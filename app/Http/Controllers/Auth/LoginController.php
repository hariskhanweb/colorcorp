<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\InstallationCharges;
use App\Models\User;
use App\Mail\userVerifyMail;
use App\Models\VendorShopSettings;
use Mail;
use View;
use Helper;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(Request $request){
        $this->validateLogin($request);
        // dd($request);
        if(Auth::attempt($this->credentials($request))){
           $user = Auth::user();
           if($user->role_id == 1){
                Auth::logout();
                auth()->logout(); 
                Session::flash('errortype', '1'); 
                return redirect()->route('login')->with('error', 'You are not authorized person to login via this page');
            // }elseif(($user->role_id == 2 || $user->role_id == 3) && $user->email_verified_at !== NULL) {
            }elseif( $user->role_id == 3 && $user->email_verified_at !== NULL) {
                Auth::login(Auth::user(), true);  
                if($user->reset_first== NULL){
                    return redirect()->route('resetpassword')->with('success', 'Login successfully, please reset your password');
                }elseif($user->role_id==3){
                    $data = InstallationCharges::select('id','user_id')->where('user_id', $user->id)->where('status', 1)->orderBy('id', 'DESC')->first();
                    $shop_url_slug = Helper::getShopslug($user->vendor_id);
                    if(!empty($data)){
                        if($data->user_id == $user->id){
                            return redirect()->route('invoiceCheckout', ['vendor_name' => $shop_url_slug, 'id' => base64_encode($data->id)]);
                        }else{
                            return redirect()->route('shop',['vendor_name' => $shop_url_slug]);
                        }                        
                    }else{
                        return redirect()->route('shop',['vendor_name' => $shop_url_slug]);
                    }
                }else{
                    return redirect()->route('login')->with('error', 'You are not authorized person to login via this page');
                }
            }elseif($user->role_id==2){   
                    $vendorInfo=VendorShopSettings::where("vendor_id", $user->id)->first(); 
                    if($vendorInfo){
                        $shop_url_slug=Helper::getShopslug($user->id);
                        return redirect()->route('vendor.dashboard',['vendor_name' => $shop_url_slug])->with('success', 'Login successful');
                    }else{
                        return redirect()->route('vendor.shopSetting')->with('success', 'Login successful');
                    }                    
                }
                elseif($user->role_id!=2 && $user->role_id!=3) {
                Auth::login(Auth::user(), true);
                $success["message"] = "Login successful";
                return redirect()->route('vendor.shopSetting')->with('success', 'Login successful');
            }else{
                Auth::logout();
                auth()->logout(); 
                Session::flash('errortype', '1'); 
                return redirect()->route('login')->with('error', 'Please click on email verify link from your mail');
            }
        }else{           
            return redirect()->route('login')->with('error', 'Invalid username or password, please try again later.');
        }       
    }

    public function verifyUser(Request $request,$id)
    {
        
        $verified_link = User::where('verify_token',$id)->first();
        

        if(!empty($verified_link)){

            $toDate = Carbon::parse($verified_link->verify_token_date);
            $fromDate = Carbon::parse(Carbon::now());
      
            $days = $toDate->diffInDays($fromDate);

            if($verified_link->email_verified_at !== NULL){
               return redirect()->route('login')->with('error', 'You have already verified your email address.'); 

            }elseif($days>0){
               return redirect()->route('login')->with('reset', 'Your verification link has expired, please reset link'); 
            }else{
                $user =  User::find($verified_link->id);            
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->verify_token = null;
                //$user->verify_token_date = null;
                $user->save();
                return redirect()->route('login')->with('success', 'You have successfully verified your email!, Please login.');
            }
            
        }else{
            return redirect()->route('login')->with('error', 'Invalid verification link');
        }      
        
    }

    public function resetLink(Request $request)
    {
        $email_chk = User::where('email',$request->email)->where('role_id',2)->first();

        if(!empty($email_chk)){
            

            if($email_chk->email_verified_at !== NULL){
                return redirect()->route('login')->with('error', 'You have already verified your email address.'); 

            }else{
               $token = Str::random(20);
               $password = Str::random(10);

               $user =  User::find($email_chk->id);                              
               $user->verify_token= $token;
               $user->verify_token_date= Carbon::now();
               $user->password= bcrypt($password);             
               $user->save();

                 $url=url('/').'/verify/'.$token;
                $details = [  
                    'name'    => $email_chk->name,              
                    'email'    => $email_chk->email,
                    'pass'    => $password,
                    'url'    => $url,
                ];

                //Mail::to($email_chk->email)->send(new userVerifyMail($details));
                $subject='Confirm your email';
                $to=$email_chk->email;
                $toname=$email_chk->name;
                $mailtype='verifyemail';
                
                Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);


                return redirect()->route('login')->with('success', 'Verification link sent, please check your email and click on <b>verify link<b> !');
            }
            
        }else{
            return redirect()->route('login')->with('error', 'What if the email address you entered does not match your account? ');
        }
    }

    public function logout(Request $request)
    {

        Session::flush();
        Auth::logout();
        auth()->logout();

        return redirect('/');
    }

}
