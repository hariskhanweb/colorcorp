<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\BussinessCategories;
use App\Models\CartItems;
use App\Models\CustomerAddresses;
use App\Models\Orders;
use App\Models\OrderAddresses;
use App\Models\OrderComments;
use App\Models\OrderItems;
use App\Models\OrderItemAttributes;
use App\Models\VendorShopSettings;
use App\Mail\userVerifyMail;
use Mail;
use DB;
use File;
use Storage;
use Redirect;
use Helper;

class UserController extends  \TCG\Voyager\Http\Controllers\VoyagerBaseController
{     
    public function bulk_deactive(Request $request)
    {
        $ids = [];
        $ids = explode(',', $request->ids);
        foreach ($ids as $id) {
            $deactive = User::find($id);
            $deactive->status = 0;
            $deactive->save();
        }
        return redirect(route('voyager.general-users.index'));
    }

    public function adminUser(Request $request)
    {
        $adminuser=[];
        $adminuser = User::select('*')->where('role_id', '!=', 3)->whereHas('userRoll')->with('bussinessCategories')->get();        
        // dd($adminuser);
        return Voyager::view('voyager::admin-user.browse', compact('adminuser'));   
    }

    public function createadminUser(Request $request){
        $userrole=[];
        $userrole = Role::select('id','display_name')->get()->toArray();
        $buscat = BussinessCategories::select('id','name')->get()->toArray();
        // dd($userrole);
        //$pass='C'.Str::random(9);
        $pass=Helper::passwordGenerator();
        return Voyager::view('voyager::admin-user.create',compact('userrole','buscat','pass'));   
    }

    public function saveadminUser(Request $request){

        $validated = $request->validate([
            'name' => 'required',
            'user_role' => 'required',
            'email'=> 'email|unique:users',
           // 'password'=> ['required','min:8'],
            'password'=>  ['required','min:8', 
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^\&*\)\(+=._-])[A-Za-z\d!@#\$%\^\&*\)\(+=._-]{8,}$/'],
            'user_role'=> 'required',
            //'status'=> 'required',
        ]);
        
        // user registration
        $user = new User;
        $user->name =  $request->name;        
        $user->role_id = $request->user_role;
        $user->email = $request->email;
        $user->business_category= $request->bus_cat;
        //$user->status= $request->status;
        $user->password= bcrypt($request->password);
        $user->temp_pass = $request->password;

        // $token = Str::random(20);
        // if($request->user_role==2){
        //     $user->verify_token= $token;
        //     $user->verify_token_date= Carbon::now();
        // }
        
        // User address
        // if($request->hasfile('user_img')){
        //     $img_path = 'public/users/';
        //     $file = $request->file('user_img');            
        //     $destinationPath = 'users';
        //     $filename = time().'-'.$file->getClientOriginalName(); 
        //     $file->storeAs($img_path, $filename);    
        //     $user->avatar = 'users/'.$filename;
        // }

        if ($request->hasFile('user_img')) {
            $image = $request->file('user_img');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('storage/users'), $imageName);
            $user->avatar = 'users/'.$imageName;
        }

        if ($request->hasFile('user_logo')) {
            $logo = $request->file('user_logo');
            $logoName = time().'.'.$logo->getClientOriginalExtension();
            $logo->move(public_path('storage/users'), $logoName);
            $user->user_logo = 'users/'.$logoName;
        }
        $user->save();

        // if($request->user_role == 2) {
        //     $url=url('/').'/verify/'.$token;
        //     $details = [  
        //         'name'    => $request->name,              
        //         'email'    => $request->email,
        //         'pass'    => $request->password,
        //         'url'    => $url,
        //     ];
            
        //     $subject='Confirm your email';
        //     $to=$request->email;
        //     $toname=$request->name;
        //     $mailtype='verifyemail';
            
        //     Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);
        // }

        if( $user->role_id == 2 ) {
            $msg = 'Your vendor has been successfully added, Please log in with the vendor and Setup vendor shop';
        } else {
            $msg = 'Successfully Added User';
        }

