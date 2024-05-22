<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\VendorShopSettings;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductMedias;
use App\Models\ProductCategory;
use App\Models\ProductCategories;
use Illuminate\Support\Str;
use App\Models\User;
use App\Helpers\Helpers;
use Redirect;
use Helper;
use Mail;
use DB;

class ProductController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    // public function viewProducts() {
    //     $product = Product::all();
    //     return Voyager::view('voyager::products.browse',compact('product'));
    // }

    public function createProducts() {
        $vendors    = User::select('id','name')->where('role_id', '=', 2)->get();
        return Voyager::view('voyager::products.add',compact('vendors'));
    }
    public function getCategories(Request $request) {
	    $vendorId = $request->input('vendor_id');
	    $categories = ProductCategory::where('vendor_id', $vendorId)->where('status', 1)->get();

	    $options = '';
		if(count($categories)>0) {
			foreach($categories as $category) {
                if($category->has_parent == 0) {
                	$options .='<optgroup label="'.$category->name.'">';
                	$subCat = Helpers::getSubCategories($category->id);
                	if(count($subCat)>0) {
                      	foreach($subCat as $subcatlist) {
                    	$options .='<option value="'.$category->id."-".$subcatlist->id.'">'.$subcatlist->name.'</option>';
                      	}
                	}
                	$options .='</optgroup>';
                }
			}
		}
		return response()->json(['options' => $options]);
	}

	public function storeProducts(Request $request) {
		// dd($request->all());

		$validated = $request->validate([
            'prodname'     => 'required|max:255',
            'prodsku'       => 'required|unique:products,sku',
            'prodshortdesc' => 'required|max:255',
            'prodlongdesc'  => 'required',
            'prodprice'     => 'required|numeric',
            'prodcategory'  => 'required',
            'prodfeatureimg'  => 'required|max:1024',
            'prodgalleryimg'  => 'max:1024',
        ], [
            'prodname.required' => 'Name is required.',
            'prodsku.required' => 'SKU is required.',
            'prodsku.unique' => 'SKU is already exists.',
            'prodshortdesc.max' => 'The short description cannot exceed 255 characters.',
            'prodshortdesc.required' => 'Short Description is required.',
            'prodlongdesc.required' => 'Long Description is required.',
            'prodprice.required' => 'Price is required.',
            'prodcategory.required' => 'Category is required.',
            'prodfeatureimg.required' => 'Featured Image is required.',
        ]); 

        $slug = $request['prodslug'];
        $chkslug = Product::where('slug', '=', $slug)->count();
        if($chkslug>0){
            $rnslen = Self::randomNumber(3);
            $newslug = $slug.'-'.$rnslen;
        } else{
            $newslug = $slug;
        }
        

        $data = new Product();
        $data->name = trim(ucwords($request['prodname']));
        $data->sku = strtoupper(trim($request['prodsku']));
        $data->slug = $newslug;
        // $data->slug = $request['prodslug'];
        $data->short_description = trim($request['prodshortdesc']);
        $data->long_description = trim($request['prodlongdesc']);
        $data->price = trim($request['prodprice']);
        $data->vendor_id = $request['vendor_id'];
        $data->has_variation = $request['prodhasvariate'];
        $data->status = $request['prodstatus'];
        $data->meta_title = trim($request['prodmetatitle']);
        $data->meta_description = trim($request['prodmetadescript']);
        $data->meta_keywords = trim($request['prodmetakeyword']);
        if($data->save()) {
            $prodid = $data->id;
            // Attribute Saving Code
            if($request['prodhasvariate']=="1"){
                $prodattoptions = $request['prodattroptid'];
                if(!empty($prodattoptions)){
                    foreach ($prodattoptions as $prodattroptvalue) {
                        $parentid = $request['prodattrparentid'.$prodattroptvalue];
                        $varyprice = ($request['prodoptvaryprice'.$prodattroptvalue] != "0")? $request['prodoptvaryprice'.$prodattroptvalue]: "0.00";

                        $dataattribute = new ProductAttributes();
                        $dataattribute->product_id = $prodid;
                        $dataattribute->attribute_id = $parentid;
                        $dataattribute->option_id = $prodattroptvalue;
                        $dataattribute->variable_price = $varyprice;
                        $dataattribute->save();
                    }
                }
            }
            // Featured Image Saving Code
            if( $request->hasFile('prodfeatureimg')) {
                $image = $request->file('prodfeatureimg');
                $imagenm = preg_replace('/\./', '_', pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME));
                $path = public_path(). '/storage/products/';
                $seed=str_pad(rand(0, pow(10, 5)-1), 5, '0', STR_PAD_LEFT);
                $filename = $imagenm.$seed. '.' . $image->getClientOriginalExtension();
                $fullpath = 'products/'.$filename;
                $savedpath = $path.$filename;
                $image->move($path, $filename);

                $datamedia = new ProductMedias();
                $datamedia->product_id = $prodid;
                $datamedia->url = $fullpath;
                $datamedia->path = $savedpath;
                $datamedia->is_featured = '1';
                $datamedia->save();
            }
            // Gallery Images Saving Code
            $images = [];
            if( $request->hasFile('prodgalleryimg')) {
                $images = $request->file('prodgalleryimg');
                foreach($images as $img){
                    $imagenm = preg_replace('/\./', '_', pathinfo($img->getClientOriginalName(),PATHINFO_FILENAME));
                    $path = public_path(). '/storage/products/';
                    $seed=str_pad(rand(0, pow(10, 5)-1), 5, '0', STR_PAD_LEFT);
                    $filename = $imagenm.$seed. '.' . $img->getClientOriginalExtension();
                    $fullpath = 'products/'.$filename;
                    $savedpath = $path.$filename;
                    $img->move($path, $filename);

                    $datamedia2 = new ProductMedias();
                    $datamedia2->product_id = $prodid;
                    $datamedia2->url = $fullpath;
                    $datamedia2->path = $savedpath;
                    $datamedia2->is_featured = '0';
                    $datamedia2->save();
                }
            }
            // Product Category saving code
            $categories = $request['prodcategory'];
            foreach($categories as $category) {
                $product_cat = explode("-", $category);
                if(isset($product_cat[1])) {
                    $main_cat = $product_cat[1];
                } else{
                    $main_cat = null;
                }
                $parent_cat = $product_cat[0];
                DB::insert('insert into product_categories (product_id, parent_category_id, category_id) values (?, ?, ?)', [ $prodid, $parent_cat, $main_cat ]);                
            }
        }

		return redirect('/admin/products')->with([
            'message'    => 'Successfully Added Product',
            'alert-type' => 'success',
        ]);
	}
	
	public function editProducts(Request $request, $id) {
        $prodrecord = Product::find($id);
        if($prodrecord) {
            $prodfeatured = ProductMedias::select('*')->where('product_id','=', $id)->where('is_featured','=', 1)->first();
            // dd($prodfeatured->url);
            $prodmedia = ProductMedias::select('*')->where('product_id','=', $id)->where('is_featured','=', 0)->get();
            $prodattributes = ProductAttributes::select('*')->where('product_id','=', $id)->get();
            $vendors    = User::select('id','name')->where('role_id', '=', 2)->get();
            return Voyager::view('voyager::products.edit', compact('prodrecord','prodfeatured','prodmedia','prodattributes','vendors'));
        }      
    }

    
    public function updateProducts(Request $request){
        $prodid = $request['prodid'];
        $slug = $request['prodslug'];

        $checkProSlug = Product::where('slug', '=', $slug)->first();
        if($checkProSlug != null) {
            if($prodid != $checkProSlug->id) {
                return redirect('/admin/products')->with([
                    'message'    => 'Slug is not matched,Try again.',
                    'alert-type' => 'error',
                ]);
            }
        }
        
        $prodFechImg = ProductMedias::select('url')->where('product_id','=', $prodid)->where('is_featured','=', 1)->first();

        $validated = $request->validate([
            'prodname'     => 'required|max:255',
            'prodshortdesc' => 'required|max:255',
            'prodlongdesc'  => 'required',
            'prodprice'     => 'required|numeric',
            'prodcategory'  => 'required',
            'prodfeatureimg'  => !file_exists(public_path()."/storage/".$prodFechImg->url)?'required|max:1024':'',
            'prodgalleryimg'  => 'max:1024',
        ], [
            'prodname.required' => 'Name is required.',
            'prodsku.required' => 'SKU is required.',
            'prodsku.unique' => 'SKU is already exists.',
            'prodshortdesc.max' => 'The short description cannot exceed 255 characters.',
            'prodshortdesc.required' => 'Short Description is required.',
            'prodlongdesc.required' => 'Long Description is required.',
            'prodprice.required' => 'Price is required.',
            'prodcategory.required' => 'Category is required.',
        ]);
         
        $prodnm = trim(ucwords($request['prodname']));
        $categories = $request['prodcategory'];

        $data = Product::findOrFail($prodid);
        $data->name = trim(ucwords($request['prodname']));
        $data->slug = $request['prodslug'];
        $data->short_description = trim($request['prodshortdesc']);
        $data->long_description = trim($request['prodlongdesc']);
        $data->price = trim($request['prodprice']);
        $data->vendor_id = $request['vendor_id'];
        $data->has_variation = $request['prodhasvariate'];
        $data->status = $request['prodstatus'];
        $data->meta_title = trim($request['prodmetatitle']);
        $data->meta_description = trim($request['prodmetadescript']);
        $data->meta_keywords = trim($request['prodmetakeyword']);
        $data->save();
        if($data){
            $prodid = $data->id;
            // Attribute Saving Code
            if($request['prodhasvariate']=="1"){
                $prodattoptions = $request['prodattroptid'];
                if(!empty($prodattoptions)){
                    foreach ($prodattoptions as $prodattroptvalue) {
                        $parentid = $request['prodattrparentid'.$prodattroptvalue];
                        $varyprice = ($request['prodoptvaryprice'.$prodattroptvalue] != "0")? $request['prodoptvaryprice'.$prodattroptvalue]: "0.00";

                        $chkattavail = ProductAttributes::select('id')->where('option_id','=',$prodattroptvalue)->where('attribute_id','=',$parentid)->where('product_id','=',$prodid)->get();
                        if(count($chkattavail)>0){
                            foreach($chkattavail as $chkvallst){ $attbid = $chkvallst->id; }
                            $dataattribute = ProductAttributes::findOrFail($attbid);
                            $dataattribute->product_id = $prodid;
                            $dataattribute->attribute_id = $parentid;
                            $dataattribute->option_id = $prodattroptvalue;
                            $dataattribute->variable_price = $varyprice;
                            $dataattribute->save();

                            $prodattrow[] = $dataattribute->id;
                        } else {
                            $dataattribute = new ProductAttributes();
                            $dataattribute->product_id = $prodid;
                            $dataattribute->attribute_id = $parentid;
                            $dataattribute->option_id = $prodattroptvalue;
                            $dataattribute->variable_price = $varyprice;
                            $dataattribute->save();

                            $prodattrow[] = $dataattribute->id;
                        }
                    }
                    // Checked unchecked options and removed from table
                    $recdelremains = ProductAttributes::where('product_id','=',$prodid)->whereNotIn('id',$prodattrow)->delete();
                }
            }
            // Featured Image Saving Code
            if( $request->hasFile('prodfeatureimg')) {
                $productfeatrec = ProductMedias::select('id')->where('product_id','=', $prodid)->where('is_featured','=', 1)->get();
                if(count($productfeatrec)>0){
                    foreach($productfeatrec as $prodfeatval) { $featid = $prodfeatval->id; }
                    $image = $request->file('prodfeatureimg');
                    $imagenm = preg_replace('/\./', '_', pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME));
                    $path = public_path(). '/storage/products/';
                    $seed=str_pad(rand(0, pow(10, 5)-1), 5, '0', STR_PAD_LEFT);
                    $filename = $imagenm.$seed. '.' . $image->getClientOriginalExtension();
                    $fullpath = 'products/'.$filename;
                    $savedpath = $path.$filename;
                    $image->move($path, $filename);

                    $datamedia = ProductMedias::findOrFail($featid);

                    if($datamedia->url != ''  && $datamedia->url != null && file_exists(public_path()."/storage/".$datamedia->url)){
                        $_path = public_path()."/storage/".$datamedia->url;
                        unlink($_path);
                    }

                    $datamedia->product_id = $prodid;
                    $datamedia->url = $fullpath;
                    $datamedia->path = $savedpath;
                    $datamedia->is_featured = '1';
                    $datamedia->save();
                } else {
                    $image = $request->file('prodfeatureimg');
                    $imagenm = preg_replace('/\./', '_', pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME));
                    $path = public_path(). '/storage/products/';
                    $seed=str_pad(rand(0, pow(10, 5)-1), 5, '0', STR_PAD_LEFT);
                    $filename = $imagenm.$seed. '.' . $image->getClientOriginalExtension();
                    $fullpath = 'products/'.$filename;
                    $savedpath = $path.$filename;
                    $image->move($path, $filename);

                    $datamedia = new ProductMedias();
                    $datamedia->product_id = $prodid;
                    $datamedia->url = $fullpath;
                    $datamedia->path = $savedpath;
                    $datamedia->is_featured = '1';
                    $datamedia->save();
                }
            }
            // Gallery Images Saving Code
            $images = [];
            if( $request->hasFile('prodgalleryimg')) {
                $images = $request->file('prodgalleryimg');
                foreach($images as $img){
                    $imagenm = preg_replace('/\./', '_', pathinfo($img->getClientOriginalName(),PATHINFO_FILENAME));
                    $path = public_path(). '/storage/products/';
                    $seed=str_pad(rand(0, pow(10, 5)-1), 5, '0', STR_PAD_LEFT);
                    $filename = $imagenm.$seed. '.' . $img->getClientOriginalExtension();
                    $fullpath = 'products/'.$filename;
                    $savedpath = $path.$filename;
                    $img->move($path, $filename);

                    $datamedia2 = new ProductMedias();
                    $datamedia2->product_id = $prodid;
                    $datamedia2->url = $fullpath;
                    $datamedia2->path = $savedpath;
                    $datamedia2->is_featured = '0';
                    $datamedia2->save();
                }
            }

            // Product Category saving code

            // Delete all old record
            DB::table('product_categories')->where('product_id', $prodid)->delete(); 
            foreach($categories as $category){
                $product_cat = explode("-", $category);
                if(isset($product_cat[1])) {
                    $main_cat = $product_cat[1];
                } else{
                    $main_cat = null;
                }
                $parent_cat = $product_cat[0];
                DB::insert('insert into product_categories (product_id, parent_category_id, category_id) values (?, ?, ?)', [ $prodid, $parent_cat, $main_cat ]);
            }
        }

        return redirect('/admin/products')->with([
            'message'    => 'Successfully Updated Product',
            'alert-type' => 'success',
        ]);
    }

	public function deleteProducts(Request $request, $id){
        $record = Product::where('id', '=', $id)->count();
        if($record>0){
            $recmedias = ProductMedias::where('product_id','=', $id)->get();
            if(count($recmedias)>0){
                foreach($recmedias as $mediafilelst) {
                    $imgpath = $mediafilelst->path;
                    unlink($imgpath);
                }
            }
            Product::where('id', $id)->delete();
            ProductMedias::where('product_id', $id)->delete();
            ProductAttributes::where('product_id', $id)->delete();
            ProductCategories::where('product_id', $id)->delete();
        }

        return redirect('/admin/products')->with([
            'message'    => 'Successfully Deleted Product',
            'alert-type' => 'success',
        ]); 
    }

    public function viewProducts(Request $request, $id) {

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

        $prodrecord = Product::find($id);
        $prodfeatured = ProductMedias::select('*')->where('product_id','=', $id)->where('is_featured','=', 1)->first();
        // dd($prodfeatured->url);
        $prodmedia = ProductMedias::select('*')->where('product_id','=', $id)->where('is_featured','=', 0)->get();
        $prodattributes = ProductAttributes::select('*')->where('product_id','=', $id)->get();
        $vendors    = User::select('id','name')->where('role_id', '=', 2)->get();

        // dd($prodrecord);
        

        $view = 'voyager::products.read';

        if (view()->exists("voyager::$slug.read")) {
            $view = "voyager::$slug.read";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted','prodrecord','prodfeatured','prodmedia','prodattributes','vendors'));
    }

    public function removeProdGalaryImg(Request $request){
        $prodimgid = $request->input('id');
        $prodrecord = ProductMedias::where('id', '=', $prodimgid)->get();
        if(count($prodrecord)>0){
            foreach($prodrecord as $prodlist) {
                $path = $prodlist->path;
                $delrec = ProductMedias::where('id', '=', $prodimgid)->delete();
                unlink($path);
            }
            return response()->json(array('data'=> '1'), 200);
        }
        return response()->json(array('data'=> '0'), 200);
    }

	public function randomNumber($length){
        $digits = '';
        $numbers = range(0,9);
        shuffle($numbers);
        for($i = 0;$i < $length;$i++){
            $digits .= $numbers[$i];
        }           
        return $digits;
    }

}