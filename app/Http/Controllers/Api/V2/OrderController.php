<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\BusinessSetting;
use App\Models\User;
use DB;
use \App\Utility\NotificationUtility;
use App\Models\CombinedOrder;
use App\Http\Controllers\AffiliateController;

class OrderController extends Controller
{
    public function store(Request $request, $set_paid = false)
    {
        if(get_setting('minimum_order_amount_check') == 1){
            $subtotal = 0;
            foreach (Cart::where('user_id', auth()->user()->id)->get() as $key => $cartItem){
                $product = Product::find($cartItem['product_id']);
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
            }
            if ($subtotal < get_setting('minimum_order_amount')) {
                return $this->failed("You order amount is less then the minimum order amount");
            }
        }
        $cartItems = Cart::where('user_id', auth()->user()->id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'combined_order_id' => 0,
                'result' => false,
                'message' => translate('Cart is Empty')
            ]);
        }
        $user = User::find(auth()->user()->id);
        $address = Address::where('id', $cartItems->first()->address_id)->first();
        $shippingAddress = [];
        if ($address != null) {
            $shippingAddress['name']        = $user->name;
            $shippingAddress['email']       = $user->email;
            $shippingAddress['address']     = $address->address;
            $shippingAddress['country']     = $address->country->name;
            $shippingAddress['state']       = $address->state->name;
            $shippingAddress['shipping_state'] = $address->state->shipping_name;
            $shippingAddress['city']        = $address->city->name;
            $shippingAddress['shipping_city'] = $address->city->shipping_name;
            $shippingAddress['postal_code'] = $address->postal_code;
            $shippingAddress['phone']       = $address->phone;
            if ($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
            }
        }
        $combined_order = new CombinedOrder;
        $combined_order->user_id = $user->id;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();
        $seller_products = array();
        foreach ($cartItems as $cartItem) {
            $product_ids = array();
            $product = Product::find($cartItem['product_id']);
            if (isset($seller_products[$product->user_id])) {
                $product_ids = $seller_products[$product->user_id];
            }
            array_push($product_ids, $cartItem);
            $seller_products[$product->user_id] = $product_ids;
        }

        foreach ($seller_products as $seller_product) {
            $order = new Order;
            $order->combined_order_id = $combined_order->id;
            $order->user_id = $user->id;
            $order->shipping_address = $combined_order->shipping_address;

            $order->payment_type = $request->payment_type;
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');
            if($set_paid){
                $order->payment_status = 'paid';
            }else{
                $order->payment_status = 'unpaid';
            }

            $order->save();

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;

            //Order Details Storing
            foreach ($seller_product as $cartItem) {
                $product = Product::find($cartItem['product_id']);

                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax += cart_product_tax($cartItem, $product,false) * $cartItem['quantity'];
                $coupon_discount += $cartItem['discount'];

                $product_variation = $cartItem['variation'];

                $product_stock = $product->stocks->where('variant', $product_variation)->first();
                if ($product->digital != 1 && $cartItem['quantity'] > $product_stock->qty) {
                    $order->delete();
                    $combined_order->delete();
                    return response()->json([
                        'combined_order_id' => 0,
                        'result' => false,
                        'message' => translate('The requested quantity is not available for ') . $product->name
                    ]);
                } elseif ($product->digital != 1) {
                    $product_stock->qty -= $cartItem['quantity'];
                    $product_stock->save();
                }

                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->price = cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $order_detail->tax = cart_product_tax($cartItem, $product,false) * $cartItem['quantity'];
                $order_detail->shipping_type = $cartItem['shipping_type'];
                $order_detail->product_referral_code = $cartItem['product_referral_code'];
                $order_detail->shipping_cost = $cartItem['shipping_cost'];

                $shipping += $order_detail->shipping_cost;
                if (addon_is_activated('club_point')) {
                    $order_detail->earn_point = $product->earn_point;
                }

                $order_detail->quantity = $cartItem['quantity'];
                $order_detail->save();

                $product->num_of_sale = $product->num_of_sale + $cartItem['quantity'];
                $product->save();

                $order->seller_id = $product->user_id;
                //======== Added By Kiron ==========
                $order->shipping_type = $cartItem['shipping_type'];


                if ($cartItem['shipping_type'] == 'pickup_point') {
                    $order->pickup_point_id = $cartItem['pickup_point'];
                }
                if ($cartItem['shipping_type'] == 'carrier') {
                    $order->carrier_id = $cartItem['carrier_id'];
                }

                if ($product->added_by == 'seller' && $product->user->seller != null){
                    $seller = $product->user->seller;
                    $seller->num_of_sale += $cartItem['quantity'];
                    $seller->save();
                }



                if (addon_is_activated('affiliate_system')) {
                    if ($order_detail->product_referral_code) {
                        $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
                    }
                }
            }
            $order->grand_total = $subtotal + $tax + $shipping;
            if ($seller_product[0]->coupon_code != null) {
                // if (Session::has('club_point')) {
                //     $order->club_point = Session::get('club_point');
                // }
                $order->coupon_discount = $coupon_discount;
                $order->grand_total -= $coupon_discount;

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = $user->id;
                $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
                $coupon_usage->save();
            }
            $combined_order->grand_total += $order->grand_total;
            if (strpos($request->payment_type, "manual_payment_") !== false) { 
                $order->manual_payment = 1;
                $order->save();
            }
            if($order->payment_type =="cash_on_delivery")
            {
                $order->grand_total = $order->grand_total + get_setting('COD_tax');
                $order->COD_tax =  get_setting('COD_tax');
            }
            $order->save();
        }
        $combined_order->save();
        if($request->from_myfatoorah != '1'){
            Cart::where('user_id', auth()->user()->id)->delete();
        }
        NotificationUtility::sendOrderPlacedNotification($order,$request);
        return response()->json([
            'combined_order_id' => $combined_order->id,
            'result' => true,
            'message' => translate('Your order has been placed successfully')
        ]);
    }



    private function shipping($id, $is_open)
    {
        $order = Order::with('orderDetails.product')->where('id', $id)->first();
        $productNames = [];
        foreach ($order->orderDetails as $orderDetail) {
            $product = $orderDetail->product;
            if ($product) {
                $productNames[] = $product->getTranslation('name');
            }
        }
        $productNamesString = implode(', ', $productNames);
        $is_open = 0;


        $shipping_address = json_decode($order->shipping_address, true);

        if($order->payment_status =="paid")
        {
            $total =0;
        }else{
            $total=$order->grand_total;
        }
        $shippingDetail=json_decode($order->shipping_address);

        // $state=State::where('name'.$shippingDetail->state)->first();
        // $governments = Shipping_Governments::where('state_id',$state->id)->first;
        $data = array(
            'authentication_key' => env('TURBO_AUTHENTICATION_KEY'),
            'main_client_code' =>  env('TURBO_CLIENT_Code'),
            'receiver' => $shipping_address['name'],
            'phone1' => $shipping_address['phone'],
            // 'government' => $governments->name,
            // 'area' => 'السيدة زينب',
            // 'address' => $shippingDetail->address,
            // 'government' => 'القاهرة'   ,
            'government' => $shipping_address['shipping_state']   ,
            'area' => $shipping_address['shipping_city'] ,
            'address' => $shippingDetail->address,
            'return_amount' => 0,
            'is_order' => 0,
            'order_summary' => $productNamesString,
            'amount_to_be_collected' => $total,
            'can_open' => $is_open,
            'notes'=>$order->additional_info,
        );
        $jsonData = json_encode($data);
        $apiUrl = env('TURBO_ADD_ORDER_URL');
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($ch);
        $responseData = json_decode($response, true);

        if ($responseData['success'] == 1) {
            $order->shipping_barcode = $responseData['result']['bar_code'];
            $order->save();
            // flash(translate('Your Been Has Been Submitted To The Shipping Company Successfully'))->success();

        } else {
            // flash(translate('Some Error Happended While Sending Order To The Shipping Company'))->warning();
        }

        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        }

        curl_close($ch);
    }
}
