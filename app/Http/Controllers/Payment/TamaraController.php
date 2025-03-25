<?php

namespace App\Http\Controllers\Payment;

use Session;
use Redirect;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Models\SellerPackage;
use App\Models\CustomerPackage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CheckoutController;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use App\Http\Controllers\SellerPackageController;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use App\Http\Controllers\CustomerPackageController;

class TamaraController extends Controller
{
    public function pay()
    {
        $order = null;
        if(Session::has('payment_type')) {
            if(Session::get('payment_type') == 'cart_payment') {
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                $order = $combined_order->orders->first();
                $amount = $order->grand_total;
            }
        }
        if($order != null)
        {
            try {
                $shipping_address = json_decode($order->shipping_address,true);
                $order_reference_id = $order->code;
                $order_number = $order->code;
                $items = [];
                $items[] =
                [
                   'name' => 'order from Shall',
                   'type' => 'Physical',
                   'reference_id' => '123',
                   'sku' => 'SA-12436',
                   'quantity' => 1,
                   'discount_amount' => [
                                   'amount' => 0,
                                   'currency' => 'SAR'
                   ],
                   'tax_amount' => [
                                   'amount' => 00,
                                   'currency' => 'SAR'
                   ],
                   'unit_price' => [
                                   'amount' => $amount,
                                   'currency' => 'SAR'
                   ],
                   'total_amount' => [
                                   'amount' => $amount,
                                   'currency' => 'SAR'
                   ]
               ];
               
                $shipping_details = [
                    'city' => $shipping_address['city'] ?? 'N/A',
                    'country_code' => 'SA',
                    'first_name' => $shipping_address['name'] ?? 'N/A',
                    'last_name' => 'N/A',
                    'line1' => $shipping_address['address'] ?? 'N/A',
                    'line2' => 'N/A',
                    'phone_number' => $shipping_address['phone'] ?? 'N/A',
                    'region' => 'As Sulimaniyah'
                ];

                $consumer = [
                        'email' => 'shorbatli@gmail.com',
                        'first_name' => $shipping_address['name'] ?? 'N/A',
                        'last_name' => 'N/A',
                        // 'phone_number' => '544337766'
                        'phone_number' => $shipping_address['phone'] ?? 'N/A'
                    ];

                $data = [
                    CURLOPT_URL => env('TAMARA_URL') ,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                        'total_amount' => [
                            'amount' => $amount,
                            'currency' => env('TAMARA_CURRENCY')
                        ],
                        'shipping_amount' => [
                            'amount' => 0,
                            'currency' => 'SAR'
                        ],
                        'tax_amount' => [
                            'amount' => 0,
                            'currency' => 'SAR'
                        ],
                        'order_reference_id' => $order_reference_id,
                        'order_number' => $order_number,
                        'discount' => [
                            'name' => 'Voucher A',
                            'amount' => [
                                    'amount' => 0,
                                    'currency' => 'SAR'
                            ]
                        ],
                        'items' => $items,
                        'consumer' => $consumer,
                        'country_code' => 'SA',
                        'description' => 'Order From ' . env('APP_NAME'),
                        'merchant_url' => [
                            'cancel' => env('APP_URL') . 'tamara-payment-cancel',
                            'failure' => env('APP_URL') . 'tamara-payment-failed',
                            'success' => env('APP_URL') . 'tamara-payment-done',
                            'notification' => 'https://example-notification.com/payments/tamaranotifications'
                        ],
                        'payment_type' => 'PAY_BY_INSTALMENTS',
                        'instalments' => 3,
                        'shipping_address' => $shipping_details,
                        'platform' => env('APP_NAME'),
                        'is_mobile' => false,
                        'locale' => 'ar_SA',
                    ]),
                    CURLOPT_HTTPHEADER => [
                        "accept: application/json",
                        "authorization: Bearer " . env('TAMARA_API_TOKEN'),
                        "content-type: application/json"
                    ],
                    ];


                $curl = curl_init();

                curl_setopt_array($curl, $data);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $response = json_decode($response,true);

                $order_id = $response['order_id'];

                $checkout_id = $response['checkout_id'];

                $redirect_url = $response['checkout_url'];

                return redirect($redirect_url);

                }catch (\Exception $ex) {
                    flash(translate('Something went wrong'))->error();
                    return redirect()->route('home');
                }
        }else{
            flash(translate('Something went wrong'))->error();
            return redirect()->route('home');
        }


    }


    public function getCancel(Request $request)
    {
        $request->session()->forget('order_id');
        $request->session()->forget('payment_data');
        flash(translate('Payment cancelled'))->success();
    	return redirect()->route('home');
    }

    public function failedPayment(Request $request)
    {
        // Curse and humiliate the user for cancelling this most sacred payment (yours)
        $request->session()->forget('order_id');
        $request->session()->forget('payment_data');
        flash(translate('Something went wrong'))->success();
    	return redirect()->route('home');
    }

    public function getDone(Request $request)
    {
        $payment = ["status" => "Success"];
        try {
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            if($request->session()->has('payment_type')){
                if($request->session()->get('payment_type') == 'cart_payment'){
                    return (new CheckoutController)->checkout_done(session()->get('combined_order_id'), json_encode($payment));
                }
            }
        }catch (\Exception $ex) {

        }
    }
}
