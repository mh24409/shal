<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

trait SnapchatConversionTrait
{
    public static function SnapChatViewContent($detailedProduct, $event_id)
    {
        $data = [
            "pixel_id" => env('SNAPCHAT_PIXEL_ID'),
            'price' => $detailedProduct->unit_price,
            'currency' => "SAR",
            'item_ids' => $detailedProduct->id,
            'item_category' => $detailedProduct->category->name,
            'brands' => $detailedProduct->brand->name  ?? env('APP_NAME'),
            'client_deduplication_id' => $event_id,
            'customer_status' => auth()->check() ? 'user' : 'guest',
            'number_items' => "1",
            'description' => htmlentities($detailedProduct->description),
            "timestamp" => now(),
            "event_type" => "VIEW_CONTENT",
            "event_conversion_type" => "WEB",
            "event_tag" => "badger_tunneling",
            "page_url" => URL::current(),
            "hashed_email" => "20e468ca5f7903e80fe3aa79ac3b8f1436f99163a9802b55891f38be95b7959e",
            "user_agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Safari/605.1.15",
            "hashed_ip_address" => "1150a143ae46d77bca2f9f4429f8c65d4d746edc37b769b044659b820493d0db"
        ];

        $data_json = json_encode($data);
        $headers = [
            'Content-Type: application/json',
            "Authorization:Bearer eyJhbGciOiJIUzI1NiIsImtpZCI6IkNhbnZhc1MyU0hNQUNQcm9kIiwidHlwIjoiSldUIn0.eyJhdWQiOiJjYW52YXMtY2FudmFzYXBpIiwiaXNzIjoiY2FudmFzLXMyc3Rva2VuIiwibmJmIjoxNzA3ODM2MDMwLCJzdWIiOiI4MDBiOWNiMS1hZTk5LTQ0YjItOTE4ZS1kMjI1ZjlhMTgxNzh-UFJPRFVDVElPTn4wYTgzMDAzNC04YmIxLTRkOTctYjU4OS1mNGI1MWQzOWQwY2IifQ.B2DBtzmvFIPP8t5D3_YyFEPOTRRzcyLxMJwYbCV4eMU",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://tr.snapchat.com/v2/conversion');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    }
    public static function SnapChatAddToCart($detailedProduct)
    {

        // 'price': data.data.price,
        // 'currency': 'SAR', 
        // 'client_deduplication_id': data.data.pixel_event_id,
        // 'item_ids': data.data.product_id,
        $data = [
            "pixel_id" => env('SNAPCHAT_PIXEL_ID'),
            'price' => $detailedProduct['price'],
            'currency' => "SAR",
            'item_ids' => $detailedProduct['product_id'],
            'client_deduplication_id' => $detailedProduct['pixel_event_id'],
            'customer_status' => auth()->check() ? 'user' : 'guest',
            "timestamp" => now(),
            "event_type" => "ADD_CART",
            "event_conversion_type" => "WEB",
            "event_tag" => "badger_tunneling",
            "page_url" => URL::current(),
            "hashed_email" => "20e468ca5f7903e80fe3aa79ac3b8f1436f99163a9802b55891f38be95b7959e",
            "user_agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Safari/605.1.15",
            "hashed_ip_address" => "1150a143ae46d77bca2f9f4429f8c65d4d746edc37b769b044659b820493d0db"
        ];

        $data_json = json_encode($data);
        $headers = [
            'Content-Type: application/json',
            "Authorization:Bearer eyJhbGciOiJIUzI1NiIsImtpZCI6IkNhbnZhc1MyU0hNQUNQcm9kIiwidHlwIjoiSldUIn0.eyJhdWQiOiJjYW52YXMtY2FudmFzYXBpIiwiaXNzIjoiY2FudmFzLXMyc3Rva2VuIiwibmJmIjoxNzA3ODM2MDMwLCJzdWIiOiI4MDBiOWNiMS1hZTk5LTQ0YjItOTE4ZS1kMjI1ZjlhMTgxNzh-UFJPRFVDVElPTn4wYTgzMDAzNC04YmIxLTRkOTctYjU4OS1mNGI1MWQzOWQwY2IifQ.B2DBtzmvFIPP8t5D3_YyFEPOTRRzcyLxMJwYbCV4eMU",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://tr.snapchat.com/v2/conversion');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    }
    public static function SnapChatInitiateCheckout($data, $event_id = null)
    {
        $ids = $data->pluck('product_id')->toArray();
        $commaSeparatedIds = implode(',', $ids);
        $price = $data->sum('price');
        $data = [
            "pixel_id" => env('SNAPCHAT_PIXEL_ID'),
            'price' => $price,
            'currency' => "SAR",
            'item_ids' => $commaSeparatedIds,
            'number_items' => count($data),
            'client_deduplication_id' => $event_id,
            'customer_status' => auth()->check() ? 'user' : 'guest',
            "timestamp" => now(),
            "event_type" => "START_CHECKOUT",
            "event_conversion_type" => "WEB",
            "event_tag" => "badger_tunneling",
            "page_url" => URL::current(),
            "hashed_email" => "20e468ca5f7903e80fe3aa79ac3b8f1436f99163a9802b55891f38be95b7959e",
            "user_agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Safari/605.1.15",
            "hashed_ip_address" => "1150a143ae46d77bca2f9f4429f8c65d4d746edc37b769b044659b820493d0db"
        ];
        $data_json = json_encode($data);
        $headers = [
            'Content-Type: application/json',
            "Authorization:Bearer eyJhbGciOiJIUzI1NiIsImtpZCI6IkNhbnZhc1MyU0hNQUNQcm9kIiwidHlwIjoiSldUIn0.eyJhdWQiOiJjYW52YXMtY2FudmFzYXBpIiwiaXNzIjoiY2FudmFzLXMyc3Rva2VuIiwibmJmIjoxNzA3ODM2MDMwLCJzdWIiOiI4MDBiOWNiMS1hZTk5LTQ0YjItOTE4ZS1kMjI1ZjlhMTgxNzh-UFJPRFVDVElPTn4wYTgzMDAzNC04YmIxLTRkOTctYjU4OS1mNGI1MWQzOWQwY2IifQ.B2DBtzmvFIPP8t5D3_YyFEPOTRRzcyLxMJwYbCV4eMU",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://tr.snapchat.com/v2/conversion');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    }
    public static function SnapChatPurchase($data, $event_id = null)
    {
        $ids = $data->pluck('product_id')->toArray();
        $commaSeparatedIds = implode(',', $ids);
        $price = $data->sum('price');
        $data = [
            "pixel_id" => env('SNAPCHAT_PIXEL_ID'),
            'price' => $price,
            'currency' => "SAR",
            'item_ids' => $commaSeparatedIds,
            'number_items' => count($data),
            'client_deduplication_id' => $event_id,
            'customer_status' => auth()->check() ? 'user' : 'guest',
            "timestamp" => now(),
            "event_type" => "START_CHECKOUT",
            "event_conversion_type" => "WEB",
            "event_tag" => "badger_tunneling",
            "page_url" => URL::current(),
            "hashed_email" => "20e468ca5f7903e80fe3aa79ac3b8f1436f99163a9802b55891f38be95b7959e",
            "user_agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Safari/605.1.15",
            "hashed_ip_address" => "1150a143ae46d77bca2f9f4429f8c65d4d746edc37b769b044659b820493d0db"
        ];
        $data_json = json_encode($data);
        $headers = [
            'Content-Type: application/json',
            "Authorization:Bearer eyJhbGciOiJIUzI1NiIsImtpZCI6IkNhbnZhc1MyU0hNQUNQcm9kIiwidHlwIjoiSldUIn0.eyJhdWQiOiJjYW52YXMtY2FudmFzYXBpIiwiaXNzIjoiY2FudmFzLXMyc3Rva2VuIiwibmJmIjoxNzA3ODM2MDMwLCJzdWIiOiI4MDBiOWNiMS1hZTk5LTQ0YjItOTE4ZS1kMjI1ZjlhMTgxNzh-UFJPRFVDVElPTn4wYTgzMDAzNC04YmIxLTRkOTctYjU4OS1mNGI1MWQzOWQwY2IifQ.B2DBtzmvFIPP8t5D3_YyFEPOTRRzcyLxMJwYbCV4eMU",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://tr.snapchat.com/v2/conversion');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}
