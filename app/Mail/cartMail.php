<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class cartMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

     public function build()
     {
         return $this->view('emails.cart')
                     ->subject('cart')
                     ->with([
                         'user_id' => $this->user_id
                     ]);
     }
}
