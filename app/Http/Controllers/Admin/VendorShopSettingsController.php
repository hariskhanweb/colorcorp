<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use App\Models\VendorShopSettings;
use Helper;
use File;
use DB;

class VendorShopSettingsController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    public function shopSetting()
    {
        $data = array(
            'title' => 'Vendor Shop Setting',
        );
        return Voyager::view('voyager::vendor.add',compact('data'));
    }

    public function createShopSetting(Request $request)
    {        
        $validated = $request->validate([
            'shop_name'     => 'required',
            'shop_email'    => 'email|unique:vendor_shop_settings',
            'shop_mobile'   => 'required|numeric', 
            'shop_url_slug' => 'required|unique:vendor_shop_settings',
            'shop_logo'     =>'required|image|mimes:jpg,png,jpeg|max:20000',
            'shop_banner'   =>'required|image|mimes:jpg,png,jpeg|max:20000',
            'address'       => 'required',
            'city'          => 'required',
            'state'         => 'required',
            'country'       => 'required',
            'postcode'      => 'required',
        ]);

        $vendor = new VendorShopSettings;
        
        $vendor->shop_name     =  $request->shop_name;        
        $vendor->shop_email    =  $request->shop_email;        
        $vendor->shop_mobile   =  $request->shop_mobile;        
        $vendor->shop_url_slug =  $request->shop_url_slug;        
        $vendor->address       =  $request->address;        
        $vendor->city          =  $request->city;        
        $vendor->state         =  $request->state;        
        $vendor->country       =  $request->country;        
        $vendor->postcode      =  $request->postcode;        
        
        if ($request->hasFile('shop_logo')) {
            $file = $request->file('shop_logo');
            $extenstion = $file->getClientOriginalName();
            $filename = time().'_'.$extenstion;
            $file->move('uploads/vendors/', $filename);
            $vendor->shop_logo = $filename;
        }

        if ($request->hasFile('shop_banner')) {
            $file = $request->file('shop_banner');
            $extenstion = $file->getClientOriginalName();
            $filename = time().'_'.$extenstion;
            $file->move('uploads/vendors/', $filename);
            $vendor->shop_banner = $filename;
        }
    
        $vendor->save();
      
        return redirect()->route('voyager.shopSetting')->with([
            'message'    => 'Successfully Saved Shop Setting',
            'alert-type' => 'success',
        ]);

    }

    public function editShopSetting(Request $request, $id)
    {
        $vendorInfo = VendorShopSettings::find($id);
        $data       = array(
            'id'         => $id,
            'title'      => 'Vendor Shop Setting',
            'vendorInfo' => $vendorInfo
        );
        return Voyager::view('voyager::vendor.edit',compact('data'));
    }

    public function updateShopSetting(Request $request, $id)
    {
        $validated = $request->validate([
            'shop_name'     => 'required',
            'shop_email'    => 'required|email',
            'shop_mobile'   => 'required',
            'shop_url_slug' => 'required',
            'address'       => 'required',
            'city'          => 'required',
            'state'         => 'required',
            'country'       => 'required',
            'postcode'      => 'required',
        ]);

        $vendor = VendorShopSettings::find($id);
        $vendor->shop_name     =  $request->shop_name;        
        $vendor->shop_email    =  $request->shop_email;        
        $vendor->shop_mobile   =  $request->shop_mobile;        
        $vendor->shop_url_slug =  $request->shop_url_slug;        
        $vendor->address       =  $request->address;        
        $vendor->city          =  $request->city;        
        $vendor->state         =  $request->state;        
        $vendor->country       =  $request->country;        
        $vendor->postcode      =  $request->postcode;        
        
        if($request->shop_logo != ''){        
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
        }

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
        return redirect()->route('voyager.shopSetting')->with([
            'message'    => 'Successfully Updated Shop Setting',
            'alert-type' => 'success',
        ]);
    }
}
