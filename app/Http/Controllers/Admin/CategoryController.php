<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ProductCategories;
use App\Models\ChildCategories;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductMedias;
use App\Models\User;
use TCG\Voyager\Facades\Voyager;
use DB;

class CategoryController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    public function viewCategories() {
        $categories = ProductCategory::all();
        return Voyager::view('voyager::categories.browse',compact('categories'));
    }

    public function getParentCategories(Request $request) {
        $vendorId = $request->input('vendor_id');
        $categories = ProductCategory::where('vendor_id', $vendorId)
                                        ->where('has_parent', '=', 0)
                                        ->where('status', 1)
                                        ->get();
        $options = '';
        if(count($categories)>0) {
            foreach($categories as $category) {
                $options .='<option value="'.$category->id.'">'.$category->name.'</option>';
            }
        }
        return response()->json(['options' => $options]);                                
    }

    public function createCategory(Request $request) {
        $categories = ProductCategory::where('has_parent', '=', 0)->get();
        $vendors    = User::select('id','name')->where('role_id', '=', 2)->get();
        return Voyager::view('voyager::categories.add', compact('categories','vendors')); 
    }

    public function storeCategory(Request $request) {
        // dd($request->all()); exit();
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
        /*if($request->has_parent == 1) {
            $category->parent_id = $request->parent_id;
        }*/
        if($request->hasfile('image')){
            $image = $request->file('image');
            $imagenm = preg_replace('/\./', '_', pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME));
            $path = public_path(). '/storage/categories/';
            $seed = str_pad(rand(0, pow(10, 5)-1), 5, '0', STR_PAD_LEFT);
            $filename = $imagenm.$seed. '.' . $image->getClientOriginalExtension();
            $fullpath = 'categories/'.$filename;
            $image->move($path, $filename);
            $category->image = $fullpath;
        }
        $category->meta_title       = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords    = $request->meta_keywords;
        $category->save();

        $lastRecord = ProductCategory::select('id')->orderBy('id', 'DESC')->first();
        if($request->has_parent == 1) {
            $parent_ids = $request->parent_id;
            $childCategory = new ChildCategories;
            if(!empty($parent_ids)) {
                foreach( $parent_ids as $key => $parent ) {
                    DB::insert('insert into child_category (cat_id, child_id) values (?, ?)', [ $parent, $lastRecord->id ]);
                }
            }
        }

        return redirect('/admin/categories')->with([
            'message'    => 'Successfully Added Category',
            'alert-type' => 'success',
        ]);
    }

    public function editCategory(Request $request, $id) {
        $vendors    = User::select('id','name')->where('role_id', '=', 2)->get();
        $data       = ProductCategory::with('child_categories')->where('id', '=',  $id)->first();

         $categories = ProductCategory::where('vendor_id', $data->vendor_id)
                                        ->where('has_parent', '=', 0)
                                        ->where('id','!=', $id)
                                        ->where('status', 1)
                                        ->get();
        // dd($categories);
        
        return Voyager::view('voyager::categories.edit', compact('data', 'categories', 'vendors')); 
    }

    public function updateCategory(Request $request){
        $category = ProductCategory::find($request->category_id); 
        
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id,
            'vendor_id' => 'required',
            'status'=> 'required',
            'image' => !file_exists(public_path()."/storage/".$category->image)?'required|mimes:jpeg,png,jpg|max:1024':'',
        ], [
            'slug.unique' => 'The slug is already in use by another category. Please choose a different slug.',
            'slug.required' => 'The slug field is required.',
        ]);
        
        $category->name      = $request->name;        
        $category->slug      = $request->slug;
        $category->vendor_id = $request->vendor_id;
        $category->status    = $request->status;
        $category->has_parent= $request->has_parent;
        /*if($request->has_parent == 1) {
            $category->parent_id = json_encode($request->parent_id);
        } else {
            $category->parent_id = null;
        }*/
        if($request->hasfile('image')){
            if($category->image != ''  && $category->image != null && file_exists(public_path()."/storage/".$category->image)){
                $path = public_path()."/storage/".$category->image;
                unlink($path);
            }

            $image = $request->file('image');
            $imagenm = preg_replace('/\./', '_', pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME));
            $path = public_path(). '/storage/categories/';
            $seed=str_pad(rand(0, pow(10, 5)-1), 5, '0', STR_PAD_LEFT);
            $filename = $imagenm.$seed. '.' . $image->getClientOriginalExtension();
            $fullpath = 'categories/'.$filename;
            $image->move($path, $filename);
            $category->image = $fullpath;
        }
        $category->meta_title       = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords    = $request->meta_keywords;
        $category->save();

        if($request->has_parent == 1) {
            $childCategory = new ChildCategories;
            if($request->parent_id) {
                foreach( $request->parent_id as $key => $parent ) {
                    $findRecord = ChildCategories::where('child_id', '=', $request->category_id)->where('cat_id', '=', $parent)->first();
                    if(empty($findRecord)) {
                        DB::insert('insert into child_category (cat_id, child_id) values (?, ?)', [ $parent, $request->category_id ]);
                    }
                    // Delete unwanted parent ids
                    $records = DB::table('child_category')->select('cat_id')->whereNotIn('cat_id', $request->parent_id)->where('child_id', $request->category_id)->get();
                    if(!empty($records)) {
                        $deleteParentCat = array();
                        foreach($records as $record ){
                            $deleteParentCat[] = $record->cat_id;
                        }
                        DB::table('child_category')->whereIn('cat_id', $deleteParentCat)->delete();
                    }
                }
            }
        }
        return redirect('/admin/categories')->with([
            'message'    => 'Successfully Updated Category',
            'alert-type' => 'success',
        ]);
    }
    
    public function show(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $isSoftDeleted = false;

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $query = $model->query();

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $query = $query->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query = $query->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$query, 'findOrFail'], $id);
            if ($dataTypeContent->deleted_at) {
                $isSoftDeleted = true;
            }
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'read');

        // Check permission
        $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'read', $isModelTranslatable);

        $parentCatId = ChildCategories::select('cat_id as parent_id')->where('child_id', '=', $id)->get();

        $parentID = [];
        $PID = '';
        if(!empty($parentCatId) && $parentCatId){
            foreach ($parentCatId as $key => $value) {
                array_push($parentID, $value->parent_id);
            }
            $PID = implode(',', $parentID);
        }
        $dataTypeContent['parent_id'] = $PID;

        $view = 'voyager::bread.read';

        if (view()->exists("voyager::$slug.read")) {
            $view = "voyager::$slug.read";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted'));
    }


    public function deleteCategory(Request $request, $id) {
        $productIds = $productMultiCatIds = array();

        $products = ProductCategories::select('*')->where('category_id',$id)->get();
        foreach($products as $product) {
            $productIds[] = $product->product_id;
        }

        /*if(!empty($productIds)) {
            Product::whereIn('id', $productIds)->delete();
            ProductAttributes::whereIn('product_id', $productIds)->delete();
            ProductMedias::whereIn('product_id', $productIds)->delete();
        }*/

        ProductCategories::where('category_id', $id)->delete();
        ChildCategories::where('cat_id', $id)->delete();
        ChildCategories::where('child_id', $id)->delete();
        ProductCategory::where('id', $id)->delete();
        
        return redirect('/admin/categories')->with([
            'message'    => 'Successfully Deleted Category',
            'alert-type' => 'success',
        ]);
    }


    // public function deleteCategory(Request $request, $id) {
    //     $productIds = array();
    //     $productMultiCatIds = array();
    //     $products = Product::select('*')->where('cat_id','LIKE', '%"'.$id.'"%')->get();
    //     foreach($products as $product) {
    //         $catID =  json_decode($product->cat_id);
    //         if(count($catID) <= 1) {
    //             $productIds[] = $product->id;
    //         } else {
    //             $productMultiCatIds[] = $product;
    //         }
    //     }
    //     if(!empty($productIds)) {
    //         Product::whereIn('id', $productIds)->delete();
    //         ProductAttributes::whereIn('product_id', $productIds)->delete();
    //         ProductMedias::whereIn('product_id', $productIds)->delete();
    //     }
    //     if(!empty($productMultiCatIds)){
    //         foreach($productMultiCatIds as $pdt) {
    //             $product = Product::find($pdt->id); 
    //             if(!empty($product)) {
    //                 $catIds = json_decode($pdt->cat_id);
    //                 if (!empty($catIds) && ($key = array_search($id, $catIds)) !== false) { 
    //                     unset($catIds[$key]); 
    //                 }
                   
    //                 $product->cat_id = json_encode(array_values($catIds));
    //                 $product->save();
    //             }
    //         }
    //     }
    //     ProductCategory::where('id', $id)->delete();
    //     ChildCategories::where('cat_id', $id)->delete();
    //     ChildCategories::where('child_id', $id)->delete();
    //     return redirect('/admin/categories')->with([
    //         'message'    => 'Successfully Deleted Category',
    //         'alert-type' => 'success',
    //     ]);
    // }
}