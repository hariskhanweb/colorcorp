<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use App\Models\VendorShopSettings;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Response;
use Helper;
use File;
use DB;

class VendorShopSettingsController extends Controller
{
    public function fetchState(Request $request)
    {
        $data['states'] = State::where("country_id",$request->country_id)->get(["name", "id"]);
        return response()->json($data);
    }

    public function shopSetting()
    {
        $user = Auth::user();
        $vendorInfo=VendorShopSettings::where("vendor_id", $user->id)->first();
        $countries = Country::get(["name", "id"]);
        if($vendorInfo){
            Session::put('vendordata', $vendorInfo);
            return redirect()->route('vendor.dashboard', ['vendor_name' => $vendorInfo->shop_url_slug]);
        }else{
            $data = array(
            'title' => 'Vendor Shop Setting',
            'countries' => $countries,
            );       
            return view('vendordashboard.shop-setting',compact('data'));
        }        
    }

    public function createShopSetting(Request $request)
    {        
        $user = Auth::user();
        $validated = $request->validate([
            'shop_name'     => 'required',
            //'shop_email'    => 'email|unique:vendor_shop_settings',
            //'shop_mobile'   => 'required|numeric', 
            'shop_url_slug' => 'required|unique:vendor_shop_settings',
            //'shop_logo'     =>'required|image|mimes:jpg,png,jpeg|max:20000',
            'shop_banner'   =>'required|image|mimes:jpg,png,jpeg|max:20000',
            'address'       => 'required',
            'city'          => 'required',
            'state'         => 'required',
            'country'       => 'required',
            'postcode'      => 'required',
        ]);

        $payByValues = isset($request->pay_by) ? $request->pay_by : [];

        $vendor = new VendorShopSettings;
        $vendor->shop_name     =  $request->shop_name;        
        //$vendor->shop_email    =  $request->shop_email;        
        //$vendor->shop_mobile   =  $request->shop_mobile;        
        $vendor->shop_url_slug =  $request->shop_url_slug;        
        $vendor->pay_by        =  json_encode($payByValues);        
        $vendor->address       =  $request->address;        
        $vendor->city          =  $request->city;        
        $vendor->state_id      =  $request->state;        
        $vendor->country_id    =  $request->country;        
        $vendor->postcode      =  $request->postcode;
        $vendor->shop_body_color      =  $request->shop_body_color;
        $vendor->shop_heading_color   =  $request->shop_heading_color;
        $vendor->shop_primary_color   =  $request->shop_primary_color;
        $vendor->shop_secondary_color =  $request->shop_secondary_color;
        $vendor->shop_third_color     =  $request->shop_third_color;
        $vendor->shop_forth_color     =  $request->shop_forth_color;
        $vendor->shop_fifth_color     =  $request->shop_fifth_color; 

        $vendor->menu_bg_color         =  ($request->menu_bg_color) ? ($request->menu_bg_color) : ''; 
        $vendor->active_menu_bg_color  =  ($request->active_menu_bg_color) ? ($request->active_menu_bg_color) : ''; 
        $vendor->menu_text_color       =  ($request->menu_text_color) ? ($request->menu_text_color) : '';  
        $vendor->active_menu_text_color=  ($request->active_menu_text_color) ? ($request->active_menu_text_color) : '';  

        $vendor->vendor_id  =  $user->id;   
        if ($request->hasFile('shop_banner')) {
            $file = $request->file('shop_banner');
            $extenstion = $file->getClientOriginalName();
            $filename = time().'_'.$extenstion;
            $file->move('uploads/vendors/', $filename);
            $vendor->shop_banner = $filename;
        }    
        $vendor->save();

        $user = User::find($user->id);
        
        $details = [  
            'name'    => $user->name,              
            'email'   => $user->email,
            'pass'    => $user->temp_pass,
            'url'    => '',
            'role_id' => $user->role_id,
        ];

        // dd($details);
        
        $subject='Confirm your email';
        $to=$user->email;
        $toname=$user->name;
        $mailtype='verifyemail';
        
        Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);
        
        $user->mobile_number = $request->shop_mobile;      
        $user->temp_pass = '';      
        $user->update(); 

