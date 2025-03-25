<?php
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\FollowSellerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductQueryController;
use App\Http\Controllers\Payment\BkashController;
use App\Http\Controllers\Payment\NagadController;
use App\Http\Controllers\Payment\Edfa3Controller;
use App\Http\Controllers\Payment\PaykuController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\DigitalProductController;
use App\Http\Controllers\Payment\IyzicoController;
use App\Http\Controllers\Payment\PaypalController;
use App\Http\Controllers\Payment\PayMobController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\Payment\NgeniusController;
use App\Http\Controllers\Payment\PayhereController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\Payment\AamarpayController;
use App\Http\Controllers\Payment\PaystackController;
use App\Http\Controllers\Payment\RazorpayController;
use App\Http\Controllers\Payment\VoguepayController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Payment\InstamojoController;
use App\Http\Controllers\Payment\SslcommerzController;
use App\Http\Controllers\Payment\MercadopagoController;
use App\Http\Controllers\Payment\AuthorizenetController;
use App\Http\Controllers\Payment\TamaraController;
use App\Models\Product;
use App\Models\BusinessSetting;
use App\Models\User;
use App\Models\Order;
use GuzzleHttp\Client;
use App\Http\Controllers\Aramex\CountriesController;
use App\Models\Brand;
use App\Models\Upload;
use App\Models\Cart;
use FacebookAds\Api;
use FacebookAds\Object\Catalog;
use Illuminate\Support\Facades\File;
use App\Mail\InvoiceEmailManager;
use Illuminate\Support\Facades\Mail;
use App\Mail\cartMail;
use App\Models\City;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Events\UserKeyChanged;
use App\Events\OrderEdfaStatus;
use Illuminate\Http\Request;
use App\Models\Category;



Route::get('Tiktok', function () {
    $data = [
        "test_event_code" => "TEST52288",
        "pixel_code" => "CO1V9JRC77UCJOSK3AO0",
        "event_source" => "web",
        "event_source_id" => "CO1V9JRC77UCJOSK3AO0",
        "data" => [
            [
                "event" => "ViewContent",
                "event_id" => Str::random('30'),
                "event_time" => now(),
                "user" => [
                    "ttclid" => "CO1V9JRC77UCJOSK3AO0",
                    "external_id" => hash('sha256',"CO1V9JRC77UCJOSK3AO0"),
                    "phone" => hash('sha256', "201273095210"),
                    "email" => hash('sha256', "hasonamohamed033@gmail.com"),
                    "ttp" => "CO1V9JRC77UCJOSK3AO0",
                    "ip" => "CO1V9JRC77UCJOSK3AO0",
                    "user_agent" => "website"
                ],
                "page" => [
                    "url" => env('APP_URL')
                ],
                "properties" => [
                    "contents" => [
                        [
                            "content_id" => "19589",
                            "content_type" => "product",
                            "content_name" => "طاجن مقاس 45*32 بالشواية",
                            "content_category" => "Category",
                            "quantity" => 1,
                            "price" => "480",
                            "brand" => "Shal Store"
                        ]
                    ],
                    "value" => "500",
                    "currency" => "SAR",
                    "query" => "Shal",
                    "description" => "Shal",
                    "status" => "Draft"
                ]
            ]
        ]
    ];
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
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return response()->json(['error' => $error], 500);
    }
    curl_close($ch);
    $decoded_response = json_decode($response, true);
    if ($decoded_response === null) {
        // Response is not valid JSON
        return response()->json(['error' => 'Invalid JSON response'], 500);
    }
    return response()->json($decoded_response);
});


Route::get('tiktok-pixel-track', function () {
    $payload = [
        "pixel_code" => "CO1V9JRC77UCJOSK3AO0",
        "event" => "CompletePayment",
        "event_id" => "123asdios_1234",
        "timestamp" => "2020-12-14T09:49:27Z",
        "test_event_code" => "TEST52288",
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
    $response = Http::withHeaders([
        'Access-Token' => env('TIKTOK_CONVERASION_TOKEN'),
        'Content-Type' => 'application/json',
    ])->post('https://business-api.tiktok.com/open_api/v1.3/pixel/track', $payload);
    dd($response);
    if ($response->failed()) {
        return response()->json(['error' => 'Failed to track pixel.'], $response->status());
    }
    $responseData = $response->json();
    return response()->json($responseData);
});
 Route::get('/catalog2/{lang}',function (Request $request){
    $products =\App\Models\Product::where('published',1)->get();
    $stocks = \App\Models\ProductStock::whereHas('product', function ($query) {
        $query->where('published', 1);
    })->get();
    $app_url = env('APP_URL');
    $app_name = env('APP_NAME');
    $head ="<?xml version='1.0'?>
    <rss xmlns:g='http://base.google.com/ns/1.0' version='2.0'>
    <channel>
    <title>$app_name</title>
    <link>$app_url</link>
    <description>$app_name</description>";
    $content = env('APP_NAME') ." "."Catalog";
    $foot =
    '</channel> 
    </rss>';
    foreach($stocks as $stock)
    {
        $product_stock=Product::find($stock->product_id);
        if($stock->variation !== '')
        {
            $product_stock = Product::find($stock->product_id);
            $item_group_id = $product_stock->item_group_id;
            $stock_id = $stock->id;
            $image_link = Upload::find($product_stock->thumbnail_img)->file_name ??Upload::first()->file_name ;
        $url = route('product', ['slug' => $product_stock->slug]);
        $img_link =  env('APP_URL') . '/public' . '/' . $image_link;
        if ($product_stock->brand_id == null) {
            $brand = env('APP_NAME');
        } else {
            $brand = Brand::find($product_stock->brand_id)->name;
        }
        
            $categories = Category::find($product_stock->category_id)->name;
            $stock_image_link = Upload::find($stock->imato)->file_name ?? null;
            $stock_image_link =   $stock_image_link != null ? env('APP_URL') . '/public' . '/' . $image_link : $img_link;
            $stock_name =  htmlentities($product_stock->name);
                    $sale_price = home_discounted_base_price_catalog($product_stock);

            $stock_sale_price = $stock->price + ( $sale_price > $product_stock->unit_price ? $sale_price - $product_stock->unit_price : $product_stock->unit_price  - $sale_price );
            $stock_price = $stock->price - $product_stock->discount;
            if ($stock->qty > 0) {
                $available = "in stock";
            } else {
                $available = "out of stock";
            }
            $content .=
            "<item>
            <g:id>$stock_id</g:id>
            <g:item_group_id>$item_group_id</g:item_group_id>
            <g:sale_price>$stock->price</g:sale_price>
            <g:title>$stock_name</g:title>
            <g:description>test</g:description>
            <g:availability>$available</g:availability>
            <g:condition>new</g:condition>
            <g:price>$stock_price</g:price>
            <g:link>$url</g:link>
            <g:image_link>$stock_image_link</g:image_link>
            <g:brand>$brand</g:brand>
            <g:google_product_category>$categories</g:google_product_category>
            </item>
            ";
        }
    }
    $page = '';
    $page .= $head;
    $page .= $content;
    $page .= $foot;
    $page = str_replace('&','&amp;',trim($page));
        return response($page)->header('Content-Type', 'text/xml');
});
Route::get('test-pusher',function(){
    Artisan::call('optimize:clear');
    $order = Order::find(286);
    event(new OrderEdfaStatus($order));

});
Route::get('test-edfa3', function (Request $request) {
    $order = null;
    if (Session::has('payment_type')) {
        if (Session::get('payment_type') == 'cart_payment') {
            $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
            $order = $combined_order->orders->first();
            $amount = $order->grand_total;

            $shipping_address = json_decode($order->shipping_address, true);
        }
    }
    $order = Order::latest()->first();
    $ip = request()->ip();
    $orderNumber = $order->id;
    $orderAmount = $order->grand_total;
    $orderCurrency = "SAR";
    $orderDescription = "Order For Shal Store 22";
    $merchantPassword = "387af2bcde51f73b57408693f09ffb54";
    $merchant_id = "6cfa3a3b-8b50-4b88-b138-7a55b11b8bae";

    $url  = url('loading');
    $toMd5 = strtoupper($orderNumber . $orderAmount . $orderCurrency . $orderDescription . $merchantPassword);
    $md5 = md5($toMd5);
    $sha1 = sha1($md5);
    $postData = [
        'action'            => 'SALE',
        'edfa_merchant_id'  => $merchant_id,
        'order_id'          => $orderNumber,
        'order_amount'      => $orderAmount,
        'order_currency'    => 'SAR',
        'order_description' => $orderDescription,
        'req_token'         => 'N',
        'payer_first_name'  => "Mrs",
        'payer_last_name'   => "test ",
        'payer_address'     => "test ",
        'payer_country'     => 'SA',
        'payer_city'        => "test",
        'payer_zip'         => '12221',
        'payer_email'       => "test@gmail.com",
        'payer_phone'       => "test ",
        'payer_ip'          => $ip,
        'term_url_3ds'      => $url,
        'auth'              => 'N',
        'recurring_init'    => 'Y',
        'hash'              => $sha1
    ];
    $ch = curl_init('https://api.edfapay.com/payment/initiate');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    if ($response === false) {
        throw new Exception('Curl error: ' . curl_error($ch));
    }

    $response = json_decode($response);

    return redirect($response->redirect_url);

});
Route::get('test_fizpa', function () {
    $url = "https://fizzapi.anyitservice.com/api/locations/cities/riyadh/ar";
    $headers = [
        'Referer: https://shal.store',
        'Authorization: UF3M0Q1F7W5ZLWWCN2DLZQBDYDTLMMN6F5HUQ1ABHJH7K5Y17KGSJR5EDZWW5P1UI7UOPCVV1BEPJD11O1NCG3XWADQZENF0QVYL',
    ];
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    return $response;
    if ($response === false) {
        $error = curl_error($curl);
        // Handle the error here
        echo "cURL Error: " . $error;
    } else {
        echo $response;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://fizzapi.anyitservice.com/api/Auth/me");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: UF3M0Q1F7W5ZLWWCN2DLZQBDYDTLMMN6F5HUQ1ABHJH7K5Y17KGSJR5EDZWW5P1UI7UOPCVV1BEPJD11O1NCG3XWADQZENF0QVYL",
        "Referer: https://shal.store"
    ));

    $response = curl_exec($ch);
    curl_close($ch);


    return $response;
});


