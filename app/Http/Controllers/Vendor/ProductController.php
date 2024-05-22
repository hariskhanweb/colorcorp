<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductMedias;
use App\Models\ProductCategory;
use Illuminate\Support\Str;
use DB;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\VendorShopSettings;
use Helper;

class ProductController extends Controller
{
    //
    public function index($vendor_name) {
        $user = Auth::user();
        $userid = $user->id; 
        $shop_url_slug=Helper::getShopslug($user->id);
        if($vendor_name!==$shop_url_slug){
            return redirect()->route('vendor.product',['vendor_name' =>$shop_url_slug]);            
        }
        $prodrecord = Product::select('*')->where('vendor_id','=',$userid)->get();
        return view('vendordashboard.products.product')->with(compact('prodrecord'));
    }

    public function viewProduct($name, $id){
        $user = Auth::user();
        $shop_url_slug=Helper::getShopslug($user->id);
        if($name!==$shop_url_slug){
            return  redirect()->route('vendor.product.edit',['vendor_name' => $shop_url_slug, 'id' => $id]);            
        }
        $prodrecord = Product::select('*')->where('id','=', $id)->where('vendor_id','=',$user->id)->first();
        if($prodrecord){
            $prodfeatured = ProductMedias::select('*')->where('product_id','=', $id)->where('is_featured','=', 1)->get();
            $prodmedia = ProductMedias::select('*')->where('product_id','=', $id)->where('is_featured','=', 0)->get();
            $prodattributes = ProductAttributes::select('*')->where('product_id','=', $id)->get();
            return view('vendordashboard.products.editproduct')->with(compact('prodrecord','prodmedia','prodfeatured','prodattributes')); 
        }else{
            abort(404);
        }        
    }
}