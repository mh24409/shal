<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceEmailManager extends Mailable
{
    use Queueable, SerializesModels;
    public $array;

    public function __construct($array)
    {
        $this->array = $array;
    }
    
     public function build()
     {
         return $this->view($this->array['view'])
                     ->from($this->array['from'], env('MAIL_FROM_NAME'))
                     ->subject($this->array['subject'])
                     ->with([
                         'order' => $this->array['order']
                     ]);
     }
}