Route::get('edna', function () {
try {
    $name = "shal";
    $number = 1234;
    $phone = "201559470947";
    $client = new Client();
    $baseUrl = env('EDNA_BASEURL');
    $headers = [
        'Content-Type' => 'application/json',
        'x-api-key' => env('EDNA_APIKEY'),
    ];
    $text = "يا هلا وغلا بأحلى عميلة في $name وصلت لنا طلبيتك رقم $number وحبينا نقول لك: مبروك عليك الاختيار اللي راح يزيدك جمال على جمال! 🌹 فاتورة طلبيتك مرفقة هنا لعيونك\n\nفي شال كل قطعة نقدمها مختارة بعناية فائقة، عشان تليق بجمالك وتبرزه للدنيا وإنتي بإختيارك لنا، اثبتي إنك مو بس تبحثين عن الجمال، لكن تعرفين وين تلاقينه!\n\nنحن متحمسين قد السما عشان تشوفين طلبيتك وتجربينها واحنا واثقين إنها راح تعجبك قد ما عجبتنا وهي تتجهز لك\n\n*ترقبي وصولها... واستعدي للمفاجآت! 💖\n\nدمتي متألقة وأجمل مع $name *فريق $name";



$requestId = (string) Str::uuid();
$phone = "201559470947";
$name = "شال"; // Assuming $name represents the store name
$text = "يا هلا وغلا بأحلى عميلة في شال {{1}}\n\nوصلت لنا طلبيتك رقم {{2}} وحبينا نقول لك:\n*مبروك عليك الاختيار اللي راح يزيدك جمال على جمال! 🌹\nفاتورة طلبيتك مرفقة هنا لعيونك\n\nفي شال، كل قطعة نقدمها مختارة بعناية فائقة، عشان تليق بجمالك وتبرزه للدنيا\nوإنتي بإختيارك لنا، اثبتي إنك مو بس تبحثين عن الجمال، لكن تعرفين وين تلاقينه!\n\nنحن متحمسين قد السما عشان تشوفين طلبيتك وتجربينها\nواحنا واثقين إنها راح تعجبك قد ما عجبتنا وهي تتجهز لك\n\n*ترقبي وصولها... واستعدي للمفاجآت! 💖\n\nدمتي متألقة وأجمل مع شال،\n*فريق شال*";

$jsonData = '{
    "text": "يا هلا وغلا بأحلى عميلة في شال sometext\n\nوصلت لنا طلبيتك رقم more text وحبينا نقول لك:\n*مبروك عليك الاختيار اللي راح يزيدك جمال على جمال! 🌹\nفاتورة طلبيتك مرفقة هنا لعيونك\n\nفي شال، كل قطعة نقدمها مختارة بعناية فائقة، عشان تليق بجمالك وتبرزه للدنيا\nوإنتي بإختيارك لنا، اثبتي إنك مو بس تبحثين عن الجمال، لكن تعرفين وين تلاقينه!\n\nنحن متحمسين قد السما عشان تشوفين طلبيتك وتجربينها\nواحنا واثقين إنها راح تعجبك قد ما عجبتنا وهي تتجهز لك\n\n*ترقبي وصولها... واستعدي للمفاجآت! 💖\n\nدمتي متألقة وأجمل مع شال،\n*فريق شال*",
    "footer": {"text": "متجر شال | SHAL Store"},
    "keyboard": {"row": [{"buttons": [{"url": "https://shall.dokkan.xyz/checkout/order-confirmed/page1", "text": "الاطلاع على الفاتورة", "buttonType": "URL"}]}]},
    "securityRecommendation": false
}';

// Decode JSON data into associative array
$data = json_decode($jsonData, true);

// Build the $body array
$body = [
    "requestId" => $requestId,
    'cascadeId' => "945",
    'subscriberFilter' => [
        'address' => $phone,
        'type' => 'PHONE'
    ],
    'startTime' => "2021-01-21T08:00:00Z", // Use needed time and date
    'content' => [
        'whatsappContent' => [
            'contentType' => 'TEXT',
            'text' => $data['text'],
            'footer' => [
                'text' => $data['footer']['text']
            ],
            'keyboard' => [
                'rows' => $data['keyboard']['row'] // 'row' should be 'rows'
            ]
        ]
    ]
];

// Convert $body array to JSON format
$bodyJson = json_encode($body);

// Output the $bodyJson for debugging or further processing
    $response = $client->post($baseUrl, [
        'headers' => $headers,
        'json' => $body,
    ]);
    $responseData = json_decode($response->getBody()->getContents(), true);
    return response()->json([
        'success' => true,
        'message' => 'Message sent successfully',
        'data' => $responseData,
    ]);
} catch (RequestException $e) {
    return response()->json([
        'success' => false,
    ]);
}

    $client = new Client();
    $baseUrl = "https://app.edna.io/api/cascade/schedule"; 
    $messageMatcherId = 12765;
    $headers = [
        'Content-Type' => 'application/json',
        'x-api-key' => '7de7bc40-bf04-4b2e-8158-c8a85f6fb335', 
    ];

    $otpCode = mt_rand(1000, 9999);
    $body = [
        "requestId" => (string) Str::uuid(),
        "cascadeId" => "945",
        "subscriberFilter" => [
            "address" => "966563331605",
            "type" => "PHONE"
        ],
        "content" => [
            "whatsappContent" => [
                "contentType" => "AUTHENTICATION",
                "messageMatcherId" => 13063,
                "text" => $otpCode,
            ],
        ]
    ];
    $response = $client->post($baseUrl, [
        'headers' => $headers,
        'json' => $body,
    ]);
    $responseData = json_decode($response->getBody()->getContents(), true);
    return response()->json([
        'success' => true,
        'message' => 'Message sent successfully',
        'data' => $responseData,
    ]);
    try {
        $response = $client->post($baseUrl, [
            'headers' => $headers,
            'json' => $body,
        ]);
        $responseData = json_decode($response->getBody()->getContents(), true);
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $responseData,
        ]);
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        return response()->json([
            'success' => false,
            'message' => 'Failed to send message',
            'error' => json_decode($responseBodyAsString, true),
        ], $response->getStatusCode());
    } catch (\GuzzleHttp\Exception\ServerException $e) {
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        return response()->json([
            'success' => false,
            'message' => 'Server error occurred',
            'error' => json_decode($responseBodyAsString, true),
        ], $response->getStatusCode());
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred',
            'error' => $e->getMessage(),
        ], 500);
    }
});
Route::get('JntOrder', function () {
    // JNT API credentials
    $apiAccount = '292508153084379141';
    $privateKey = 'a0a1047cce70493c9d5d29704f05d0d9';
    // Prepare timestamp
    $timestamp = time();
    $apiEndpoint = 'https://demoopenapi.jtjms-sa.com/webopenplatformapi/api/order/addOrder?uuid=9507241ba7334189a27fe19a0e573b41';

    $concatenatedString = $apiAccount . $privateKey . $timestamp;

    // Calculate MD5 hash and encode in base64
    $digest = base64_encode(md5($concatenatedString, true));

    // Prepare request payload
    $requestPayload = [
        'bizContent' => "{'customerCode': 'J0086024071', 'digest': '$digest', 'serviceType': '01', 'orderType': '2', 'deliveryType': '04', 'expressType': 'EZKSA', 'network': '', 'length': 30, 'sendStartTime': '2021-12-03 10:02:50', 'weight': 5.01, 'remark': 'test', 'billCode': '', 'batchNumber': '', 'txlogisticId': 'SA67733240451', 'goodsType': 'ITN1', 'totalQuantity': '1', 'receiver': {'area': 'sdfdsafdsfdsafdsa1', 'address': 'sdfsacdscdscds2a', 'town': '', 'street': '', 'city': 'Abu Ajram', 'mobile': '1441234567843543543554311143', 'mailBox': 'ant_li123@qq.com', 'phone': '1441234567843543543554311143', 'countryCode': 'KSA', 'name': 'test_receiver', 'company': 'guangdongshengshenzhenshizhuantayigeyidia nzishiyeyouxianggongsi', 'postCode': '518000', 'prov': 'Al Jawf'}, 'sender': {'area': 'sdfsafdsafsdfdsa', 'address': 'cdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdss', 'town': 'town1', 'street': 'street1', 'city': 'Al Ammarah', 'mobile': '1441234567843543543554311143', 'mailBox': 'ant_li12345678901234567890@qq.com', 'phone': '1441234567843543543554311143', 'countryCode': 'KSA', 'name': 'test_sender', 'company': 'jiangsunanjingshihonghuangzhili kejiyouxiangongsisdfdsfdsfds', 'postCode': '518000', 'prov': 'Al Qassim'}, 'itemsValue': 1500.02, 'priceCurrency': 'AED', 'width': 10, 'offerFee': 3600.01, 'items': [{'number': 1, 'itemType': 'ITN1', 'itemName': '服饰123456test', 'priceCurrency': 'DHS', 'itemValue': '12.36', 'itemUrl': 'http://www.baidu.com/shangpinlianjiedizhi', 'desc': 'test_ordermiaoshu'}, {'number': 1, 'itemType': 'ITN1', 'itemName': '服饰123456test', 'priceCurrency': 'DHS', 'itemValue': '12.36', 'itemUrl': 'http://www.baidu.com/shangpinlianjiedizhi', 'desc': 'test_ordermiaoshu'}, {'number': 1, 'itemType': 'ITN1', 'itemName': '服饰123456test', 'priceCurrency': 'DHS', 'itemValue': '12.36', 'itemUrl': 'http://www.baidu.com/shangpinlianjiedizhi', 'desc': 'test_ordermiaoshu'}, {'number': 1, 'itemType': 'ITN1', 'itemName': '服饰123456test', 'priceCurrency': 'DHS', 'itemValue': '12.36', 'itemUrl': 'http://www.baidu.com/shangpinlianjiedizhi', 'desc': 'test_ordermiaoshu'}, {'number': 1, 'itemType': 'ITN1', 'itemName': '服饰123456test', 'priceCurrency': 'DHS', 'itemValue': '12.36', 'itemUrl': 'http://www.baidu.com/shangpinlianjiedizhi', 'desc': 'test_ordermiaoshu'}, {'number': 1, 'itemType': 'ITN1', 'itemName': '服饰123456test', 'priceCurrency': 'DHS', 'itemValue': '12.36', 'itemUrl': 'http://www.baidu.com/shangpinlianjiedizhi', 'desc': 'test_ordermiaoshu'}, {'number': 1, 'itemType': 'ITN1', 'itemName': '服饰123456test', 'priceCurrency': 'DHS', 'itemValue': '12.36', 'itemUrl': 'http://www.baidu.com/shangpinlianjiedizhi', 'desc': 'test_ordermiaoshu'}, {'number': 1, 'itemType': 'ITN1', 'itemName': '服饰123456test', 'priceCurrency': 'DHS', 'itemValue': '12.36', 'itemUrl': 'http://www.baidu.com/shangpinlianjiedizhi', 'desc': 'test_ordermiaoshu'}, {'number': 1, 'itemType': 'ITN1', 'itemName': '服饰123456test', 'priceCurrency': 'DHS', 'itemValue': '12.36', 'itemUrl': 'http://www.baidu.com/shangpinlianjiedizhi', 'desc': 'test_ordermiaoshu'}, {'number': 1, 'itemType': 'ITN1', 'itemName': '服饰123456test', 'priceCurrency': 'DHS', 'itemValue': '12.36', 'itemUrl': 'http://www.baidu.com/shangpinlianjiedizhi', 'desc': 'test_ordermiaoshu'}], 'sendEndTime': '2021-12-04 10:02:50', 'height': 60, 'payType': 'PP_PM', 'operateType': 1, 'platformName': '开放13213154564646132131312sdfdsfdsfdsafdsafdsdsfdsafdsafdsfsadfsdafsdafsad', 'customerAccount': '客户0086023990sdfdsfdsfdsafdsafdsafdsfsacdsfdsafdsfdsfdsafdsafsdfsdfdsafdsafdsfdsafdsa', 'isUnpackEnabled': 1}",
    ];

    // Make the API request
    $response = Http::withHeaders([
        'apiAccount' => $apiAccount,
        'digest' => $digest,
        'timestamp' => $timestamp,
    ])->post($apiEndpoint, $requestPayload);

    // Get the response body


    return $response;
    $responseBody = $response->json();







    //     $customerNumber = '966563331605';
    //     $plaintextPassword = 'As140801407';
    //     $privateKey = 'a0a1047cce70493c9d5d29704f05d0d9';

    //     // Create the ciphertext by MD5 hashing the plaintext password and 'jadada236t2'
    //     $ciphertextMd5 = strtoupper(md5($plaintextPassword . 'jadada236t2'));

    //     // Create the digest by concatenating customer number, ciphertext, and private key
    //     // Then MD5 hash this concatenation and encode in Base64
    //     $digestMd5 = base64_encode(md5($customerNumber . $ciphertextMd5 . $privateKey, true));






    //         $apiUrl = 'https://demoopenapi.jtjms-sa.com/webopenplatformapi/api/order/addOrder';
    //         $apiAccount = '292508153084379141';
    //         $digest = 'dv7y+k4Pth0asb/tf7IOtg==';
    //         $timestamp = '1638428570653';

    //         // Prepare the headers

    // $headers = [
    //     'apiAccount' => '292508153084379141', // The API account number
    //     'digest' => $digestMd5, // The Base64-encoded MD5 digest
    //     'timestamp' => $timestamp, // The current timestamp in milliseconds
    //     'digestType' => '1', // The digest type indicating MD5
    //     'Content-Type' => 'application/json' // Assuming the content type is JSON
    // ];

    //         // Prepare the request payload (body)
    // $requestData = [
    //     'bizContent' => '{
    //         "customerCode": "J0086024071",
    //         "digest": "4hQ8qXNkuSJ8cIgJQDFFRA==",
    //         "serviceType": "01",
    //         "orderType": "2",
    //         "deliveryType": "04",
    //         "expressType": "EZKSA",
    //         "network": "",
    //         "length": 30,
    //         "sendStartTime": "2021-12-03 10:02:50",
    //         "weight": 5.01,
    //         "remark": "test",
    //         "billCode": "",
    //         "batchNumber": "",
    //         "txlogisticId": "SA67733240451",
    //         "goodsType": "ITN1",
    //         "totalQuantity": "1",
    //         "receiver": {
    //             "area": "sdfdsafdsfdsafdsa1",
    //             "address": "sdfsacdscdscds2a",
    //             "town": "",
    //             "street": "",
    //             "city": "Abu Ajram",
    //             "mobile": "1441234567843543543554311143",
    //             "mailBox": "ant_li123@qq.com",
    //             "phone": "1441234567843543543554311143",
    //             "countryCode": "KSA",
    //             "name": "test_receiver",
    //             "company": "guangdongshengshenzhenshizhuantayigeyidia nzishiyeyouxianggongsi",
    //             "postCode": "518000",
    //             "prov": "Al Jawf"
    //         },
    //         "sender": {
    //             "area": "sdfsafdsafsdfdsa",
    //             "address": "cdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd",
    //             "town": "town1",
    //             "street": "street1",
    //             "city": "Al Ammarah",
    //             "mobile": "1441234567843543543554311143",
    //             "mailBox": "ant_li12345678901234567890@qq.com",
    //             "phone": "1441234567843543543554311143",
    //             "countryCode": "KSA",
    //             "name": "test_sender",
    //             "company": "jiangsunanjingshihonghuangzhili kejiyouxiangongsisdfdsfdsfds",
    //             "postCode": "518000",
    //             "prov": "Al Qassim"
    //         },
    //         "itemsValue": 1500.02,
    //         "priceCurrency": "AED",
    //         "width": 10,
    //         "offerFee": 3600.01,
    //         "items": [
    //             {
    //                 "number": 1,
    //                 "itemType": "ITN1",
    //                 "itemName": "服饰123456test",
    //                 "priceCurrency": "DHS",
    //                 "itemValue": "12.36",
    //                 "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
    //                 "desc": "test_ordermiaoshu"
    //             },
    //             {
    //                 "number": 1,
    //                 "itemType": "ITN1",
    //                 "itemName": "服饰123456test",
    //                 "priceCurrency": "DHS",
    //                 "itemValue": "12.36",
    //                 "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
    //                 "desc": "test_ordermiaoshu"
    //             },
    //             {
    //                 "number": 1,
    //                 "itemType": "ITN1",
    //                 "itemName": "服饰123456test",
    //                 "priceCurrency": "DHS",
    //                 "itemValue": "12.36",
    //                 "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
    //                 "desc": "test_ordermiaoshu"
    //             },
    //             {
    //                 "number": 1,
    //                 "itemType": "ITN1",
    //                 "itemName": "服饰123456test",
    //                 "priceCurrency": "DHS",
    //                 "itemValue": "12.36",
    //                 "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
    //                 "desc": "test_ordermiaoshu"
    //             },
    //             {
    //                 "number": 1,
    //                 "itemType": "ITN1",
    //                 "itemName": "服饰123456test",
    //                 "priceCurrency": "DHS",
    //                 "itemValue": "12.36",
    //                 "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
    //                 "desc": "test_ordermiaoshu"
    //             },
    //             {
    //                 "number": 1,
    //                 "itemType": "ITN1",
    //                 "itemName": "服饰123456test",
    //                 "priceCurrency": "DHS",
    //                 "itemValue": "12.36",
    //                 "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
    //                 "desc": "test_ordermiaoshu"
    //             },
    //             {
    //                 "number": 1,
    //                 "itemType": "ITN1",
    //                 "itemName": "服饰123456test",
    //                 "priceCurrency": "DHS",
    //                 "itemValue": "12.36",
    //                 "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
    //                 "desc": "test_ordermiaoshu"
    //             },
    //             {
    //                 "number": 1,
    //                 "itemType": "ITN1",
    //                 "itemName": "服饰123456test",
    //                 "priceCurrency": "DHS",
    //                 "itemValue": "12.36",
    //                 "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
    //                 "desc": "test_ordermiaoshu"
    //             },
    //             {
    //                 "number": 1,
    //                 "itemType": "ITN1",
    //                 "itemName": "服饰123456test",
    //                 "priceCurrency": "DHS",
    //                 "itemValue": "12.36",
    //                 "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
    //                 "desc": "test_ordermiaoshu"
    //             },
    //             {
    //                 "number": 1,
    //                 "itemType": "ITN1",
    //                 "itemName": "服饰123456test",
    //                 "priceCurrency": "DHS",
    //                 "itemValue": "12.36",
    //                 "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
    //                 "desc": "test_ordermiaoshu"
    //             }
    //         ],
    //         "sendEndTime": "2021-12-04 10:02:50",
    //         "height": 60,
    //         "payType": "PP_PM",
    //         "operateType": 1,
    //         "platformName": "开放13213154564646132131312sdfdsfdsfdsafdsafdsdsfdsafdsafdsfsadfsdafsdafsad",
    //         "customerAccount": "客户0086023990sdfdsfdsfdsafdsafdsafdsfsacdsfdsafdsfdsfdsafdsafsdfsdfdsafdsafdsfdsafdsa",
    //         "isUnpackEnabled": 1
    //     }',
    // ];

    //         // Make the POST request
    //         $response = Http::withHeaders($headers)->post($apiUrl, $requestData);

    // return $response;
    //         // Handle the response
    //         if ($response->successful()) {
    //             $responseData = $response->json();
    //             // Process the response data
    //             return response()->json($responseData);
    //         } else {
    //             $errorData = $response->json();
    //             // Handle the error
    //             return response()->json(['error' => $errorData], $response->status());
    //         }










    $customerNumber = '966563331605';
    $plaintextPassword = 'As140801407';
    $privateKey = 'e393d0f7a1d0499d84bb0b31eeb6aa6a';

    // Create the ciphertext by MD5 hashing the plaintext password and 'jadada236t2'
    $ciphertextMd5 = strtoupper(md5($plaintextPassword . 'jadada236t2'));

    // Create the digest by concatenating customer number, ciphertext, and private key
    // Then MD5 hash this concatenation and encode in Base64
    $digestMd5 = base64_encode(md5($customerNumber . $ciphertextMd5 . $privateKey, true));


    $apiAccount = "621732773484298266";
    $jtExpressUrl = env('JT_EXPRESS_CREATE_ORDER_URL');
    $sender = [
        'area'        => 'حي الزهور',
        'street'      => 'street122',
        'city'        => 'الزقازيق',
        'mobile'      => '1441234567843543543554311143',
        'mailBox'     => 'ant_li12345678901234567890@qq.com',
        'phone'       => '1441234567843543543554311143',
        'countryCode' => 'MEX',
        'name'        => 'sender TEST',
        'company'     => 'company TEST',
        'postCode'    => '16880',
        'prov'        => 'الشرقية',
        'areaCode'    => '324234',
        'building'    => '13',
        'floor'       => '25',
        'flats'       => '47'
    ];



    $client = new Client();
    $serviceType = '01';
    $orderType = '2';
    $deliveryType = '04';


    $order = Order::with('orderDetails')->latest()->first();
    $shipping_address = json_decode($order->shipping_address, true);
    $items = [];
    $items_value = $order->grand_total;
    $quantity = 0;
    foreach ($order->orderDetails as $item) {
        $items[] = [
            'number' => $item->id,
            'itemName' => $item->product->name,
            'itemValue' => $item->price,
            'itemUrl' => route('product', ['slug' => $item->product->slug]),
            'desc' => $item->product->short_description
        ];
        $quantity += $item->quantity;
    }


    $data = [
        'customerCode' => "J0086024071",
        'digest' => '4hQ8qXNkuSJ8cIgJQDFFRA==',            'length' => '20',
        'sendStartTime' => '2021-12-03 10:02:50',
        'weight' => '20',
        'billCode' => strtoupper("bc-" . Str::random(6)),
        'txlogisticId' => strtoupper("tli-" . Str::random(6)),
        'totalQuantity' => $quantity,
        'receiver' => [
            'area' => 'N/A',
            'address' => $shipping_address['address'],
            'town' => '',
            'street' => '',
            'city' => $shipping_address['city'],
            'mobile' => $shipping_address['phone'],
            'mailBox' => $shipping_address['email'],
            'phone' => $shipping_address['phone'],
            'countryCode' => 'KSA',
            'name' => $shipping_address['name'],
            'company' => 'guangdongshengshenzhenshizhuantayigeyidia nzishiyeyouxianggongsi',
            'postCode' => '518000',
            'prov' => 'Al Jawf'
        ],
        'itemsValue' => $items_value,
        'width' => '23',
        'items' => $items,
        'sendEndTime' => '2021-12-05 10:02:50',
        'height' => '10',
    ];


    $baseData = [
        'serviceType' => $serviceType,
        'orderType' => $orderType,
        'deliveryType' => $deliveryType,
        'expressType' => "UAE",
        'network' => '',
        'batchNumber' => '',
        'goodsType' => 'ITN1',
        'sender' => $sender,
        'priceCurrency' => "DHS",
        'payType' => 'PP_PM',
        'operateType' => 1,
        'platformName' => "开放",
        'isUnpackEnabled' => 1,
    ];

    $bizContentArray = array_merge($data, $baseData);
    $bizContentJsonString = json_encode($bizContentArray);
    $timestamp = (string) round(microtime(true) * 1000);

    // Ensure bizContent is a string within the JSON body
    $requestData = [
        'bizContent' => '{
        "customerCode": "J0086024071",
        "digest": "9507241ba7334189a27fe19a0e573b41",
        "serviceType": "01",
        "orderType": "2",
        "deliveryType": "04",
        "expressType": "EZKSA",
        "network": "",
        "length": 30,
        "sendStartTime": "2021-12-03 10:02:50",
        "weight": 5.01,
        "remark": "test",
        "billCode": "",
        "batchNumber": "",
        "txlogisticId": "SA67733240451",
        "goodsType": "ITN1",
        "totalQuantity": "1",
        "receiver": {
            "area": "sdfdsafdsfdsafdsa1",
            "address": "sdfsacdscdscds2a",
            "town": "",
            "street": "",
            "city": "Abu Ajram",
            "mobile": "1441234567843543543554311143",
            "mailBox": "ant_li123@qq.com",
            "phone": "1441234567843543543554311143",
            "countryCode": "KSA",
            "name": "test_receiver",
            "company": "guangdongshengshenzhenshizhuantayigeyidia nzishiyeyouxianggongsi",
            "postCode": "518000",
            "prov": "Al Jawf"
        },
        "sender": {
            "area": "sdfsafdsafsdfdsa",
            "address": "cdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd cdscdscdscdsscdscds 132132131cdscd",
            "town": "town1",
            "street": "street1",
            "city": "Al Ammarah",
            "mobile": "1441234567843543543554311143",
            "mailBox": "ant_li12345678901234567890@qq.com",
            "phone": "1441234567843543543554311143",
            "countryCode": "KSA",
            "name": "test_sender",
            "company": "jiangsunanjingshihonghuangzhili kejiyouxiangongsisdfdsfdsfds",
            "postCode": "518000",
            "prov": "Al Qassim"
        },
        "itemsValue": 1500.02,
        "priceCurrency": "AED",
        "width": 10,
        "offerFee": 3600.01,
        "items": [
            {
                "number": 1,
                "itemType": "ITN1",
                "itemName": "服饰123456test",
                "priceCurrency": "DHS",
                "itemValue": "12.36",
                "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
                "desc": "test_ordermiaoshu"
            },
            {
                "number": 1,
                "itemType": "ITN1",
                "itemName": "服饰123456test",
                "priceCurrency": "DHS",
                "itemValue": "12.36",
                "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
                "desc": "test_ordermiaoshu"
            },
            {
                "number": 1,
                "itemType": "ITN1",
                "itemName": "服饰123456test",
                "priceCurrency": "DHS",
                "itemValue": "12.36",
                "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
                "desc": "test_ordermiaoshu"
            },
            {
                "number": 1,
                "itemType": "ITN1",
                "itemName": "服饰123456test",
                "priceCurrency": "DHS",
                "itemValue": "12.36",
                "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
                "desc": "test_ordermiaoshu"
            },
            {
                "number": 1,
                "itemType": "ITN1",
                "itemName": "服饰123456test",
                "priceCurrency": "DHS",
                "itemValue": "12.36",
                "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
                "desc": "test_ordermiaoshu"
            },
            {
                "number": 1,
                "itemType": "ITN1",
                "itemName": "服饰123456test",
                "priceCurrency": "DHS",
                "itemValue": "12.36",
                "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
                "desc": "test_ordermiaoshu"
            },
            {
                "number": 1,
                "itemType": "ITN1",
                "itemName": "服饰123456test",
                "priceCurrency": "DHS",
                "itemValue": "12.36",
                "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
                "desc": "test_ordermiaoshu"
            },
            {
                "number": 1,
                "itemType": "ITN1",
                "itemName": "服饰123456test",
                "priceCurrency": "DHS",
                "itemValue": "12.36",
                "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
                "desc": "test_ordermiaoshu"
            },
            {
                "number": 1,
                "itemType": "ITN1",
                "itemName": "服饰123456test",
                "priceCurrency": "DHS",
                "itemValue": "12.36",
                "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
                "desc": "test_ordermiaoshu"
            },
            {
                "number": 1,
                "itemType": "ITN1",
                "itemName": "服饰123456test",
                "priceCurrency": "DHS",
                "itemValue": "12.36",
                "itemUrl": "http://www.baidu.com/shangpinlianjiedizhi",
                "desc": "test_ordermiaoshu"
            }
        ],
        "sendEndTime": "2021-12-04 10:02:50",
        "height": 60,
        "payType": "PP_PM",
        "operateType": 1,
        "platformName": "开放13213154564646132131312sdfdsfdsfdsafdsafdsdsfdsafdsafdsfsadfsdafsdafsad",
        "customerAccount": "客户0086023990sdfdsfdsfdsafdsafdsafdsfsacdsfdsafdsfdsfdsafdsafsdfsdfdsafdsafdsfdsafdsa",
        "isUnpackEnabled": 1
    }',
    ];
    $privateKey = 'e393d0f7a1d0499d84bb0b31eeb6aa6a';

    $jsonPayload = $requestData['bizContent'];

    // Calculate the digest key
    $digestKey = base64_encode(md5($jsonPayload . $privateKey, true));
    $headers = [
        'apiAccount' => '292508153084379141', // The API account number
        'digest' => "9507241ba7334189a27fe19a0e573b41", // The Base64-encoded MD5 digest
        'timestamp' => $timestamp, // The current timestamp in milliseconds
        'digestType' => '1', // The digest type indicating MD5
        'Content-Type' => 'application/json' // Assuming the content type is JSON
    ];


    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://demoopenapi.jtjms-sa.com/webopenplatformapi/api/order/addOrder?uuid=9507241ba7334189a27fe19a0e573b41',

        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $requestData,
        CURLOPT_HTTPHEADER => $headers,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }
});


