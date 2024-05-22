<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\InstallationChargeItems;
use App\Models\InstallationCharges;
use App\Models\OrderItems;
use App\Models\OrderComments;
use App\Models\Orders;
use App\Models\User;
use App\Models\State;
use App\Models\Country;
use Redirect;
use Helper;
use Mail;
use DB;
use PDF;

class InstallationInvoiceController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    public function index(Request $request)
    {
        $orders =[];
        $orders = Orders::leftJoin('order_items as oi', 'oi.order_id', '=', 'orders.id')
               ->leftJoin('installation_charges as ic', 'ic.order_id', '=', 'orders.id')
               ->select('orders.*','ic.status as ic_status','ic.attachment','ic.id as icid','ic.inv_number','ic.total_charges','ic.created_at as ic_created_at')
               ->where(DB::raw('oi.installation'), '=', 'yes')
               ->groupBy('oi.order_id')
               ->orderBy('orders.id', 'DESC')
               ->get();    
        return Voyager::view('voyager::installation-invoice.browse', compact('orders'));
    }

    public function createInvoice($orderID)
    {
        $chkData = InstallationCharges::where("order_id", $orderID)->first();
        if(empty($chkData)){
            $dataType = Voyager::model('DataType')->where('slug', '=', 'orders')->first();

            $isSoftDeleted = false;

            if (strlen($dataType->model_name) != 0) {
                $model = app($dataType->model_name);
                $query = $model->query();

                // Use withTrashed() if model uses SoftDeletes and if toggle is selected
                if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                    $query = $query->withTrashed();
                }
                if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                    $query = $query->{$dataType->scope}();
                }
                $dataTypeContent = call_user_func([$query, 'findOrFail'], $orderID);
                if ($dataTypeContent->deleted_at) {
                    $isSoftDeleted = true;
                }
            } else {
                // If Model doest exist, get data from table name
                $dataTypeContent = DB::table($dataType->name)->where('id', $orderID)->first();
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'read');

            // Check permission
            $this->authorize('read', $dataTypeContent);

            // Check if BREAD is Translatable
            $isModelTranslatable = is_bread_translatable($dataTypeContent);

            // Eagerload Relations
            $this->eagerLoadRelations($dataTypeContent, $dataType, 'read', $isModelTranslatable);

            $orderItems = OrderItems::where('order_id',$orderID)->get();
            $order      = Orders::find($orderID);

            foreach ($dataTypeContent->orderItems as $key => $value) {
                if($value->installation == 'no') {
                    unset($dataTypeContent->orderItems[$key]);
                }
            }
            
            return Voyager::view('voyager::installation-invoice.edit-add', compact('dataTypeContent', 'orderItems', 'order'));
        }else{
            $orders =[];
            $orders = Orders::leftJoin('order_items as oi', 'oi.order_id', '=', 'orders.id')
                   ->leftJoin('installation_charges as ic', 'ic.order_id', '=', 'orders.id')
                   ->select('orders.*','ic.status as ic_status','ic.attachment','ic.id as icid','ic.inv_number','ic.total_charges','ic.created_at as ic_created_at')
                   ->where(DB::raw('oi.installation'), '=', 'yes')
                   ->groupBy('oi.order_id')
                   ->orderBy('orders.id', 'DESC')
                   ->get();     
            return redirect()->route('installation-invoice')->with([
                'adminuser'  => $orders,
            ]);
        }
    }

    public function saveInvoice(Request $request)
    {       
        $user_id  = $request->user_id;
        $order_id = $request->orderID;

        $ic = new InstallationCharges;
        $token = rand(0, 9999999999);
        $ic->user_id        = $user_id;        
        $ic->order_id       = $order_id;        
        $ic->inv_number     = 'INV-'.$token;
        $ic->total_charges  = $request->totalIC;
        $ic->status         = 1;
        $ic->save();

        $installation_charge_id = $ic->id;

        $item_name = explode(',', $request->item_name);
        $item_id = explode(',', $request->item_id);
        $charges = explode(',', $request->charges);

        foreach ($item_name as $key => $value) {
            $ici = new InstallationChargeItems;
            $ici->installation_charge_id = $installation_charge_id; 
            $ici->order_item_id          = $item_id[$key]; 
            $ici->order_item             = $value; 
            $ici->charges                = $charges[$key]; 
            $ici->save();       
        } 

        // generate PDF
        $this->genrateInstallationInvoicePdf($installation_charge_id, $user_id, $order_id);
            
        // start mail sec
        $ICdata = InstallationCharges::where("id", $installation_charge_id)->first();
        $data = Orders::where("id", $ICdata->order_id)->orderBy('id', 'DESC')->with('userOrder')->with('shippingAddress')->first()->toArray();

        if($ICdata['status'] == 2){ 
            $status = "<span style='color:green;'>Completed</span>";
        } else if($ICdata['status'] == 0) { 
            $status = "<span style='color:red;'>Trash</span>";
        } else { 
            $status = "<span style='color:red;'>Pending</span>";
        }

        $shopslug  = Helper::getShopslug($data['vendor_id']);
        $url       = route('invoiceCheckout', ['vendor_name' => $shopslug, 'id' => base64_encode($installation_charge_id)]);

        $details = [
            'username'      => $data['shipping_address']['name']??'NA',
            'order_number'  => $data['order_number'],
            'inv_number'    => $ICdata->inv_number,
            'status'        => $status,
            'attachment'    => asset('installation-invoices/'.$ICdata->attachment.''),
            'amount'        => setting('payment-setting.currency').$ICdata->total_charges,
            'filename'      => $ICdata->attachment,
            'url'           => $url,
            'subject'       => 'Installation Invoice Mail',
            'to'            => $data['user_order']['email'],
        ];

        $subject    = 'Installation Invoice Mail';
        $to         = $data['user_order']['email'];
        $toname     = $data['shipping_address']['name']??'NA';
        $mailtype   ='installation-invoice';
        $filename   = $ICdata->attachment;
        
        // Helper::setMailWeb($subject,$to,$toname,$details,$mailtype,$filename);

        $files = [
            public_path('installation-invoices/'.$filename.'')
        ];

        Mail::send('emails.InstallationInvoiceMail', $details, function($message)use($details, $files) {
            $message->to($details["to"])
                    ->subject($details["subject"]);
            foreach ($files as $file){
                $message->attach($file);
            }   
        });
        // end mail section

        $orders =[];
        $orders = Orders::leftJoin('order_items as oi', 'oi.order_id', '=', 'orders.id')
               ->leftJoin('installation_charges as ic', 'ic.order_id', '=', 'orders.id')
               ->select('orders.*','ic.status as ic_status','ic.attachment','ic.id as icid','ic.inv_number','ic.total_charges','ic.created_at as ic_created_at')
               ->where(DB::raw('oi.installation'), '=', 'yes')
               ->groupBy('oi.order_id')
               ->orderBy('orders.id', 'DESC')
               ->get();     
        return redirect()->route('installation-invoice')->with([
            'adminuser'  => $orders,
            'message'    => 'Successfully Invoice Generated',
            'alert-type' => 'success',
        ]);
    }

    public function genrateInstallationInvoicePdf($installation_charge_id, $user_id, $order_id) {

        $ICdata = InstallationCharges::where("id", $installation_charge_id)->where("user_id", $user_id)->where("order_id", $order_id)->first();
        $ICIdata = InstallationChargeItems::where("installation_charge_id", $ICdata->id)->orderBy('id', 'DESC')->get();
        $UserData = User::select('name')->find($ICdata->user_id);
        $OrderData = Orders::select('order_number')->find($ICdata->order_id);

        $imgpath = asset('/storage/');

        //var_dump($data); die();

        if(empty($ICdata)){
            return redirect()->route('createInvoice');        
        }

        $filename = "Installation_".trim($ICdata->inv_number).".pdf";

        $ic = InstallationCharges::find($installation_charge_id);
        $ic->attachment = $filename;
        $ic->save(); 

        $pdf = PDF::loadView('emails.installation-invoice-pdf', compact('ICdata','ICIdata','UserData','OrderData','imgpath'))->setOptions(['isRemoteEnabled' => true]);
        
        $pdf->save(public_path('installation-invoices/'.$filename));   
        return $pdf->download($filename);
    }
}
