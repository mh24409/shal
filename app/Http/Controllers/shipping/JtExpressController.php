<?php

namespace App\Http\Controllers\Shipping;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Shipping\JtExpress;

class JtExpressController extends Controller
{
    
    public function createOrder()
    {
        $order = Order::with('orderDetails')->first();
        $shipping_address = json_decode($order->shipping_address,true);
        $items = [];
        $items_value = $order->grand_total;
        $quantity = 0;
        foreach($order->orderDetails as $item)
        {
            $items[] = [
                'number' => $item->id ,
                'itemName' => $item->product->name ,
                'itemValue' => $item->price,
                'itemUrl' => route('product', ['slug' => $item->product->slug]),
                'desc' => $item->product->short_description
            ];
            $quantity += $item->quantity;
        }

        $data = [
            'customerCode'=> env('JT_EXPRESS_USER'),
            'digest'=> '4hQ8qXNkuSJ8cIgJQDFFRA==',
            'length'=> '20',
            'sendStartTime'=> '2021-12-03 10:02:50',
            'weight'=> '20',
            'billCode'=> strtoupper("bc-" . Str::random(6)),
            'txlogisticId'=> strtoupper("tli-" . Str::random(6)),
            'totalQuantity'=> $quantity,
            'receiver'=> [
                'area'=> 'N/A',
                'address'=> $shipping_address['address'],
                'town'=> '',
                'street'=> '',
                'city'=> $shipping_address['city'],
                'mobile'=> $shipping_address['phone'],
                'mailBox'=> $shipping_address['email'],
                'phone'=> $shipping_address['phone'],
                'countryCode'=> 'KSA',
                'name'=> $shipping_address['name'],
                'company'=> 'Shal Store',
                'postCode'=> '518000',
                'prov'=> 'Al Jawf'
            ],
            'itemsValue'=> $items_value ,
            'width'=> '23',
            'items'=>$items,
            'sendEndTime'=> '2021-12-05 10:02:50',
            'height'=> '10',
        ];
        $shipping = new JtExpress();
        dd($shipping->createOrder($data));
    }
}