Route::get('/mail',  function () {
    $cartsGroupedByUser = Cart::with('user')
        ->where('user_id', '!=', null)
        ->get()
        ->groupBy('user_id');
    foreach ($cartsGroupedByUser as $carts) {
        return $carts[0]['user']->id;
    }
    $user_id =  117;
    Mail::to('mh24409@gmail.com')->queue(new cartMail($user_id));
    return 'sent';
    return view('emails.invoice', compact('order'));
});
Route::group([
    'controller' => TamaraController::class
], function () {
    Route::get('/tamara', 'pay');
    Route::get('/tamara-payment-done', 'getDone');
    Route::get('/tamara-payment-failed', 'failedPayment');
    Route::get('/tamara-payment-cancel', 'getCancel');
});
Route::namespace('Payment')->group(function () {
    Route::post('edfa-callback', [Edfa3Controller::class, 'payment_callback'])->name('edfa3-callback');
});


Route::controller(DemoController::class)->group(function () {
    Route::get('/demo/cron_1', 'cron_1');
    Route::get('/demo/cron_2', 'cron_2');
    Route::get('/convert_assets', 'convert_assets');
    Route::get('/convert_category', 'convert_category');
    Route::get('/convert_tax', 'convertTaxes');
    Route::get('/insert_product_variant_forcefully', 'insert_product_variant_forcefully');
    Route::get('/update_seller_id_in_orders/{id_min}/{id_max}', 'update_seller_id_in_orders');
    Route::get('/migrate_attribute_values', 'migrate_attribute_values');
});

