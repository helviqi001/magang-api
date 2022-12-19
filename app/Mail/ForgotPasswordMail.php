<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp)
    {
        //
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $data = $this->data;

       $otp = $this->otp['otp'];
        return $this->from($this->otp['email'])
        ->subject($this->otp['subject'])
        ->view($this->otp['view'])
        ->with($otp);
    }
}