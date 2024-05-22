<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use DB;
use Helper;

class CustomerController extends  \TCG\Voyager\Http\Controllers\VoyagerBaseController
{     
    public function index(Request $request) {
        $customers = User::select('*')->where('role_id', '=', 3)->get();
        return Voyager::view('voyager::customers.browse',compact('customers'));
    }

    public function customerCreate(Request $request){
        $vendors    = User::select('id','name')->where('role_id', '=', 2)->get();
        $pass=Str::random(4).'Cc2@'.Str::random(3);    
        return Voyager::view('voyager::customers.create', compact('vendors','pass'));    
    }

    public function customerSave(Request $request){
        $validated = $request->validate([
            'name'     => 'required',
            'email'    => 'email|unique:users',
            'password'=>  ['required','min:8', 
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^\&*\)\(+=._-])[A-Za-z\d!@#\$%\^\&*\)\(+=._-]{8,}$/'],
            // 'mobile_number'   => 'required',
            // 'vendor_id'   => 'required',
        ], [
            'name.required' => 'Name is required', 
            'password.required' => 'The password must be at least 8 characters & it should contain at least one capital letter, one digit & one special character.',                     
        ]);

        $user = Auth::user();
        
        $token = Str::random(20);
        $userdata = new User;
        $userdata->name =  $request->name;        
        $userdata->role_id = 3;
        $userdata->email = $request->email;
        $userdata->mobile_number = $request->mobile_number;
        $userdata->password= bcrypt($request->password);  

        $userdata->verify_token= $token;
        $userdata->verify_token_date= Carbon::now();
        $userdata->vendor_id =  $request->vendor_id;     

        if ($request->hasFile('user_img')) {
            $image = $request->file('user_img');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('storage/users'), $imageName);
            $userdata->avatar = 'users/'.$imageName;
        }

        // dd($user);
        $userdata->save();
      
        $url=url('/').'/verify/'.$token;
        $details = [  
            'name'    => $request->name,              
            'email'    => $request->email,
            'pass'    => $request->password,
            'url'    => $url,
        ];
        $subject='Confirm your email';
        $to=$request->email;
        $toname=$request->name;
        $mailtype='verifyemail';

        // dd($mailtype);
        
        Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);
       
        return redirect()->route('customers')->with([
            'message'    => 'Customer added and invitation mail send Successfully',
            'alert-type' => 'success',
        ]);   
    }

    public function customerEdit(Request $request,$id) {
        $user = Auth::user();        
        $data = User::where("id", $id)->first(); 
        $vendors = User::select('id','name')->where('role_id', '=', 2)->get();      
        return view('voyager::customers.edit',compact('data','vendors'));
    }

    public function customerUpdate(Request $request) {
        $user = User::find($request->user_id);
        $validated = $request->validate([
            'name' => 'required',
            'email'=> 'required|email|unique:users,email,' . $user->id,
        ], [
        'email.unique' => 'The email address is already in use by another user.',
        'email.email' => 'Please provide a valid email address.',
        'email.required' => 'The email field is required.',
        ]);   
         
        $user->name =  $request->name;
        $user->email = $request->email;
        if($request->password){
            $user->password= bcrypt($request->password);
        }
        $user->mobile_number = $request->mobile_number;
        $user->vendor_id =  $request->vendor_id; 

        if ($request->hasFile('user_img')) {
            $image = $request->file('user_img');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('storage/users'), $imageName);
            $user->avatar = 'users/'.$imageName;
        }
        $user->save(); 

        return redirect('/admin/customers')->with([
            'message'    => 'Successfully Updated Customers',
            'alert-type' => 'success',
        ]);
    }

    public function customerDelete(Request $request) {
        Helper::deleteUserData($request->user_id);
        return redirect('/admin/customers')->with([
            'message'    => 'Successfully Deleted Customers',
            'alert-type' => 'success',
        ]);
    }
    

}