Route::get('/refresh-csrf', function () {
    return csrf_token();
});

// AIZ Uploader
Route::controller(AizUploadController::class)->group(function () {
    Route::post('/aiz-uploader', 'show_uploader');
    Route::post('/aiz-uploader/upload', 'upload');
    Route::get('/aiz-uploader/get_uploaded_files', 'get_uploaded_files');
    Route::post('/aiz-uploader/get_file_by_ids', 'get_preview_files');
    Route::get('/aiz-uploader/download/{id}', 'attachment_download')->name('download_attachment');
});

Auth::routes(['verify' => true]);

// Login
Route::controller(LoginController::class)->group(function () {
    Route::get('/logout', 'logout');
    Route::get('/social-login/redirect/{provider}', 'redirectToProvider')->name('social.login');
    Route::get('/social-login/{provider}/callback', 'handleProviderCallback')->name('social.callback');
    //Apple Callback
    Route::post('/apple-callback', 'handleAppleCallback');
    Route::get('/account-deletion', 'account_deletion')->name('account_delete');
});

Route::controller(VerificationController::class)->group(function () {
    Route::get('/email/resend', 'resend')->name('verification.resend');
    Route::get('/verification-confirmation/{code}', 'verification_confirmation')->name('email.verification.confirmation');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/email_change/callback', 'email_change_callback')->name('email_change.callback');
    Route::post('/password/reset/email/submit', 'reset_password_with_code')->name('password.update');
    Route::get('/users/login', 'login')->name('user.login');
    Route::get('/seller/login', 'login')->name('seller.login');
    Route::get('/deliveryboy/login', 'login')->name('deliveryboy.login');
    Route::get('/users/registration', 'registration')->name('user.registration');
    Route::post('/users/login/cart', 'cart_login')->name('cart.login.submit');
    // Route::get('/new-page', 'new_page')->name('new_page');

    //Home Page
    Route::get('/', 'index')->name('home');

    Route::post('/home/section/featured', 'load_featured_section')->name('home.section.featured');
    Route::post('/home/section/best_selling', 'load_best_selling_section')->name('home.section.best_selling');
    Route::post('/home/section/home_categories', 'load_home_categories_section')->name('home.section.home_categories');
    Route::post('/home/section/best_sellers', 'load_best_sellers_section')->name('home.section.best_sellers');

    // custom categories
    Route::get('/category/abayat/best_selling', 'abayat_best_selling');
    Route::get('/category/abayat/shal', 'abayat_shal');
    Route::get('/category/abayat/klosh', 'abayat_klosh');
    Route::get('/category/klosh/{category_slug}', 'kloshByCategory')->name('klosh.category');
    Route::get('/category/abayat/150_or_less', 'abayat_150_or_less');
    Route::get('/category/offers', 'offers');
    Route::get('/category/offers/{category_slug}', 'offersByCategory')->name('offers.category');
    Route::get('/category/abayat/summer', 'summer');
    Route::get('/category/summer/{category_slug}', 'summerByCategory')->name('summer.category');



    //category dropdown menu ajax call
    Route::post('/category/nav-element-list', 'get_category_items')->name('category.elements');
    Route::get('/category/details/{slug}', 'get_category_details')->name('category.details');

    //Flash Deal Details Page
    Route::get('/flash-deals', 'all_flash_deals')->name('flash-deals');
    Route::get('/flash-deal/{slug}', 'flash_deal_details')->name('flash-deal-details');

    //Todays Deal Details Page
    Route::get('/todays-deal', 'todays_deal')->name('todays-deal');

    Route::get('/product/{slug}', 'product')->name('product');
    Route::post('/product/variant_price', 'variant_price')->name('products.variant_price');
    Route::get('/shop/{slug}', 'shop')->name('shop.visit');
    Route::get('/shop/{slug}/{type}', 'filter_shop')->name('shop.visit.type');

    Route::get('/customer-packages', 'premium_package_index')->name('customer_packages_list_show');

    Route::get('/brands', 'all_brands')->name('brands.all');
    Route::get('/categories', 'all_categories')->name('categories.all');
    Route::get('/sellers', 'all_seller')->name('sellers');
    Route::get('/coupons', 'all_coupons')->name('coupons.all');
    Route::get('/inhouse', 'inhouse_products')->name('inhouse.all');


    // Policies
    Route::get('/seller-policy', 'sellerpolicy')->name('sellerpolicy');
    Route::get('/return-policy', 'returnpolicy')->name('returnpolicy');
    Route::get('/support-policy', 'supportpolicy')->name('supportpolicy');
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/aboutus', 'aboutus')->name('aboutus');
    Route::get('/privacy-policy', 'privacypolicy')->name('privacypolicy');
    Route::get('/contact-us', 'contactus')->name('contactus');

    Route::get('/track-your-order', 'trackOrder')->name('orders.track');
    Route::get('/common/questions', 'common_questions')->name('common_questions');


});

