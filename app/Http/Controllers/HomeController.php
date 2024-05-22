<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\VendorShopSettings;
use Helper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if($user->role_id==2){ 
                
                $vendorInfo    = VendorShopSettings::where("vendor_id", $user->id)->first(); 
                if($vendorInfo){
                    $shop_url_slug = Helper::getShopslug($user->id);
                    return redirect()->route('vendor.dashboard', ['vendor_name' => $shop_url_slug])->with('success', 'Login successful');
                }else{
                    return redirect()->route('vendor.shopSetting')->with('success', 'Login successful');
                }
            }elseif($user->role_id==3){
                $shop_url_slug=Helper::getShopslug($user->vendor_id);
                return redirect()->route('vendor.index', ['vendor_name' => $shop_url_slug]);
            }elseif($user->role_id==1){
                return redirect('/admin');
            }
        }else{
            return redirect('/login');
        }
        //return view('home');
        
    }
}
