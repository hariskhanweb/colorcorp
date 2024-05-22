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
use App\Models\CustomerAddresses;
use App\Models\Country;
use App\Models\State;
use Redirect;
use Mail;
use Helper;
use File;
use DB;

class CustomerOrderController extends Controller
{
    //
    public function myOrder() {
        $user = Auth::user();
        $data=Orders::where("user_id", $user->id)->orderBy('id', 'DESC')->get();
        $page = Helper::getPageData($user->vendor_id); 
        return view('customer.order.my-order',compact('data','page'));
    }

    public function viewOrder(Request $request, $id) {
        $user = Auth::user();
        $data = Orders::where("id", $id)->orderBy('id', 'DESC')->first();
        $ICdata = InstallationCharges::where("order_id", $id)->where("user_id", $user->id)->first();
        $page = Helper::getPageData($user->vendor_id); 
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

        if(empty($data)){
            return redirect()->route('my.order');        
        }

        return view('customer.order.order-view', compact('data','ICdata','page')); 
    }   
}