// Language Switch
Route::post('/language', [LanguageController::class, 'changeLanguage'])->name('language.change');

// Currency Switch
Route::post('/currency', [CurrencyController::class, 'changeCurrency'])->name('currency.change');


Route::get('/sitemap.xml', function () {
    return base_path('sitemap.xml');
});

// Classified Product
Route::controller(CustomerProductController::class)->group(function () {
    Route::get('/customer-products', 'customer_products_listing')->name('customer.products');
    Route::get('/customer-products?category={category_slug}', 'search')->name('customer_products.category');
    Route::get('/customer-products?city={city_id}', 'search')->name('customer_products.city');
    Route::get('/customer-products?q={search}', 'search')->name('customer_products.search');
    Route::get('/customer-product/{slug}', 'customer_product')->name('customer.product');
});

// Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'index')->name('search');
    Route::get('/search?keyword={search}', 'index')->name('suggestion.search');
    Route::post('/ajax-search', 'ajax_search')->name('search.ajax');
    Route::get('/category/{category_slug}', 'listingByCategory')->name('products.category');
    Route::get('/brand/{brand_slug}', 'listingByBrand')->name('products.brand');
});

// Cart
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->middleware(['customer', 'verified', 'unbanned'])->name('cart');
    Route::post('/cart/show-cart-modal', 'showCartModal')->name('cart.showCartModal');
    Route::post('/cart/addtocart', 'addToCart')->name('cart.addToCart');
    Route::post('/cart/removeFromCart', 'removeFromCart')->name('cart.removeFromCart');
    Route::post('/cart/updateQuantity', 'updateQuantity')->name('cart.updateQuantity');
});

