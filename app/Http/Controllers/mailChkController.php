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
use App\Mail\userVerifyMail;
use App\Mail\userTestMail;
use Mail;
use View;
use Helper;


class mailChkController extends Controller
{
    //
    public function index()
    {
          /*try {

            // Create SMTP Transport
            $transport = new \Swift_SmtpTransport(env('MAIL_HOST'), env('MAIL_PORT'), env('MAIL_ENCRYPTION') );

            // Authentication
            $transport->setUsername( 'sahupravin92@gmail.com' );
            $transport->setPassword( 'dmmlqjeevtcfglfq' );

            // Mailer
            $mailer = new \Swift_Mailer( $transport );

            // Message
            $message = new \Swift_Message();

            // Subject
            $message->setSubject( 'Welcome Swift Mailer' );

            // Sender
            $message->setFrom( [ 'sahupravin92@gmail.com' => 'Pravin sahu' ] );

            // Recipients
            $message->addTo( 'pramod.fwork@gmail.com', 'pramod Name' );

            // CC - Optional
            //$message->addCc( 'cc@gmail.com', 'CC Name' );

            // BCC - Optional
            //$message->addBcc( 'bcc@gmail.com', 'BCC Name' );

            // Body
            $details = [  
                    'name'    => 'pramod',              
                    'email'    => 'pramod.fwork@gmail.com',
                    'pass'    => 'fgdfgdfgdfg',
                    'url'    => 'abc.com',
                ];            
             $view = new userVerifyMail($details);

        $html = $view->render();

            $message->setBody( $html, 'text/html' );

            // Send the message
            $result = $mailer->send( $message );
        }
        catch( Exception $exc ) {

            echo $exc->getMessage();
        }*/

        $subject='Welcome Swift Mailer';
        $to='pramod.fwork@gmail.com';
        $toname='pramod';
        $mailtype='verify';
        $details = [  
                    'name'    => 'pramods',              
                    'email'    => 'pramod.fwork@gmail.com',
                    'pass'    => 'fgdfgdfgdfg2',
                    'url'    => 'abc.com',
                ];
        Helper::setMailWeb($subject,$to,$toname,$details,$mailtype);
    }    
    
}
