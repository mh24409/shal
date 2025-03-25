<?php
namespace App\Traits;
use App\Models\Product;
use Illuminate\Support\Facades\URL;
trait TikTokConverasionTrait
{
    public static function TikTokViewContent($detailedProduct, $event_id)
    {
        $discount_applicable = false;
        $lowest_price = $detailedProduct->unit_price;
        $highest_price = $detailedProduct->unit_price;
        if ($detailedProduct->variant_product) {
            foreach ($detailedProduct->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }
        $discount_applicable = false;
        if ($detailedProduct->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $detailedProduct->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $detailedProduct->discount_end_date
        ) {
            $discount_applicable = true;
        }
        if ($discount_applicable) {
            if ($detailedProduct->discount_type == 'percent') {
                $lowest_price -= ($lowest_price * $detailedProduct->discount) / 100;
                $highest_price -= ($highest_price * $detailedProduct->discount) / 100;
            } elseif ($detailedProduct->discount_type == 'amount') {
                $lowest_price -= $detailedProduct->discount;
                $highest_price -= $detailedProduct->discount;
            }
        }
        foreach ($detailedProduct->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }
        $data = [
            "event_source" => "web",
            "event_source_id" => env("TIKTOK_PIXEL_ID"),
            "data" => [
                [
                    "event" => "ViewContent",
                    "event_id" => $event_id,
                    "event_time" => now(),
                    "user" => [
                        "ttclid" => "E.C.P." . $event_id,
                        "external_id" => auth()->check() ? auth()->id() : request()->session()->get('temp_user_id'),
                        "phone" => auth()->check() && auth()->user()->phone ? hash('sha256', auth()->user()->phone) : "",
                        "email" => auth()->check() && auth()->user()->phone ? hash('sha256', auth()->user()->email) : "",
                        "ttp" => request()->session()->get('temp_user_id'),
                        "ip" => request()->ip(),
                        "user_agent" => "website"
                    ],
                    "page" => [
                        "url" => env('APP_URL'),
                        "referrer" => env('APP_URL')
                    ],
                    "properties" => [
                        "contents" => [
                            [
                                "content_id" => $detailedProduct->id,
                                "content_type" => "product",
                                "content_name" => $detailedProduct->name,
                                "content_category" => $detailedProduct->category->name,
                                "quantity" => 1,
                                "price" => $lowest_price,
                                "brand" => "Shal Store"
                            ]
                        ],
                        "value" => $lowest_price,
                        "currency" => "SAR",
                    ]
                ]
            ]
        ];


        $data['test_event_code'] = 'TEST52288';
        $data['pixel_code'] = env('TIKTOK_PIXEL_ID');
        $json_data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://business-api.tiktok.com/open_api/v1.3/event/track');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Access-Token: ' . env('TIKTOK_CONVERASION_TOKEN'),
            'Content-Type: application/json',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    }

    public static function TikTokAddToCart($data, $event_id)
    {
        $data = [
            "test_event_code" => "TEST52288",
            "pixel_code" => env('TIKTOK_PIXEL_ID'),
            "event" => "CompletePayment",
            "event_id" => "123asdios_1234",
            "timestamp" => "2020-12-14T09:49:27Z",
            "test_event_code" => "YOUR_TEST_CODE",
            "context" => [
                "ad" => [
                    "callback" => "123ATXSfe"
                ],
                "page" => [
                    "url" => "http://demo.mywebsite.com/purchase",
                    "referrer" => "http://demo.mywebsite.com"
                ],
                "user" => [
                    "email" => "a4ef46e711c986ea534c9c3d2a1fde303505b539ef5a7ec2a63f23a536d575d1"
                ],
                "user_agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.109 Safari/537.36",
                "ip" => "13.57.97.131"
            ],
            "properties" => [
                "contents" => [
                    [
                        "price" => 8,
                        "quantity" => 2,
                        "content_id" => "1077218",
                        "content_name" => "running shoes",
                        "content_category" => "Shoes > Sneakers > running shoes",
                        "brand" => "your brand name"
                    ],
                    [
                        "price" => 30,
                        "quantity" => 1,
                        "content_id" => "1197218",
                        "content_name" => "running shoes",
                        "content_category" => "Shoes > Sneakers > running shoes",
                        "brand" => "your brand name"
                    ]
                ],
                "content_type" => "product",
                "currency" => "USD",
                "value" => 46.00
            ]
        ];

        $json_data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://business-api.tiktok.com/open_api/v1.3/event/track');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Access-Token: 9a925de93e87d4e3690d6c8797491a9b9e640822',
            'Content-Type: application/json',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    }


    public static function TikTokInitiateCheckout($data, $event_id = null)
    {
        $product_name = Product::find($data['product_id'])->name;
        $data = [
            "event_source" => "web",
            "event_source_id" => $event_id,
            "data" => [
                [
                    "event" => "InitiateCheckout",
                    "event_id" => $event_id,
                    "event_time" => time(),
                    "user" => [
                        "ttclid" => "E.C.P." . $event_id,
                        "external_id" => auth()->check() ? auth()->id() : request()->session()->get('temp_user_id'),
                        "phone" => auth()->check() && auth()->user()->phone ? hash('sha256', auth()->user()->phone) : "",
                        "email" => auth()->check() && auth()->user()->phone ? hash('sha256', auth()->user()->email) : "",
                        "ttp" => request()->session()->get('temp_user_id'),
                        "ip" => request()->ip(),
                        "user_agent" => "website"
                    ],
                    "page" => [
                        "url" => URL::current(),
                        "referrer" => env('APP_URL')
                    ],
                    "properties" => [
                        "contents" => [
                            [
                                "content_id" => $data['product_id'],
                                "content_type" => "product",
                                "content_name" => $product_name,
                                "quantity" => $data['quantity'],
                                "price" => $data['price'] + $data['tax'],
                            ]
                        ],
                        "value" => $data['price'] + $data['tax'],
                        "currency" => "SAR",
                    ]
                ]
            ]
        ];
        $data['test_event_code'] = 'TEST52288';
        $data['pixel_code'] = env('TIKTOK_PIXEL_ID');
        $json_data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://business-api.tiktok.com/open_api/v1.3/event/track');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Access-Token: 9a925de93e87d4e3690d6c8797491a9b9e640822',
            'Content-Type: application/json',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    }


    public static function TikTokPurchase($data, $event_id = null)
    {
        $product_name = Product::find($data['product_id'])->name;
        $data = [
            "event_source" => "web",
            "event_source_id" => $event_id,
            "data" => [
                [
                    "event" => "InitiateCheckout",
                    "event_id" => $event_id,
                    "event_time" => time(),
                    "user" => [
                        "ttclid" => "E.C.P." . $event_id,
                        "external_id" => auth()->check() ? auth()->id() : request()->session()->get('temp_user_id'),
                        "phone" => auth()->check() && auth()->user()->phone ? hash('sha256', auth()->user()->phone) : "",
                        "email" => auth()->check() && auth()->user()->phone ? hash('sha256', auth()->user()->email) : "",
                        "ttp" => request()->session()->get('temp_user_id'),
                        "ip" => request()->ip(),
                        "user_agent" => "website"
                    ],
                    "page" => [
                        "url" => URL::current(),
                        "referrer" => env('APP_URL')
                    ],
                    "properties" => [
                        "contents" => [
                            [
                                "content_id" => $data['product_id'],
                                "content_type" => "product",
                                "content_name" => $product_name,
                                "quantity" => $data['quantity'],
                                "price" => $data['price'] + $data['tax'],
                            ]
                        ],
                        "value" => $data['price'] + $data['tax'],
                        "currency" => "SAR",
                    ]
                ]
            ]
        ];
        $data['test_event_code'] = 'TEST52288';
        $data['pixel_code'] = env('TIKTOK_PIXEL_ID');
        $json_data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://business-api.tiktok.com/open_api/v1.3/event/track');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Access-Token: 9a925de93e87d4e3690d6c8797491a9b9e640822',
            'Content-Type: application/json',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    }

    public static function TikTokPayment($data, $event_id = null)
    {
        $product_name = Product::find($data['product_id'])->name;
        $data = [
            "event_source" => "web",
            "event_source_id" => $event_id,
            "data" => [
                [
                    "event" => "CompletePayment",
                    "event_id" => $event_id,
                    "event_time" => time(),
                    "user" => [
                        "ttclid" => "E.C.P." . $event_id,
                        "external_id" => auth()->check() ? auth()->id() : request()->session()->get('temp_user_id'),
                        "phone" => auth()->check() && auth()->user()->phone ? hash('sha256', auth()->user()->phone) : "",
                        "email" => auth()->check() && auth()->user()->phone ? hash('sha256', auth()->user()->email) : "",
                        "ttp" => request()->session()->get('temp_user_id'),
                        "ip" => request()->ip(),
                        "user_agent" => "website"
                    ],
                    "page" => [
                        "url" => URL::current(),
                        "referrer" => env('APP_URL')
                    ],
                    "properties" => [
                        "contents" => [
                            [
                                "content_id" => $data['product_id'],
                                "content_type" => "product",
                                "content_name" => $product_name,
                                "quantity" => $data['quantity'],
                                "price" => $data['price'] + $data['tax'],
                            ]
                        ],
                        "value" => $data['price'] + $data['tax'],
                        "currency" => "SAR",
                    ]
                ]
            ]
        ];
        $data['test_event_code'] = 'TEST52288';
        $data['pixel_code'] = env('TIKTOK_PIXEL_ID');
        $json_data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://business-api.tiktok.com/open_api/v1.3/event/track');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Access-Token: 9a925de93e87d4e3690d6c8797491a9b9e640822',
            'Content-Type: application/json',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}
