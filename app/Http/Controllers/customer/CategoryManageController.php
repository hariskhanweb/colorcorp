<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ChildCategories;
use App\Models\Product;
use App\Models\Pages;
use App\Helpers\Helpers;
use Helper;
use DB;
use Auth;

class CategoryManageController extends Controller
{
    //
    public function vendorRediect($vendor_name){
        $user = Auth::user();
        $shopslug = Helper::getShopslug($user->vendor_id);
        return redirect()->route('shop',['vendor_name' => $shopslug]);            
    }
    
    public function index(Request $request, $vendor_name) {
        //dd($vendor_name);exit;
        $vendorid = Auth::user()->vendor_id;
        $user     = Auth::user();
        $shopslug = Helper::getShopslug($vendorid);
        $accessoriesId = Helpers::getAccessoriesCatId();
        if($vendor_name!==$shopslug){
            return redirect()->route('shop',['vendor_name' => $shopslug]);            
        }
        // $records  = ProductCategory::select('*')
        //             ->where('status', '=', 1)
        //             ->where('has_parent', '=', 0)
        //             ->where('id', '!=', $accessoriesId)
        //             ->paginate(12);
        $records  = ProductCategory::select('*')
                    ->where('status', '=', 1)
                    ->where('has_parent', '=', 0)
                    ->where('id', '!=', $accessoriesId)
                    ->where('vendor_id', '=', $vendorid)
                    ->paginate(12);
        $records_count  = ProductCategory::select('*')
                    ->where('status', '=', 1)
                    ->where('has_parent', '=', 0)
                    ->where('vendor_id', '=', $vendorid)
                    ->where('id', '!=', $accessoriesId)->count();
        $page = Pages::where('vendor_id', $vendorid)
                    ->where('is_home', 1)
                    ->where('status', 1)
                    ->first();            
        // print_r($page);exit;       
        return view('customer.category.customer-category', compact('records', 'shopslug','records_count','page'));
    }

    public function loadMoreCategory(Request $request) {
        $vendor_id = Auth::user()->vendor_id;    
        $html   = "";
        $shopslug      = Helper::getShopslug(Auth::user()->vendor_id);
        $accessoriesId = Helpers::getAccessoriesCatId();
        $search = $request->input('text');
        if($search){
            $records = ProductCategory::select('*')->where('status', '=', 1)->where('name', 'LIKE', '%'.$search.'%')->where('has_parent', '=', 0)->where('vendor_id', '=', $vendor_id)->where('id', '!=', $accessoriesId)->paginate(12);
        } else {
            $records  = ProductCategory::select('*')->where('status', '=', 1)->where('has_parent', '=', 0)->where('vendor_id', '=', $vendor_id)->where('id', '!=', $accessoriesId)->paginate(12);
        }
        foreach($records as $record) {
            $html .= '<div class="lg:w-1/4 md:w-1/2 w-full grid-item" data-title="'.$record->name.'">
            <a href="'.url('/'.$shopslug.'/'.$record->slug).'">
              <div class="bg-sky-200 p-4 m-2 h-80 shine-overlay-bg">
                <img src="'.asset('storage/'.$record->image).'" alt="" class="mx-auto py-4">
                <h4 class="service_title w-full">'.$record->name.'</h4>
              </div>
            </a>
          </div>';
        } 
        return response()->json(['success' => 1, 'result' => $html ]); 
    }

    public function getSubCategoryList($parent_id = null) {
        $records = ProductCategory::whereHas('child_categories', function ($query) use($parent_id) {
                    return $query->where('cat_id', '=', $parent_id);
                })->select('*')->where('status', '=', 1)->where('has_parent', '=', 1)->get();

        return $records;
    }

    public function showSubcategories(Request $request, $shopslug, $categoryslug){
        $vendorid = Auth::user()->vendor_id;
        $catRecord = ProductCategory::select('id', 'name')->where('slug', '=', $categoryslug)->where('vendor_id', '=', $vendorid)->where('status', '=', 1)->where('has_parent', '=', 0)->first();

        if(!empty($catRecord)) {
            $catID = $catRecord ? $catRecord->id:"";
            $catName = $catRecord? $catRecord->name:"";
            $records = $this->getSubCategoryList($catID);

            $page = Helper::getPageData($vendorid);   

        return view('customer.category.customer-subcategory', compact('records', 'shopslug', 'categoryslug', 'page'));
        } else {
            return redirect($shopslug);
        }
    }

