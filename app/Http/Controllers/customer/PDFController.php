<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\InstallationCharges;
use App\Models\InstallationChargeItems;
use App\Models\Orders;
use App\Models\User;
use App\Models\Role;
use App\Models\OrderAddresses;
use App\Models\Country;
use App\Models\State;
use Redirect;
use Mail;
use Helper;
use File;
use DB;
use PDF;

class PDFController extends Controller
{
    public function getOrderInvoicePdf($id) {
        $data=Orders::where("id", $id)->orderBy('id', 'DESC')->with('orderItems')->with('shippingAddress')->with('billingAddress')->first()->toArray();

        $datashipstate = State::select('name')->where('id','=', $data['shipping_address']['state_id'])->first();
        $datashipcontry = Country::select('name')->where('id','=', $data['shipping_address']['country_id'])->first();
        $databillstate = State::select('name')->where('id','=', $data['billing_address']['state_id'])->first();
        $databillcontry = Country::select('name')->where('id','=', $data['billing_address']['country_id'])->first();
        $imgpath = asset('/storage/');

        //var_dump($data); die();

        if(empty($data)){
            return redirect()->route('my.order');        
        }

        $filename = "Invoice".trim($data['order_number']).".pdf";
        $pdf = PDF::loadView('customer.order.order-invoice-pdf', compact('data','datashipstate','datashipcontry','databillstate','databillcontry','imgpath'))->setOptions(['isRemoteEnabled' => true]);
        
        $pdf->save(public_path('invoices/'.$filename));   
        return $pdf->download($filename);
    }

    public function genrateInstallationInvoicePdf($installation_charge_id) {

        $ICdata = InstallationCharges::where("id", $installation_charge_id)->first();
        $ICIdata = InstallationChargeItems::where("installation_charge_id", $installation_charge_id)->orderBy('id', 'DESC')->get();
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