//Paypal START
Route::controller(PaypalController::class)->group(function () {
    Route::get('/paypal/payment/done', 'getDone')->name('payment.done');
    Route::get('/paypal/payment/cancel', 'getCancel')->name('payment.cancel');
});


//Paymob START
Route::controller(PayMobController::class)->group(function () {
    Route::get('/paymob/payment/start', 'pay');
    Route::get('/paymob/payment/callback', 'callback')->name('payment.done');
});


//Mercadopago START
Route::controller(MercadopagoController::class)->group(function () {
    Route::any('/mercadopago/payment/done', 'paymentstatus')->name('mercadopago.done');
    Route::any('/mercadopago/payment/cancel', 'callback')->name('mercadopago.cancel');
});
//Mercadopago

// SSLCOMMERZ Start
Route::controller(SslcommerzController::class)->group(function () {
    Route::get('/sslcommerz/pay', 'index');
    Route::POST('/sslcommerz/success', 'success');
    Route::POST('/sslcommerz/fail', 'fail');
    Route::POST('/sslcommerz/cancel', 'cancel');
    Route::POST('/sslcommerz/ipn', 'ipn');
});
//SSLCOMMERZ END

//Stipe Start
Route::controller(StripeController::class)->group(function () {
    Route::get('stripe', 'stripe');
    Route::post('/stripe/create-checkout-session', 'create_checkout_session')->name('stripe.get_token');
    Route::any('/stripe/payment/callback', 'callback')->name('stripe.callback');
    Route::get('/stripe/success', 'success')->name('stripe.success');
    Route::get('/stripe/cancel', 'cancel')->name('stripe.cancel');
});
//Stripe END