        Session::put('vendordata', $vendor);      
        return redirect()->route('vendor.dashboard', ['vendor_name' => $request->shop_url_slug])->with([
            'message'    => 'Successfully Saved Shop Setting',
            'alert-type' => 'success',
        ]);

    }

    public function editShopSetting(Request $request,$vendor_name)
    {
        $user = Auth::user();
        $vendorInfo=VendorShopSettings::where("vendor_id", $user->id)->first();
        if($vendor_name!==$vendorInfo->shop_url_slug){
            return redirect()->route('vendor.shopSetting.edit', ['vendor_name' => $vendorInfo->shop_url_slug]);            
        }
        $countries = Country::get(["name", "id"]);
        $state = State::get(["name", "id"]);        
        $data       = array(
            'id'         => $vendorInfo->id,
            'title'      => 'Vendor Shop Setting',
            'vendorInfo' => $vendorInfo,
            'countries'  => $countries,
            'state'      => $state,
        );
       // return Voyager::view('voyager::vendor.edit',compact('data'));
        return view('vendordashboard.shop-setting-edit',compact('data'));
    }

    public function updateShopSetting(Request $request, $id)
    {
        $validated = $request->validate([
            'shop_name'     => 'required',
            //'shop_email'    => 'email|unique:vendor_shop_settings,shop_email,'. $id,
            //'shop_mobile'   => 'required|numeric',            
            'address'       => 'required',
            'city'          => 'required',
            'state'         => 'required',
            'country'       => 'required',
            'postcode'      => 'required',
        ]);

        $user = Auth::user();
        $payByValues = isset($request->pay_by) ? $request->pay_by : [];

        $vendorInfo=VendorShopSettings::select('id','shop_url_slug')->where("vendor_id", $user->id)->first();

        $vendor = VendorShopSettings::find($vendorInfo->id);
        $vendor->shop_name     =  $request->shop_name;        
        //$vendor->shop_email    =  $request->shop_email;        
        //$vendor->shop_mobile   =  $request->shop_mobile;
        $vendor->pay_by        =  json_encode($payByValues);   
        $vendor->address       =  $request->address;        
        $vendor->city          =  $request->city;        
        $vendor->state_id      =  $request->state;        
        $vendor->country_id    =  $request->country;       
        $vendor->postcode      =  $request->postcode; 
        /*$vendor->shop_body_color      =  $request->shop_body_color;
        $vendor->shop_heading_color   =  $request->shop_heading_color;
        $vendor->shop_primary_color   =  $request->shop_primary_color;
        $vendor->shop_secondary_color =  $request->shop_secondary_color;
        $vendor->shop_third_color     =  $request->shop_third_color;
        $vendor->shop_forth_color     =  $request->shop_forth_color;
        $vendor->shop_fifth_color     =  $request->shop_fifth_color;*/    
        
        /*if($request->shop_logo != ''){        
            $path = public_path().'/uploads/vendors/';

            //code for remove old file
            if($vendor->shop_logo != ''  && $vendor->shop_logo != null && file_exists($path.$vendor->shop_logo)){
               $file_old = $path.$vendor->shop_logo;
               unlink($file_old);
            }

            //upload new file
            $file = $request->shop_logo;
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move($path, $filename);

            //for update in table
            $vendor->shop_logo = $filename;
        }*/

        if($request->shop_banner != ''){        
            $path = public_path().'/uploads/vendors/';

            //code for remove old file
            if($vendor->shop_banner != ''  && $vendor->shop_banner != null && file_exists($path.$vendor->shop_banner)){
               $file_old = $path.$vendor->shop_banner;
               unlink($file_old);
            }

            //upload new file
            $file = $request->shop_banner;
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move($path, $filename);

            //for update in table
            $vendor->shop_banner = $filename;
        }    
        $vendor->update(); 
        Session::put('vendordata', $vendor);
        return redirect()->route('vendor.dashboard', ['vendor_name' => $vendorInfo->shop_url_slug])->with([
            'message'    => 'Successfully updated Shop Setting',
            'alert-type' => 'success',
        ]);
    }

    public function updateColorSetting(Request $request, $id)
    {   
        $user = Auth::user();
        $vendorInfo=VendorShopSettings::select('id','shop_url_slug')->where("vendor_id", $user->id)->first();
        $vendor = VendorShopSettings::find($vendorInfo->id);
        $vendor->shop_body_color      =  $request->shop_body_color;
        $vendor->shop_heading_color   =  $request->shop_heading_color;
        $vendor->shop_primary_color   =  $request->shop_primary_color;
        $vendor->shop_secondary_color =  $request->shop_secondary_color;
        $vendor->shop_third_color     =  $request->shop_third_color;
        $vendor->shop_forth_color     =  $request->shop_forth_color;
        $vendor->shop_fifth_color     =  $request->shop_fifth_color; 

        $vendor->menu_bg_color         =  ($request->menu_bg_color) ? ($request->menu_bg_color) : ''; 
        $vendor->active_menu_bg_color  =  ($request->active_menu_bg_color) ? ($request->active_menu_bg_color) : ''; 
        $vendor->menu_text_color       =  ($request->menu_text_color) ? ($request->menu_text_color) : '';  
        $vendor->active_menu_text_color=  ($request->active_menu_text_color) ? ($request->active_menu_text_color) : '';  
        
        $vendor->update(); 
        Session::put('vendordata', $vendor);
        return redirect()->back()->with([
            'message'    => 'Successfully updated Shop Setting',
            'alert-type' => 'success',
        ]);
        
    }
}
