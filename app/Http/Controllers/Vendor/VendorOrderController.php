<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\InstallationCharges;
use App\Models\InstallationChargeItems;
use App\Models\OrderComments;
use App\Models\Orders;
use App\Models\User;
use App\Mail\userVerifyMail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\VendorShopSettings;
use App\Mail\UserOrderStatusMail;
use Redirect;
use Mail;
use Helper;
use File;
use DB;

class VendorOrderController extends Controller
{
    //
    public function index($vendor_name) {
        $user = Auth::user();
        $shop_url_slug=Helper::getShopslug($user->id);
        if($vendor_name!==$shop_url_slug){          
            return redirect()->route('vendor.customer', ['vendor_name' => $shop_url_slug ]);            
        }
        $data=Orders::where("vendor_id", $user->id)->orderBy('id', 'DESC')->get();
        return view('vendordashboard.order.order-list',compact('data'));
    }

    public function edit(Request $request, $name, $id) {
        $user = Auth::user();
        $shop_url_slug=Helper::getShopslug($user->id);
        if($name!==$shop_url_slug){
           return redirect()->route('vendor.order.edit',['vendor_name' =>$shop_url_slug,'id' =>$id]);           
        }
        $data=Orders::where("id", $id)->orderBy('id', 'DESC')->first();

        $ICdata = InstallationCharges::where("order_id", $id)->first();
        $ICIdata = [];

        foreach ($data['orderItems'] as $key => $value) {
            $ICIdata    = InstallationChargeItems::where("order_item_id", $value->id)->first();
            if(!empty($ICIdata)){
                if($value->installation=='yes' && $ICIdata->order_item_id==$value->id){
                    $data['orderItems'][$key]['charges'] = $ICIdata->charges;
                }else{
                    $data['orderItems'][$key]['charges'] = 0;
                }
            }else{
                $data['orderItems'][$key]['charges'] = 0;
            }
        }
        
        return view('vendordashboard.order.order-edit', compact('data','ICdata')); 
    }

     public function update(Request $request) {
        $current_user  = Auth::user();
        $shop_url_slug = Helper::getShopslug($current_user->id);
        $validated = $request->validate([
            'order_id' => 'required',
            'status' => 'required',
        ]);

        $data=Orders::where("id", $request->order_id)->orderBy('id', 'DESC')->first(); 

        $order = Orders::find($request->order_id); 
        $order->status      = $request->status;       
        $order->save();

        if(!empty($request->order_status_comment)){
            $order_comment = new OrderComments; 
            $order_comment->order_id      = $request->order_id; 
            $order_comment->commentor_id  = $current_user->id;  
            $order_comment->user_id       = $order->user_id;  
            $order_comment->note          = $request->order_status_comment;   
            $order_comment->save();
        }
         
        $user = User::select('name','email')->where('id', $order->user_id)->first();
        $name  = $user->name;            
        $email = $user->email; 
        //$email = "shahinafwork@gmail.com"; 
     
        if($request->status == 1){
                $status = 'Pending';
        }else if($request->status == 2){ 
            $status = 'Completed';                
        }else{
            $status = 'Trash';
        }  
        $details = [
            'username'  => $name,
            'status'    => $status,
            'note'      => $request->order_status_comment,
            'data1'      => $order,
        ];

        if($data['status'] != $request->status){
            Mail::to($email)->send(new UserOrderStatusMail($details));
        }
        return redirect()->route('vendor.order', ['vendor_name' => $shop_url_slug ])->with([
            'message'    => 'Order updated successfully',
            'alert-type' => 'success',
        ]); 
    }
   
}
