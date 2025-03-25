<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerPackageController;

// use App\Http\Controllers\Api\V2\Seller\SellerPackageController;

class PayMobController extends Controller
{
    protected $payment_token;
    protected $auth_token;
    protected $order_id;
    protected $integration_key;
    protected $payment_method;
    protected $total_amount;

    public function orderProcces()
    {
        $request = request();

        $amount = 0;
            if ($request->session()->has('payment_type')) {
                if ($request->session()->get('payment_type') == 'cart_payment') {
                    $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));

                    $client_reference_id = $combined_order->id;
                    $amount = round($combined_order->grand_total * 100);
                } elseif ($request->session()->get('payment_type') == 'wallet_payment') {
                    $amount = round($request->session()->get('payment_data')['amount'] * 100);
                    $client_reference_id = auth()->id();
                } elseif ($request->session()->get('payment_type') == 'customer_package_payment') {
                    $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
                    $amount = round($customer_package->amount * 100);
                    $client_reference_id = auth()->id();
                } elseif ($request->session()->get('payment_type') == 'seller_package_payment') {
                    $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
                    $amount = round($seller_package->amount * 100);
                    $client_reference_id = auth()->id();
                }
            }
            $this->total_amount = $amount;
            $this->payment_method = $request->paymob_option ;
            $this->auth_token = $this->getAuthToken();
            $this->order_id = $this->getOrderId($request);
            $this->integration_key = $this->setIntergrationKey($this->payment_method);
            $this->payment_token = $this->payment_response($request);
    }

    public function pay(Request $request)
    {
        $this->orderProcces();
        switch($this->payment_method){
            case 'wallet':
                $payment_url = $this->walletPaymentUrl($request);
                break;
                case 'credit_card':
                $payment_url = $this->creditCardPaymentUrl($request);
                break;
            // case 'cash':
            //     return $this->integration_key = 4343602;
            //     break;
        }
            return redirect($payment_url);
    }
    public function getAuthToken(){
          // Step 1: Authentication
          $auth_url = 'https://accept.paymob.com/api/auth/tokens';
          $headers = [
              'Content-Type' => 'application/json',
            ];

            $response = Http::withHeaders($headers)->post($auth_url, [
                'api_key' => env('PAYMOB_API_KEY'),
            ]);
          if (!isset($response->json()['token'])) {
              flash('Pay With Paymob Failed. Try Another Option')->error();
              return redirect()->route('checkout.shipping_info');
          }
          $auth_token = $response->json()['token'];
          return $auth_token;
    }
    public function setIntergrationKey($payment_method){

        if ($payment_method == null) {
            flash('Pay With Paymob Failed. Try Another Option')->error();
            return redirect()->route('checkout.shipping_info');
        }

        switch($payment_method){
            case 'wallet':
                return 3124052;

                break;
            case 'credit_card':
                return  3124050;
                break;
            default:
                flash('Pay With Paymob Failed. Wrong Payment Method')->error();
                return redirect()->route('checkout.shipping_info');
        }
    }
    public function getOrderId($request){
            $order_data = [
                'auth_token' => $this->auth_token,
                'delivery_needed' => 'false',
                'amount_cents' => $this->total_amount ,
                'currency' => strtoupper(\App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code),
                'items' => [
                    [
                        'name' => 'Shall Order',
                        'amount_cents' => $this->total_amount,
                        'description' => 'Order From Shall Website',
                        'quantity' => '1',
                    ]
                ]
            ];

            $order_url = 'https://accept.paymob.com/api/ecommerce/orders';
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($order_url, $order_data);
            if (!$response->successful()) {
                flash('Pay With Paymob Failed. Try Another Option')->error();
                return redirect()->route('checkout.shipping_info');
            }
            $order_data = $response->json();
            $order_id = $order_data['id'];
            return $order_id;
    }
    public function payment_response(Request $request)
    {

        $payment_token_url = 'https://accept.paymob.com/api/acceptance/payment_keys';
        $payment_data = [
            'auth_token' => $this->auth_token,
            'amount_cents' => $this->total_amount,
            'expiration' => 3600,
            'order_id' => $this->order_id,
            'billing_data' => [
                'apartment' => 'NA',
                     'email' => $request->email,
                     'floor' => 'NA',
                     'first_name' => $request->name,
                     'street' => 'NA',
                     'building' => 'NA',
                     'phone_number' => $request->phone,
                     'postal_code' => 'NA',
                     'extra_description' => 'NA',
                     'city' => 'Jaskolskiburgh',
                     'country' => 'CR',
                     'last_name' => $request->name,
                     'state' => 'Utah',
            ],
            'currency' => strtoupper(\App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code),
            'integration_id' => $this->integration_key,
        ];
        $response = Http::withHeaders(['Content-Type' => 'application/json'])->post($payment_token_url, $payment_data);
        if (!$response->successful() || !isset($response->json()['token'])) {
            flash('Failed to generate payment token: ' . $response->body())->error();
           return redirect()->route('checkout.shipping_info');
        }

        return $response->json()['token'];
    }
    public function creditCardPaymentUrl()
    {
        $payment_url = 'https://accept.paymobsolutions.com/api/acceptance/iframes/' .  702426 . '?payment_token=' . $this->payment_token;
        return $payment_url;
    }
    public function walletPaymentUrl(Request $request){
        $payment_token_url = 'https://accept.paymob.com/api/acceptance/payments/pay';
        $payment_data = [
            'source' => [
                'identifier' => $request->phone,
                'subtype' => 'WALLET',
            ],
            'payment_token' => $this->payment_token
        ];
         $response = Http::withHeaders(['Content-Type' => 'application/json'])->post($payment_token_url, $payment_data);
         if (!$response->successful() || !isset($response->json()['redirect_url'])) {
             flash('Failed to generate payment token: ' . $response->body())->error();
            return redirect()->route('checkout.shipping_info');
         }
        $payment_url = $response->json()['redirect_url'];

        return $payment_url;
    }
    public function callback(Request $request)
    {
        $data = $request->all();
        ksort($data);
        $hmac = $data['hmac'];
        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];
        $connectedString = '';
        foreach ($data as $key => $element) {
            if(in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
        $response = $data;
        if($request->session()->has('payment_type')){
            if($request->session()->get('payment_type') == 'cart_payment'){
                return (new CheckoutController)->checkout_done($request->session()->get('combined_order_id'), json_encode($response));
            }
            elseif ($request->session()->get('payment_type') == 'wallet_payment') {
                return (new WalletController)->wallet_payment_done($request->session()->get('payment_data'), json_encode($response));
            }
            elseif ($request->session()->get('payment_type') == 'customer_package_payment') {
                return (new CustomerPackageController)->purchase_payment_done($request->session()->get('payment_data'), json_encode($response));
            }
            elseif ($request->session()->get('payment_type') == 'seller_package_payment') {
                return (new SellerPackageController)->purchase_payment_done($request->session()->get('payment_data'), json_encode($response));
            }
        }
        $secret = env('PAYMOB_HMAC');
        $hased = hash_hmac('sha512', $connectedString, $secret);
        if ( $hased == $hmac) {
            echo "secure" ; exit;
        }
        echo 'not secure'; exit;
    }
}