// Compare
Route::controller(CompareController::class)->group(function () {
    Route::get('/compare', 'index')->name('compare');
    Route::get('/compare/reset', 'reset')->name('compare.reset');
    Route::post('/compare/addToCompare', 'addToCompare')->name('compare.addToCompare');
});

// Subscribe
Route::resource('subscribers', SubscriberController::class);

Route::group(['middleware' => ['user', 'verified', 'unbanned']], function () {

    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/new-user-verification', 'new_verify')->name('user.new.verify');
        Route::post('/new-user-email', 'update_email')->name('user.change.email');
        Route::post('/user/update-profile', 'userProfileUpdate')->name('user.profile.update');
    });

    Route::get('/all-notifications', [NotificationController::class, 'index'])->name('all-notifications');
});

Route::group(['middleware' => ['customer', 'verified', 'unbanned']], function () {

    // Checkout Routs

    // Purchase History
    Route::resource('purchase_history', PurchaseHistoryController::class);
    Route::controller(PurchaseHistoryController::class)->group(function () {
        Route::get('/purchase_history/details/{id}', 'purchase_history_details')->name('purchase_history.details');
        Route::get('/purchase_history/destroy/{id}', 'order_cancel')->name('purchase_history.destroy');
        Route::get('digital-purchase-history', 'digital_index')->name('digital_purchase_history.index');
        Route::get('/digital-products/download/{id}', 'download')->name('digital-products.download');
    });

    // Wishlist
    Route::resource('wishlists', WishlistController::class);
    Route::post('/wishlists/remove', [WishlistController::class, 'remove'])->name('wishlists.remove');

    //Follow
    Route::controller(FollowSellerController::class)->group(function () {
        Route::get('/followed-seller', 'index')->name('followed_seller');
        Route::get('/followed-seller/store', [FollowSellerController::class, 'store'])->name('followed_seller.store');
        Route::get('/followed-seller/remove', [FollowSellerController::class, 'remove'])->name('followed_seller.remove');
    });

    // Wallet
    Route::controller(WalletController::class)->group(function () {
        Route::get('/wallet', 'index')->name('wallet.index');
        Route::post('/recharge', 'recharge')->name('wallet.recharge');
    });

    // Support Ticket
    Route::resource('support_ticket', SupportTicketController::class);
    Route::post('support_ticket/reply', [SupportTicketController::class, 'seller_store'])->name('support_ticket.seller_store');

    // Customer Package
    Route::post('/customer_packages/purchase', [CustomerPackageController::class, 'purchase_package'])->name('customer_packages.purchase');

    // Customer Product
    Route::resource('customer_products', CustomerProductController::class);
    Route::controller(CustomerProductController::class)->group(function () {
        Route::get('/customer_products/{id}/edit', 'edit')->name('customer_products.edit');
        Route::post('/customer_products/published', 'updatePublished')->name('customer_products.published');
        Route::post('/customer_products/status', 'updateStatus')->name('customer_products.update.status');
        Route::get('/customer_products/destroy/{id}', 'destroy')->name('customer_products.destroy');
    });

    // Product Review
    Route::post('/product_review_modal', [ReviewController::class, 'product_review_modal'])->name('product_review_modal');
});

Route::group(['prefix' => 'checkout'], function () {
    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/', 'get_shipping_info')->middleware(['customer', 'verified', 'unbanned'])->name('checkout.shipping_info');
        Route::post('/register_for_order', 'register_for_order')->name('checkout.new_user');
        Route::post('/delivery_info', 'store_shipping_info')->name('checkout.store_shipping_infostore');
        Route::post('/payment_select', 'store_delivery_info')->name('checkout.store_delivery_info');


        Route::get('/get_edfa_status/{order_id}', 'get_edfa_status')->name('get_edfa_status');

        Route::get('/edfa_failed/{order_id}', 'edfa_failed')->name('edfa_failed');


        Route::get('/order-confirmed/{code}', 'order_confirmed')->name('order_confirmed');
        Route::post('/payment', 'checkout')->name('payment.checkout');
        Route::post('/get_pick_up_points', 'get_pick_up_points')->name('shipping_info.get_pick_up_points');
        Route::get('/payment-select', 'get_payment_info')->name('checkout.payment_info');
        Route::post('/apply_coupon_code', 'apply_coupon_code')->name('checkout.apply_coupon_code');
        Route::get('/apply_coupon_code_checkout/{code}', 'apply_coupon_code_checkout')->name('checkout.apply_coupon_code_checkout');
        Route::get('/remove_coupon_code_checkout/{code}', 'remove_coupon_code_checkout')->name('checkout.remove_coupon_code_checkout');
        Route::post('/remove_coupon_code', 'remove_coupon_code')->name('checkout.remove_coupon_code');
        //Club point
        Route::post('/apply-club-point', 'apply_club_point')->name('checkout.apply_club_point');
        Route::post('/remove-club-point', 'remove_club_point')->name('checkout.remove_club_point');

        // device type
        Route::get('/return-mobile-checkout', 'return_mobile_checkout')->name('checkout.return_mobile_checkout');
        Route::get('/return-desktop-checkout', 'return_desktop_checkout')->name('checkout.return_desktop_checkout');

        Route::get('/check_auth_verify', 'check_auth_verify')->name('checkout.check_auth_verify');
        

    });
});

