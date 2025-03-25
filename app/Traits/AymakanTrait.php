<?php

namespace App\Traits;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

trait AymakanTrait
{
    public $base_url;

    public function __construct()
    {
        $this->base_url = env('AYMAKAN_BASE_URL');
    }
    public function AymakanNewOrder($combined_order)
    {
        $order = $combined_order->orders()->latest()->first();
        $shipping_info = json_decode($order->shipping_address);
        $url = env('AYMAKAN_BASE_URL'); 
        $authorization = env('AYMAKAN_AUTH_KEY');
        $headers = [
            'Authorization' => $authorization,
            'Accept' => 'application/json'
        ];
        
        if($order->payment_type == "cash_on_delivery")
        {
            $grand_total = $order->grand_total;
        }else{
            $grand_total = 0;
        }
        $body = [
            'requested_by' => "SHAL Store | متجر شال",
            'delivery_name' => $order->user->name,
            'declared_value' => $grand_total,         
            'cod_amount' => $grand_total,         
            'delivery_city' => $shipping_info->city,
            'delivery_address' => $shipping_info->address,
            'delivery_description' => $order->additional_info,
            'delivery_country' => 'SA',
            'delivery_phone' => $shipping_info->phone,
            'collection_name' => "SHAL Store | متجر شال",
            'collection_email' => $order->email ?? "shorbatli@gmail.com",
            'collection_city' => $shipping_info->state,
            'collection_address' => $shipping_info->address,
            'collection_country' => 'SA',
            'collection_phone' => $shipping_info->phone,
            'pieces' => $order->orderDetails->sum('quantity')
        ];
        $response = Http::withoutVerifying()->withHeaders($headers)->post($url, $body);
        if ($response->successful()) {
            $responseData = $response->json();
            $order->tracking_code  = $responseData['shipping']['tracking_number'];
            $order->shipping_barcode  = $responseData['shipping']['pdf_label'];
            $order->save();
        } else {
            $errorMessage = $response->body();
            return $errorMessage;
        }
    }

    public function AymakanCancelOrder($tracking_number)
    {
        $url = env('AYMAKAN_BASE_CANCEL_URL');
        $tracking = "AY505746402";
        $authorizationToken = env('AYMAKAN_AUTH_KEY');
        $postData = json_encode([
            'tracking' => $tracking
        ]);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $authorizationToken
        ]);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            curl_close($ch);
            return "Error: " . $error_message;
        }
        curl_close($ch);
        return $response;
    }
}