        $adminuser=[];
        $adminuser = User::select('*')->whereHas('userRoll')->with('bussinessCategories')->get();        
        return redirect()->route('admin-user')->with([
            'adminuser'  => $adminuser,
            'message'    => $msg,
            'alert-type' => 'success',
        ]);
    }

    public function editadminUser($id){ 
        
        $userInfo =[];
        $userrole=[];
        $userAddress =[];

        $userInfo = User::select('*')->where('id',$id)->first()->toArray();       
        $userrole = Role::select('*')->get()->toArray();
         $buscat = BussinessCategories::select('*')->get()->toArray();
        return Voyager::view('voyager::admin-user.edit',compact('userrole','userInfo','userAddress','buscat'));
    }

    public function updateadminUser(Request $request){ 

        // dd($request->all());
        
        $user = User::find($request->user_id);
        $validated = $request->validate([
            'name' => 'required',
            //'user_role' => 'required',
            'email'=> 'required|email|unique:users,email,' . $user->id,
           // 'user_role'=> 'required',
           // 'status'=> 'required',
        ], [
        'email.unique' => 'The email address is already in use by another user.',
        'email.email' => 'Please provide a valid email address.',
        'email.required' => 'The email field is required.',
        ]);   
         
        $user->name =  $request->name;        
        //$user->role_id = $request->user_role;
        $user->email = $request->email;
        $user->business_category= $request->bus_cat;
        //$user->status= $request->status;
        if($request->password){
            $user->password= bcrypt($request->password);
        }

        // if($request->hasfile('user_img')){
        //     $img_path = 'public/users/';
        //     $file = $request->file('user_img');            
        //     $destinationPath = 'users';
        //     $filename = time().'-'.$file->getClientOriginalName(); 
        //     $file->storeAs($img_path, $filename);    
        //    $user->avatar = 'users/'.$filename;
        // }

        if ($request->hasFile('user_img')) {
            $image = $request->file('user_img');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('storage/users'), $imageName);
            $user->avatar = 'users/'.$imageName;
        }

        if ($request->hasFile('user_logo')) {
            $logo = $request->file('user_logo');
            $logoName = time().'.'.$logo->getClientOriginalExtension();
            $logo->move(public_path('storage/users'), $logoName);
            $user->user_logo = 'users/'.$logoName;
        }

        if($request->locale){
            $user->locale = $request->locale;
        }

        $user->save(); 

        return redirect()->route('admin-user')->with('message', 'Successfully Updated User')->with('alert-type', 'success');
    }


   public function deleteAdminUser(Request $request){      
        $user_data=User::where('vendor_id', $request->user_id)->get();
        if($user_data->isNotEmpty()){ 
            foreach($user_data as $value){        
                Helper::deleteUserData($value->id);
            }
            
        }
        $user_data=User::where('id', $request->user_id)->delete();
        $vendor_shop_settings=VendorShopSettings::where('vendor_id', $request->user_id)->get();    

        return back()->with('message', 'Successfully Deleted User')->with('alert-type', 'success');
    }

    public function deleteAdminUsers(Request $request, $id)
    {
        $slug = 'admin-user';

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Init array of IDs
        $ids = [];
        if (empty($id)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }
        foreach ($ids as $id) {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

            // Check permission
            $this->authorize('delete', $data);

            $model = app($dataType->model_name);
            if (!($model && in_array(SoftDeletes::class, class_uses_recursive($model)))) {
                $this->cleanup($dataType, $data);
            }
        }

        $displayName = count($ids) > 1 ? $dataType->getTranslatedAttribute('display_name_plural') : $dataType->getTranslatedAttribute('display_name_singular');

        $res = $data->destroy($ids);
        $data = $res
            ? [
                'message'    => __('voyager::generic.successfully_deleted')." {$displayName}",
                'alert-type' => 'success',
            ]
            : [
                'message'    => __('voyager::generic.error_deleting')." {$displayName}",
                'alert-type' => 'error',
            ];

        if ($res) {
            event(new BreadDataDeleted($dataType, $data));
        }

        return redirect()->route('admin-user')->with($data);
    }   

    
}