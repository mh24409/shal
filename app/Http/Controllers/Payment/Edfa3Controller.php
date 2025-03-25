<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\CombinedOrder;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Log;
use App\Events\OrderEdfaStatus;

class Edfa3Controller extends Controller
{
    public $merchant_id;
    public $merchant_password;


    public function __construct()
    {
        $this->merchant_id = env('EDFA3_MERCHANT_ID');
        $this->merchant_password = env('EDFA3_MERCHANT_PASSWORD');
    }
    public function pay()
    {
        $order = null;
        if (Session::has('payment_type')) {
            if (Session::get('payment_type') == 'cart_payment') {
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                $order = $combined_order->orders->first();
                $amount = $order->grand_total;

                $shipping_address = json_decode($order->shipping_address, true);
            }
        }
        if ($order != null) {
            try {
                $ip = request()->ip();
                $orderNumber = $order->id;
                $orderAmount = $amount;
                $orderCurrency = "SAR";
                $orderDescription = "Order For Shal Store";
                $merchantPassword = $this->merchant_password;
                $toMd5 = strtoupper($orderNumber . $orderAmount . $orderCurrency . $orderDescription . $merchantPassword);
                $md5 = md5($toMd5);
                $sha1 = sha1($md5);
                $postData = [
                    'action'            => 'SALE',
                    'edfa_merchant_id'  => $this->merchant_id,
                    'order_id'          => $order->id,
                    'order_amount'      => $amount,
                    'order_currency'    => 'SAR',
                    'order_description' => $orderDescription,
                    'req_token'         => 'N',
                    'payer_first_name'  => "Mrs",
                    'payer_last_name'   => $order->user->name,
                    'payer_address'     => $shipping_address['address'],
                    'payer_country'     => 'SA',
                    'payer_city'        => $shipping_address['state'],
                    'payer_zip'         => '12221',
                    'payer_email'       =>  "shorbatli@gmail.com",
                    'payer_phone'       => $shipping_address['phone'],
                    'payer_ip'          => $ip,
                    'term_url_3ds' => route('get_edfa_status', ['order_id' => $order->id]),
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
                } else {
                    $responseObject = json_decode($response);
                    return redirect($responseObject->redirect_url);
                }
            } catch (\Exception $ex) {
                    Log::info('Error In Edfa Initiate Payment:', $ex);
                    flash(translate('Something went wrong'))->error();
                    return redirect()->route('checkout.shipping_info');
            }
        }
    }
    public function payment_callback(Request $request)
    {
        Log::info('Payment callback request data:', $request->all());
        $order = Order::find($request->order_id);
        if ($request->result == "SUCCESS") {
            $order->payment_status = "paid";
            $order->payment_details = $request;
            $order->payment_type = "edfa";
            $order->save();
        } else {
            $order->payment_status = "unpaid";
            $order->payment_details = $request;
            $order->payment_type = "edfa";
            $order->save();
        }
        sleep(5);
        event(new OrderEdfaStatus($order));
    }
}