Route::get('translation-check/{check}', [LanguageController::class, 'get_translation']);
Route::resource('addresses', AddressController::class);
Route::controller(AddressController::class)->group(function () {
    Route::post('/get-states', 'getStates')->name('get-state');
    Route::post('/get-cities', 'getCities')->name('get-city');
    Route::post('/check-state-to-jana', 'checkStateToJana')->name('check-state-to-jana');
    Route::post('/addresses/update/{id}', 'update')->name('addresses.update');
    Route::post('update/addresses/popup', 'updateAddressPopup')->name('addresses.updateAddressPopup');
    Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
    Route::post('/addresses/set_default', 'set_default')->name('addresses.set_default');

    Route::post('/render-address-inputs', 'render_address_input')->name('addresses.render_address_input');

    Route::post('/store_address_fron_checkout', 'store_address_fron_checkout')->name('addresses.store_address_fron_checkout');

    Route::post('/render_address_to_checkout', 'render_address_to_checkout')->name('addresses.render_address_to_checkout');
    Route::post('update/addresses/checkout', 'updateFromCheckout')->name('addresses.updateFromCheckout');


});

Route::group(['middleware' => ['auth']], function () {
    // Route::get('invoice/{order_id}', [InvoiceController::class, 'invoice'])->name('invoice.download');
    Route::get('invoic_download/{order_id}', [InvoiceController::class, 'invoice_download'])->name('invoice_download');


    // Reviews
    Route::resource('/reviews', ReviewController::class);

    // Product Conversation
    Route::resource('conversations', ConversationController::class);
    Route::controller(ConversationController::class)->group(function () {
        Route::get('/conversations/destroy/{id}', 'destroy')->name('conversations.destroy');
        Route::post('conversations/refresh', 'refresh')->name('conversations.refresh');
    });

    // Product Query
    Route::resource('product-queries', ProductQueryController::class);

    Route::resource('messages', MessageController::class);
});

Route::resource('shops', ShopController::class);

Route::get('/instamojo/payment/pay-success', [InstamojoController::class, 'success'])->name('instamojo.success');

Route::post('rozer/payment/pay-success', [RazorpayController::class, 'payment'])->name('payment.rozer');

Route::get('/paystack/payment/callback', [PaystackController::class, 'handleGatewayCallback']);
Route::get('/paystack/new-callback', [PaystackController::class, 'paystackNewCallback']);

Route::controller(VoguepayController::class)->group(function () {
    Route::get('/vogue-pay', 'showForm');
    Route::get('/vogue-pay/success/{id}', 'paymentSuccess');
    Route::get('/vogue-pay/failure/{id}', 'paymentFailure');
});


//Iyzico
Route::any('/iyzico/payment/callback/{payment_type}/{amount?}/{payment_method?}/{combined_order_id?}/{customer_package_id?}/{seller_package_id?}', [IyzicoController::class, 'callback'])->name('iyzico.callback');

Route::get('/customer-products/admin', [IyzicoController::class, 'initPayment'])->name('profile.edit');

//payhere below
Route::controller(PayhereController::class)->group(function () {
    Route::get('/payhere/checkout/testing', 'checkout_testing')->name('payhere.checkout.testing');
    Route::get('/payhere/wallet/testing', 'wallet_testing')->name('payhere.checkout.testing');
    Route::get('/payhere/customer_package/testing', 'customer_package_testing')->name('payhere.customer_package.testing');

    Route::any('/payhere/checkout/notify', 'checkout_notify')->name('payhere.checkout.notify');
    Route::any('/payhere/checkout/return', 'checkout_return')->name('payhere.checkout.return');
    Route::any('/payhere/checkout/cancel', 'chekout_cancel')->name('payhere.checkout.cancel');

    Route::any('/payhere/wallet/notify', 'wallet_notify')->name('payhere.wallet.notify');
    Route::any('/payhere/wallet/return', 'wallet_return')->name('payhere.wallet.return');
    Route::any('/payhere/wallet/cancel', 'wallet_cancel')->name('payhere.wallet.cancel');

    Route::any('/payhere/seller_package_payment/notify', 'seller_package_notify')->name('payhere.seller_package_payment.notify');
    Route::any('/payhere/seller_package_payment/return', 'seller_package_payment_return')->name('payhere.seller_package_payment.return');
    Route::any('/payhere/seller_package_payment/cancel', 'seller_package_payment_cancel')->name('payhere.seller_package_payment.cancel');

    Route::any('/payhere/customer_package_payment/notify', 'customer_package_notify')->name('payhere.customer_package_payment.notify');
    Route::any('/payhere/customer_package_payment/return', 'customer_package_return')->name('payhere.customer_package_payment.return');
    Route::any('/payhere/customer_package_payment/cancel', 'customer_package_cancel')->name('payhere.customer_package_payment.cancel');
});


//N-genius
Route::controller(NgeniusController::class)->group(function () {
    Route::any('ngenius/cart_payment_callback', 'cart_payment_callback')->name('ngenius.cart_payment_callback');
    Route::any('ngenius/wallet_payment_callback', 'wallet_payment_callback')->name('ngenius.wallet_payment_callback');
    Route::any('ngenius/customer_package_payment_callback', 'customer_package_payment_callback')->name('ngenius.customer_package_payment_callback');
    Route::any('ngenius/seller_package_payment_callback', 'seller_package_payment_callback')->name('ngenius.seller_package_payment_callback');
});

Route::controller(BkashController::class)->group(function () {
    Route::get('/bkash/create-payment', 'create_payment')->name('bkash.create_payment');
    Route::get('/bkash/callback', 'callback')->name('bkash.callback');
    Route::get('/bkash/success', 'success')->name('bkash.success');
});

Route::get('/checkout-payment-detail', [StripeController::class, 'checkout_payment_detail']);

//Nagad
Route::get('/nagad/callback', [NagadController::class, 'verify'])->name('nagad.callback');

//aamarpay
Route::controller(AamarpayController::class)->group(function () {
    Route::post('/aamarpay/success', 'success')->name('aamarpay.success');
    Route::post('/aamarpay/fail', 'fail')->name('aamarpay.fail');
});

//Authorize-Net-Payment
Route::post('/dopay/online', [AuthorizenetController::class, 'handleonlinepay'])->name('dopay.online');
Route::get('/authorizenet/cardtype', [AuthorizenetController::class, 'cardType'])->name('authorizenet.cardtype');

//payku
Route::get('/payku/callback/{id}', [PaykuController::class, 'callback'])->name('payku.result');

//Blog Section
Route::controller(BlogController::class)->group(function () {
    Route::get('/blog', 'all_blog')->name('blog');
    Route::get('/blog/{slug}', 'blog_details')->name('blog.details');
});

Route::controller(PageController::class)->group(function () {
    //mobile app balnk page for webview
    Route::get('/mobile-page/{slug}', 'mobile_custom_page')->name('mobile.custom-pages');

    //Custom page
    Route::get('/{slug}', 'show_custom_page')->name('custom-pages.show_custom_page');
});
