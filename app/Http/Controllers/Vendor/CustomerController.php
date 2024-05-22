<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Role;
use App\Mail\userVerifyMail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\VendorShopSettings;
use Redirect;
use Mail;
use Helper;
use File;
use DB;

class CustomerController extends Controller
{
    public function index($vendor_name) {
        $user = Auth::user();
        $shop_url_slug=Helper::getShopslug($user->id);
        if($vendor_name!==$shop_url_slug){
            return redirect()->route('vendor.customer', ['vendor_name' => $shop_url_slug]);            
        }
        $data=User::where("vendor_id", $user->id)->orderBy('id', 'DESC')->get();
    	return view('vendordashboard.customer.customer',compact('data'));
    }

    public function customerCreate($vendor_name){

        $user = Auth::user();
        
        $shop_url_slug=Helper::getShopslug($user->id);

        if($vendor_name!==$shop_url_slug){
            return redirect()->route('vendor.customer.create', ['vendor_name' => $shop_url_slug]);            
        }      

        $pass=Str::random(4).'Cc2@'.Str::random(3);            

        return view('vendordashboard.customer.customer-create',compact('pass'));
    }

    public function customerSave(Request $request){
         $validated = $request->validate([
            'name'     => 'required',
            'email'    => 'email|unique:users',
           'password'=>  ['required','min:8', 
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^\&*\)\(+=._-])[A-Za-z\d!@#\$%\^\&*\)\(+=._-]{8,}$/'],
            'mobile_number'   => 'required',
        ], [
            'name.required' => 'Name is required', 
            'password.required' => 'The password must be at least 8 characters & it should contain at least one capital letter, one digit & one special character.',                     
        ]);


        $user = Auth::user();
        $validated = $request->validate([
            'name'     => 'required',
            'email'    => 'email|unique:users',
            'password'   => 'required',
        ], [
                'name.required' => 'Name is required',                
            ]);


        $token = Str::random(20);
        
    
        $userdata = new User;
        $userdata->name =  $request->name;        
        $userdata->role_id = 3;
        $userdata->email = $request->email;
        $userdata->mobile_number = $request->mobile_number;
        $userdata->password= bcrypt($request->password);  

        $userdata->verify_token= $token;
        $userdata->verify_token_date= Carbon::now();
        $userdata->vendor_id=  $user->id;       
    
        $userdata->save();

      
        $url=url('/').'/verify/'.$token;
        $details = [  
            'name'    => $request->name,              
            'email'    => $request->email,
            'pass'    => $request->password,
            'url'    => $url,
        ];
        //Mail::to($request->email)->send(new userVerifyMail($details));
        $subject='Confirm your email';
        $to=$request->email;
        $toname=$request->name;
        $mailtype='verifyemail';
        
        Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);

         $vendata=Helper::getShopData(Auth::id());
       
        return redirect()->route('vendor.customer', ['vendor_name' => $vendata['shop_url_slug']])->with([
            'message'    => 'Customer added and invitation mail send Successfully',
            'alert-type' => 'success',
        ]);       
    }


    public function customerEdit(Request $request,$name,$id) {
        $user = Auth::user();        
        $data=User::where("id", $id)->where("vendor_id", $user->id)->first();       
        return view('vendordashboard.customer.customer-edit',compact('data'));
    }

    public function customerUpdate(Request $request,$id) {
        $validated = $request->validate([
            'name'     => 'required',
            'email'    => 'required|email',
            'mobile_number'   => 'required',
        ], [
            'name.required' => 'Name is required',                
        ]);
        $user = Auth::user();
        $data=User::where("id", $id)->where("vendor_id", $user->id)->first();
        return view('vendordashboard.customer.customer-edit',compact('data'));
    }

    public function customerDelete(Request $request, $name) {
        $user    = Auth::user();
        $vendata = Helper::getShopData(Auth::id());
        $customerId = $request->customer_id;
       // User::where("id", $customerId )->where("vendor_id", $user->id)->delete();
        Helper::deleteUserData($customerId);

        if($request->return_to == 'dashboard') {
            return redirect()->route('vendor.dashboard', ['vendor_name' => $vendata['shop_url_slug']])->with([
                'message'    => 'Deleted Customer Successfully',
                'alert-type' => 'success',
            ]); 
        } else {
            return redirect()->route('vendor.customer', ['vendor_name' => $vendata['shop_url_slug']])->with([
                'message'    => 'Deleted Customer Successfully',
                'alert-type' => 'success',
            ]); 
        }
    }
    
}
