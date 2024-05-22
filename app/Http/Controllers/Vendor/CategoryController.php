<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ChildCategories;
use App\Models\Product;
use App\Models\VendorAdminCategories;
use App\Models\VendorShopSettings;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Auth;
use Helper;

class CategoryController extends Controller
{
    //
    public function index($vendor_name){
        if (Auth::check()) {

            $user = Auth::user();
            $user_id = auth()->user()->id;
            
            $shop_url_slug=Helper::getShopslug($user->id);

            if($vendor_name!==$shop_url_slug){
                return redirect('/'.$shop_url_slug.'/category-management');            
            }
            $categories = ProductCategory::where('vendor_id', '=', $user_id)->get();
            return view('vendordashboard.category.category', compact('categories'));
        } else {
            return redirect('/login');
        }
    }

    public function create(Request $request,$vendor_name){

        $user = Auth::user();
        $shop_url_slug=Helper::getShopslug($user->id);
        if($vendor_name!==$shop_url_slug){
            return redirect('/'.$shop_url_slug.'/category-management/add');            
        }

        $adminUser = User::select('id')->where('role_id', '=', '1')->first();
        $admin_user_id = $adminUser->id;
        $categories = ProductCategory::where('vendor_id', '=', $admin_user_id)->where('has_parent', '=', 0 )->get();
        return view('vendordashboard.category.category-add', compact('categories'));
    }

    public function store(Request $request){
        $vendata = Session::get('vendordata');
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'vendor_id' => 'required',
            'status'=> 'required',
            'image' => 'required|mimes:jpeg,png,jpg|max:1024',
        ]);

        $category = new ProductCategory;
        $category->name =  $request->name;        
        $category->slug = $request->slug;
        $category->vendor_id = $request->vendor_id;
        $category->status = $request->status;
        $category->has_parent= $request->has_parent;
        if($request->has_parent == 1) {
            $category->parent_id = $request->parent_id;
        }
        if($request->hasfile('image')){
            $img_path = 'public/product-categories/';
            $file = $request->file('image');            
            $destinationPath = 'product-categories';
            $filename = time().'-'.$file->getClientOriginalName(); 
            $file->storeAs($img_path, $filename);    
            $category->image = 'product-categories/'.$filename;
        }
        $category->meta_title       = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords    = $request->meta_keywords;
        $category->save();

        $lastRecord = ProductCategory::select('id')->orderBy('id', 'DESC')->first();
        if($request->has_parent == 1) {
            $childCategory = new ChildCategories;
            $findRecord = ChildCategories::where('child_id', '=', $lastRecord->id)->where('cat_id', '=', $request->parent_id)->first();
            if(empty($findRecord)) {
                $childCategory->cat_id =  $request->parent_id;
                $childCategory->child_id =  $lastRecord->id;
                $childCategory->save();
            }
        }

        return redirect('/'.$vendata['shop_url_slug'].'/category-management')->with([
            'message'    => 'Category added successfully',
            'alert-type' => 'success',
        ]);
    }

    public function edit(Request $request, $name, $id) {

        $user = Auth::user();
        $shop_url_slug=Helper::getShopslug($user->id);
        if($name!==$shop_url_slug){
            return redirect('/'.$shop_url_slug.'/category-management/edit/'.$id);            
        }

        $adminUser = User::select('id')->where('role_id', '=', '1')->first();
        $admin_user_id = $adminUser->id;
        $categories = ProductCategory::where('vendor_id', '=', $admin_user_id)->where('has_parent', '=', 0 )->get();
        $category = ProductCategory::where('id', '=',  $id)->first();
        return view('vendordashboard.category.category-edit', compact('category', 'categories')); 
    }

    public function update(Request $request) {
        $vendata = Session::get('vendordata');
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'vendor_id' => 'required',
            'status'=> 'required',
            'image' => 'mimes:jpeg,png,jpg|max:1024',
        ]);

        $category = ProductCategory::find($request->category_id); 
        $category->name      = $request->name;        
        $category->slug      = $request->slug;
        $category->vendor_id = $request->vendor_id;
        $category->status    = $request->status;
        $category->has_parent= $request->has_parent;
        if($request->has_parent == 1) {
            $category->parent_id = $request->parent_id;
        } else {
            $category->parent_id = null;
        }
        if($request->hasfile('image')){
            $img_path = 'public/product-categories/';
            $file = $request->file('image');            
            $destinationPath = 'product-categories';
            $filename = time().'-'.$file->getClientOriginalName(); 
            $file->storeAs($img_path, $filename);    
            $category->image = 'product-categories/'.$filename;
        }
        $category->meta_title       = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords    = $request->meta_keywords;
        $category->save();

        if($request->has_parent == 1) {
            $childCategory = new ChildCategories;
            $findRecord = ChildCategories::where('child_id', '=', $request->category_id)->where('cat_id', '=', $request->parent_id)->first();
            if(empty($findRecord)) {
                $childCategory->cat_id   =  $request->parent_id;
                $childCategory->child_id =  $request->category_id;
                $childCategory->save();
            }
        }

        return redirect('/'.$vendata['shop_url_slug'].'/category-management')->with('message','Category updated successfully')->with('alert-type','success');
    }

    public function delete(Request $request, $name, $id) {
        $user = Auth::user();
        $shop_url_slug = Helper::getShopslug($user->id);

        $productIds = array();
        $products = Product::select('id')->where('cat_id', $id)->get();
        foreach($products as $product) {
            $productIds[] = $product->id;
        }
        ProductCategory::where('id', $id)->delete();
        Product::whereIn('id', $productIds)->delete();
        ChildCategories::where('cat_id', $id)->delete();
        ChildCategories::where('child_id', $id)->delete();

        return redirect('/'.$shop_url_slug.'/category-management')->with([
            'message'    => 'Category deleted successfully',
            'alert-type' => 'success',
        ]);
    }

    public function showAdminCategories(Request $request,$name) {

         $user = Auth::user();
        $shop_url_slug=Helper::getShopslug($user->id);
        if($name!==$shop_url_slug){
            return redirect('/'.$shop_url_slug.'/category-management/admin-categories');            
        }

        $categories = $this->getAdminCategories();
        return view('vendordashboard.category.admin-categories', compact('categories'));
    }

    public function getAdminCategories() {
        $adminUser = User::select('id')->where('role_id', '=', '1')->first();
        $admin_user_id = $adminUser->id;
        $categories = ProductCategory::join('vendor_admin_categories', 'categories.id', '=', 'vendor_admin_categories.category_id')->where('categories.vendor_id', '=', $admin_user_id)->where('has_parent', '=', 0 )->get(['categories.*', 'vendor_admin_categories.status as vendor_admin_category_status' ]);
        return $categories;
    }

    public function storeVendorAdminCategory(Request $request) {
        $vendata  = Session::get('vendordata');
        $category = VendorAdminCategories::find($request->category_id);
        if(empty($category)) {
            $category = new VendorAdminCategories; 
        } 
        $category->category_id = $request->category_id;        
        $category->status      = $request->status;
        $category->vendor_id   = $request->vendor_id;
        $category->save();

        $categories = $this->getAdminCategories();
        return redirect('/'.$vendata['shop_url_slug'].'/category-management/admin-categories')->with('categories');
    }
}