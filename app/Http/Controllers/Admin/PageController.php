<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pages;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Auth;
use DB;

class PageController extends  \TCG\Voyager\Http\Controllers\VoyagerBaseController
{     
    public function viewPages() {
        $pages = Pages::all();
        return Voyager::view('voyager::pages.browse',compact('pages'));
    }

    public function createPages(Request $request) {
        $vendors    = User::select('id','name')->where('role_id', '=', 2)->get();
        return Voyager::view('voyager::pages.add', compact('vendors')); 
    }

    public function storePages(Request $request) {
        // dd($request->all());

        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:pages',
            'vendor_id' => 'required',
            'status'=> 'required',
            // 'image' => 'required|mimes:jpeg,png,jpg|max:20000',
        ]);

        if($request->is_home == 1) {
            $vendorCount = Pages::where('vendor_id', $request->vendor_id)
                      ->where('is_home', 1)
                      ->count();
            if($vendorCount > 0) {
                return redirect()->back()
                        ->withInput()
                        ->withErrors(['is_home' => 'Home Page is Already Assigned to this vendor.']);
            }
        }

        $searchableOption = isset($request->searchable_option) ? $request->searchable_option : [];
        $topMenuOption = isset($request->top_menu_option) ? $request->top_menu_option : [];

        $pages = new Pages;
        $pages->author_id =  1;        
        $pages->title =  $request->name;        
        $pages->excerpt =  trim($request->excerpt);        
        $pages->body =  trim($request->body);        
        
        // if($request->hasfile('image')){
        //     $image = $request->file('image');
        //     $imagenm = preg_replace('/\./', '_', pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME));
        //     $path = public_path(). '/storage/pages/';
        //     $seed = str_pad(rand(0, pow(10, 5)-1), 5, '0', STR_PAD_LEFT);
        //     $filename = $imagenm.$seed. '.' . $image->getClientOriginalExtension();
        //     $fullpath = 'pages/'.$filename;
        //     $image->move($path, $filename);
        //     $pages->image = $fullpath;
        // }
        $pages->slug        = $request->slug;
        $pages->meta_title  = $request->meta_title;
        $pages->meta_description = $request->meta_description;
        $pages->meta_keywords    = $request->meta_keywords;
        $pages->status           = $request->status;
        $pages->is_home          = $request->is_home;
        $pages->vendor_id        = $request->vendor_id;
        $pages->searchable_option =  json_encode($searchableOption);
        $pages->top_menu_option =  json_encode($topMenuOption);
        $pages->save();

        return redirect('/admin/pages')->with([
            'message'    => 'Successfully Added Pages',
            'alert-type' => 'success',
        ]);
    }

    public function editPages(Request $request, $id) {
        $data = Pages::find($id);
        $vendors    = User::select('id','name')->where('role_id', '=', 2)->get();
        return Voyager::view('voyager::pages.edit', compact('data','vendors')); 
    }

    public function updatePages(Request $request) {
        $pages = Pages::find($request->page_id);

        if($request->is_home == 1) {
            $vendorCount = Pages::where('vendor_id', $request->vendor_id)
                      ->where('is_home', 1)
                      ->where('id', '!=', $request->page_id)
                      ->count();
            if($vendorCount > 0) {
                return redirect('/admin/pages/'.$request->page_id.'/edit')->with([
                    'message'    => 'Home Page is Already Assigned to this vendor.',
                    'alert-type' => 'error',
                ]);
            }
        }
        
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:pages,slug,' . $pages->id,
            'vendor_id' => 'required',
            'status'=> 'required',
            // 'image' => !file_exists(public_path()."/storage/".$pages->image)?'required|mimes:jpeg,png,jpg|max:20000':'',
        ], [
            'slug.unique' => 'The slug is already in use by another page. Please choose a different slug.',
            'slug.required' => 'The slug field is required.',
        ]);

        $searchableOption = isset($request->searchable_option) ? $request->searchable_option : [];
        $topMenuOption = isset($request->top_menu_option) ? $request->top_menu_option : [];

        $pages->author_id =  1;        
        $pages->title =  $request->name;        
        $pages->excerpt =  trim($request->excerpt);        
        $pages->body =  trim($request->body); 
       
        // if($request->hasfile('image')){
        //     if($pages->image != ''  && $pages->image != null && file_exists(public_path()."/storage/".$pages->image)){
        //         $path = public_path()."/storage/".$pages->image;
        //         unlink($path);
        //     }

        //     $image = $request->file('image');
        //     $imagenm = preg_replace('/\./', '_', pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME));
        //     $path = public_path(). '/storage/pages/';
        //     $seed=str_pad(rand(0, pow(10, 5)-1), 5, '0', STR_PAD_LEFT);
        //     $filename = $imagenm.$seed. '.' . $image->getClientOriginalExtension();
        //     $fullpath = 'pages/'.$filename;
        //     $image->move($path, $filename);
        //     $pages->image = $fullpath;
        // }
        $pages->slug = $request->slug;
        $pages->meta_title = $request->meta_title;
        $pages->meta_description = $request->meta_description;
        $pages->meta_keywords    = $request->meta_keywords;
        $pages->status = $request->status;
        $pages->is_home       = $request->is_home;
        $pages->vendor_id = $request->vendor_id;
        $pages->searchable_option =  json_encode($searchableOption);
        $pages->top_menu_option =  json_encode($topMenuOption);
        $pages->save();


        return redirect('/admin/pages')->with([
            'message'    => 'Successfully Updated Page',
            'alert-type' => 'success',
        ]); 
    }

    public function deletePages(Request $request, $id) {
        Pages::where('id', $id)->delete();
        return redirect('/admin/pages')->with([
            'message'    => 'Successfully Deleted Page',
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


        $view = 'voyager::bread.read';

        if (view()->exists("voyager::$slug.read")) {
            $view = "voyager::$slug.read";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted'));
    }

}