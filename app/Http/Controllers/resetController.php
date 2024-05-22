<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\userResetPassMail;
use Helper;
use Mail;

class ResetController extends Controller
{
    //
    public function resetPassword()
    {
  
        $data=1;
        return view('auth.resetpass',compact('data'));
    }

    public function resetPasswordAction(Request $request)
    {

        //print_r($request->all());exit;
        $user = Auth::user();

        $request->validate([
        'password' => ['required','min:8', 
        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^\&*\)\(+=._-])[A-Za-z\d!@#\$%\^\&*\)\(+=._-]{8,}$/','confirmed'],
          'password_confirmation' => 'required'
        ],[ 
            'password.confirmed' => 'Password and confirmed password should be match',
            'password.regex' => 'The password must be at least 8 characters & it should contain at least one capital letter, one digit & one special character.',
        ]);


            $user =  User::find($user->id);
            $user->password= bcrypt($request->password);
            $user->reset_first= 1;
            $user->save();

            $details = [  
                    'name'    => $user->name,
                ];

            $subject='Reset Password';
                $to=$user->email;
                $toname=$user->name;
                $mailtype='reset-pass';
                
            Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);

            Auth::logout();
            auth()->logout();             
            return redirect('/login')->with('success', 'Your password has been reset successfully, please login with new password'); 
        

               
    }
    public function resetPasswordShik(Request $request)
    {
        $user = Auth::user();
        $user =  User::find($user->id);
        $user->reset_first= 1;
        $user->save();
        if($user->role_id==2){
            return redirect()->route('vendor.shopSetting');
        }else{
            return redirect('/');
        }
    }
    
}