    public function searchCategories(Request $request) {
        $vendor_id = Auth::user()->vendor_id;
        $html   = "";
        $search = $request->input('text');
        $shopslug = Helper::getShopslug($vendor_id);
        $accessoriesId = Helpers::getAccessoriesCatId();
        if(empty($search)) {
            $records = ProductCategory::select('*')->where('status', '=', 1)->where('has_parent', '=', 0)->where('vendor_id', '=', $vendor_id)->where('id', '!=', $accessoriesId)->get();
        } else {
            $records = ProductCategory::select('*')->where('status', '=', 1)->where('name', 'LIKE', '%'.$search.'%')->where('has_parent', '=', 0)->where('vendor_id', '=', $vendor_id)->where('id', '!=', $accessoriesId)->paginate(12);
        }

        // $html .= '<div id="category-wrapper" class="grid js-masonry gap-x-3 js-masonry flex flex-wrap container mx-auto">';
        foreach($records as $record) {
            $html .= '<div class="lg:w-1/4 md:w-1/2 w-full grid-item" style="position: absolute; left: 0px; top: 0px;">
                <a href="'.url('/'.$shopslug.'/'.$record->slug).'">
                  <div class="bg-sky-200 p-4 m-2 h-80 shine-overlay-bg">
                    <img src="'.asset('storage/'.$record->image).'" alt="" class="mx-auto py-4">
                    <h4 class="service_title w-full">'.$record->name.'</h4>
                  </div>
                </a>
            </div>';
        } 
        // $html .='</div>';
        /*if($records>12){
            $html .='<div class="text-center my-8">
              <a href="#" class="green_btn px-12" id="load-more-services"><span>Load More Services</span></a>
            </div>';
        }*/
 
        if(!empty($records)) {
            return response()->json(['success' => 1, 'result' => $html ]);
        } else {
            return response()->json(['success' => 0, 'result' => $html ]);
        }
    }

    public function searchResults(Request $request){     
        $user          = Auth::user();
        $vendor_id     = $user->vendor_id;
        $accessoriesId = Helpers::getAccessoriesCatId();
        $shopslug      = Helpers::getShopslug($user->vendor_id);
        $vendor_id     = $user->vendor_id;
        $key           = $request->search_key;

        $page = Helper::getPageData($vendor_id);  

        if($request->search_type == 'vehicle'){
            if($key == ""){
                $productlist = Product::select('products.*', 'product_categories.category_id')
                    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                    ->where('product_categories.parent_category_id', "!=", $accessoriesId)
                    ->where('vendor_id', '=', $vendor_id)
                    ->where('status', '=', 1)
                    ->get();
            } else {
                $productlist = Product::select('products.*', 'product_categories.category_id')
                    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                    ->where('product_categories.parent_category_id', "!=", $accessoriesId)
                    ->where('vendor_id', '=', $vendor_id)
                    ->where('name', 'LIKE', '%'.$key.'%')
                    ->where('status', '=', 1)
                    ->get();
            } 
        } else {
            if($key == ""){
                $productlist = Product::select('products.*', 'product_categories.parent_category_id')
                    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                    ->where('product_categories.parent_category_id', "=", $accessoriesId)
                    ->where('vendor_id', '=', $vendor_id)
                    ->where('status', '=', 1)
                    ->groupBy('products.id')
                    ->get();
            } else {
                $productlist = Product::select('products.*', 'product_categories.parent_category_id')->join('product_categories', 'products.id', '=', 'product_categories.product_id')->where('product_categories.parent_category_id', "=", $accessoriesId)->where('vendor_id', '=', $user->vendor_id)->where('name', 'LIKE', '%'.$key.'%')->where('status', '=', 1)->groupBy('products.id')->get();
            }
        }
        return view('customer.search.results', compact('productlist', 'shopslug', 'page'));
    }

}
