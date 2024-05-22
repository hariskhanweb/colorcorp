<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
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

class CustomerMangController extends Controller
{
    //
    public function index() {
        $user = Auth::user();
        $data=User::where("id", $user->id)->first();
        $page = Helper::getPageData(Auth::user()->vendor_id);
        return view('customer.account',compact('data','page'));
    }

    public function accountUpdate(Request $request){ 
        $validated = $request->validate([
            'name'     => 'required|max:255',
            'mobile_number' => 'required',
        ]);

        $user = Auth::user();
        $userdata = user::find($user->id);
        $userdata->name =  $request->name;        
        $userdata->mobile_number = $request->mobile_number;
    
        $userdata->update();
        
       
        return redirect()->route('account')->with([
            'message'    => 'account data updated successfully',
            'alert-type' => 'success',
        ]);
        
    }  

    public function accountAddresses() {

        $user = Auth::user();

        $data=User::where("id", $user->id)->first();
        $cusdata=CustomerAddresses::where("user_id", $user->id)->get();
        $cusshipdata=CustomerAddresses::where("user_id", $user->id)
        ->where("address_type", "0")->get();
        $cusbilldata=CustomerAddresses::where("user_id", $user->id)
        ->where("address_type", "1")->get();
        $countries = Country::get(["name", "id"]);
        $page = Helper::getPageData($user->vendor_id); 

        if($cusdata){
            return view('customer.customer-address',compact('cusdata','cusshipdata','cusbilldata','page'));
        }else{
            return view('customer.customer-address-add',compact('data','countries','page'));
        }        
    }

    public function accountAddressesAdd() {
        $user = Auth::user();
        $data=User::where("id", $user->id)->first();    
        $countries = Country::get(["name", "id"]);  
        $page = Helper::getPageData($user->vendor_id); 
        return view('customer.customer-address-add',compact('data','countries','page'));
    }

    public function accountAddressesCreate(Request $request){ 
      
        $validated = $request->validate([
            'address'       => 'required',
            'city'          => 'required',
            'state'         => 'required',
            'country'       => 'required',
            'postcode'      => 'required',
            'mobile_number'  => 'required',
            
        ]);

        //dd($request->all());exit;

        $user = Auth::user();
        if($request->default_address=="1"){
            $this->updateDefaultAdd($user->id,$request->address_type);
            $default_address= 1;
        }else{
            $default_address= 0;
        }
    
        $useradd = new CustomerAddresses;       
        $useradd->address = $request->address;
        $useradd->city = $request->city;
        $useradd->state_id = $request->state;
        $useradd->country_id = $request->country;
        $useradd->postcode = $request->postcode;
        $useradd->default_address = $default_address;
        $useradd->mobile_number = $request->mobile_number;
        $useradd->address_type = $request->address_type;
        $useradd->user_id = $user->id;
    
        $useradd->save();
        
       
        return redirect()->route('account.addresses')->with([
            'message'    => 'Address Added Successfully',
            'alert-type' => 'success',
        ]);
        
    }

    public function accountAddressesEdit(Request $request,$id) {

        $user = Auth::user();

        $data=User::where("id", $user->id)->first();
        $cusdata=CustomerAddresses::where("user_id", $user->id)->where("id", $id)->first();

        $countries = Country::get(["name", "id"]);
        $state = State::where("country_id",$cusdata->country_id)->get(["name", "id"]);
        $page = Helper::getPageData($user->vendor_id);
        return view('customer.customer-address-edit',compact('cusdata','countries','state','page'));

        
    }

    public function accountAddressesUpdate(Request $request){ 
        $validated = $request->validate([
            'address'       => 'required',
            'city'          => 'required',
            'state'         => 'required',
            'country'       => 'required',
            'postcode'      => 'required',
        ]);
        $user = Auth::user();
        if($request->default_address){
            $this->updateDefaultAdd($user->id,$request->address_type);
            $default_address= 1;
        }else{
            $default_address= 0;
        }

        $cusdata=CustomerAddresses::where("user_id", $user->id)->first();
    
        $useradd = CustomerAddresses::find($request->edit_id);       
        $useradd->address = $request->address;
        $useradd->city = $request->city;
        $useradd->state_id = $request->state;
        $useradd->country_id = $request->country;
        $useradd->postcode = $request->postcode;
        $useradd->mobile_number = $request->mobile_number;
        $useradd->default_address = $default_address;
        $useradd->address_type = $request->address_type;
        $useradd->user_id = $user->id;
    
        $useradd->update();
        
       
        return redirect()->route('account.addresses')->with([
            'message'    => 'Address successfully updated',
            'alert-type' => 'success',
        ]);
        
    }

    public function updateDefaultAdd($user_id, $address_type){
        CustomerAddresses::where('user_id', '=', $user_id)
            ->where('address_type', '=', $address_type)
            ->update([
                'default_address' => 0
            ]);
    }
    public function accountAddressesDetete(Request $request) {
        $user_data=CustomerAddresses::where('id', $request->address_id)->delete();
        return back()->with('message', 'Successfully Deleted Address')->with('alert-type', 'success');
    }

    public function accountResetPassword() {   
        $user = Auth::user();
        $page = Helper::getPageData($user->vendor_id);
        return view('customer.customer-reset-pass',compact('page'));       
    }
}