<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Traits\fizpaTrackingOrder;
class UpdateOrderShippingCompanyStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }
    public function fizpaTrackingOrder($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fizzapi.anyitservice.com/api/Tracking/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: UF3M0Q1F7W5ZLWWCN2DLZQBDYDTLMMN6F5HUQ1ABHJH7K5Y17KGSJR5EDZWW5P1UI7UOPCVV1BEPJD11O1NCG3XWADQZENF0QVYL",
            "Referer: https://shal.store"
        ));
        $response = curl_exec($ch);
        return $response;
    }
    public function handle()
    {
       return $this->fizpaTrackingOrder($this->order->tracking_code);
    }
}
