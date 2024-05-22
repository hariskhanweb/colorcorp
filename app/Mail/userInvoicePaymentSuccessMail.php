<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class userInvoicePaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;
    public $filename;
   

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details  = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.InstallationInvoicePaySuccMail')
                    ->subject('Installation Invoice Payment Success')
                    ->with('details', $this->details);
    }
}
