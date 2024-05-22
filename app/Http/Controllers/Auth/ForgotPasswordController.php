<?php 
  
namespace App\Http\Controllers\Auth; 
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use DB; 
use Carbon\Carbon; 
use App\Models\User; 
use Mail; 
use Hash;
use Illuminate\Support\Str;
use Helper;

  
class ForgotPasswordController extends Controller
{
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function showForgetPasswordForm()
      {
         return view('auth.forgetPassword');
      }
  
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitForgetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
          ]);

          $email_chk = User::where('email',$request->email)->first();
          if($email_chk->email_verified_at == NULL && $email_chk->email_verified_at == NULL){
            
            return redirect()->route('login')->with('error', 'Please click on email verify link from your mail');
          }
  
          $token = Str::random(64);
  
          DB::table('password_resets')->insert([
              'email' => $request->email, 
              'token' => $token, 
              'created_at' => Carbon::now()
            ]);
          
                $details = [
                    'token'    => $token,
                ];

        //Mail::to($email_chk->email)->send(new userVerifyMail($details));
        $subject='Reset Password';
        $to=$request->email;
        $toname='reset';
        $mailtype='forgetemail';
        
        Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);
  
          return back()->with('message', 'We have e-mailed your password reset link!');
      }
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function showResetPasswordForm($token) { 
         return view('auth.forgetPasswordLink', ['token' => $token]);
      }
  
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitResetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
              'password' => ['required','min:8', 
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^\&*\)\(+=._-])[A-Za-z\d!@#\$%\^\&*\)\(+=._-]{8,}$/','confirmed'],
              'password_confirmation' => 'required'
            ],[ 
                'password.confirmed' => 'Password and confirmed password should be match',
                'password.regex' => 'The password must be at least 8 characters & it should contain at least one capital letter, one digit & one special character.',
            ]);
  
          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }
  
          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);
 
          DB::table('password_resets')->where(['email'=> $request->email])->delete();
  
          return redirect()->route('login')->with('message', 'Your password has been changed!');
      }
}