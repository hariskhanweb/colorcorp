<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;
use Redirect;
use App\Helpers\Helpers;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductMedias;
use App\Models\ProductCategory;
use Helper;

class ProductManageController extends Controller
{
    public function productslist($vendor_name, $parentcatslug ,$catslug) {
        $user = Auth::user();

        $shopslug = Helpers::getShopslug($user->vendor_id);
        if($vendor_name!==$shopslug){
            return redirect()->route('product.list',['vendor_name' => $shopslug, 'category' => $catslug]);
        }

        $parentcatId = '';
        $parentcatrecord = ProductCategory::select('id')->where('slug','=',$parentcatslug)->where('status', '=', 1)->first();
        if($parentcatrecord) {
            $parentcatId = $parentcatrecord->id;
        } else {
            return redirect($vendor_name);
        }
        
        $catId = '';
        $catrecord = ProductCategory::select('id')->where('slug','=',$catslug)->where('status', '=', 1)->first();
        if($catrecord) {
            $catId = $catrecord->id;
        } else {
            return redirect($parentcatslug);
        }
        
        $productlist = $productlist = Product::select('products.*')
                    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                    ->where('product_categories.parent_category_id', $parentcatId)
                    ->where('product_categories.category_id', $catId)
                    ->where('vendor_id', '=', $user->vendor_id)
                    ->where('status', '=', 1)
                    ->paginate(12);

        $page = Helper::getPageData($user->vendor_id);             

        return view('customer.products.product-list')->with(compact('shopslug','catslug','productlist', 'parentcatslug','page'));
    }

    public function singleproductdetail($vendor, $category, $prodslug){
        $user = Auth::user();
        if(trim($prodslug) != ''){
            $productrecord = Product::where('slug', '=', $prodslug)->where('status', '=', 1)->first();
            if(!empty($productrecord) && $productrecord->vendor_id == $user->vendor_id){
                $productid = $productrecord->id;
                $vendor = Helpers::getShopslug($user->vendor_id);
                $catrecord = ProductCategory::select('*')->where('slug','=',$category)->first();
                $catslug = $catrecord->slug;
                $catgryid = $catrecord->id;

                $prodattribes = DB::table('product_attributes')->join('attributes','product_attributes.attribute_id','=','attributes.id')->select(DB::Raw('DISTINCT(attribute_id) as attrid'),DB::Raw('attributes.name as attribname'),DB::Raw('attributes.type as attrtype'),DB::Raw('attributes.is_price as is_price') )->where('product_id','=', $productid)->where('deleted_at','=', null)->get();
                
                $prodmedia = ProductMedias::where('product_id', '=', $productid)->get();
                $page = Helper::getPageData($user->vendor_id);
                return view('customer.products.product-single')->with(compact('vendor','catslug','productrecord','prodattribes','prodmedia','catgryid','page'));
            } else {
                return redirect($vendor);
            }
            
        } else {
            return redirect()->back();
        }
        
    }
}
