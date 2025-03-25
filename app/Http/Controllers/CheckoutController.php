<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Carrier;
use App\Models\Product;
use App\Models\Category;
use App\Models\CouponUsage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Utility\PayfastUtility;
use App\Utility\PayhereUtility;
use App\Traits\ConversionApiTrait;
use App\Traits\SnapchatConversionTrait;
use Carbon\Carbon;
use App\Utility\NotificationUtility;
use App\Http\Requests\CheckoutRequest;
use App\Http\Controllers\Payment\TamaraController;
use App\Http\Controllers\Payment\MyfatoorahController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Traits\TikTokConverasionTrait;

class CheckoutController extends Controller
{
    use ConversionApiTrait , SnapchatConversionTrait ,TikTokConverasionTrait ;
    public function __construct()
    {
        
    }
    public function all_in_one_step()
    {
        
    }
    public function get_shipping_info(Request $request)
    {

        $user = auth()->user();
        $ShouldAuthenticated = 0;
        $ShouldVerify = 0;
        $CanOrdered = 0;
        $AutoOrdered = 0;


        if ($user == null) {
            $ShouldAuthenticated = 1;
        }
        if ($user && $user->is_verified == 0) {
            $ShouldVerify = 1;
        }
        if ($user && $user->is_verified == 1) {
            $CanOrdered = 1;
        }
        if ($user != null && $user->email_verified_at != null && $user->is_verified == 1) {
            $verificationTime = Carbon::parse($user->email_verified_at);
            $currentTime = Carbon::now();
            if ($currentTime->diffInMinutes($verificationTime) <= 1) {
                $AutoOrdered = 1;
            }
        }


        $shipping_info = null;
        $temp_user_id = $request->session()->get('temp_user_id');
        if (Auth::check()) {
            $carts = Cart::where('user_id', auth()->user()->id)->get();
        } else {
            $carts = Cart::where('temp_user_id', $temp_user_id)->where('user_id', null)->get();
        }
        $user = Address::where('temp_user_id', $temp_user_id)->get();
        $pixel_event_id = Str::random(30);
        if ($carts && count($carts) > 0) {
            $categories = Category::all();
            $delivery_info_status = false;
            $payment_select_status = false;
            $total = $carts->sum('price');
            if (get_setting('facebook_converasion_api')  == 1) {
                ConversionApiTrait::InitiateCheckout($carts, $pixel_event_id);
            }
            if (get_setting('snapchat_converasion_api')  == 1) {
                SnapchatConversionTrait::SnapChatInitiateCheckout($carts, $pixel_event_id);
            }
            foreach ($carts as $cart) {
                $name = Product::find($cart->product_id)->name;
                $cart->product_name = $name . "_" . $cart->variation;
            }
            return view('frontend.shipping_info', compact('shipping_info', 'pixel_event_id', 'payment_select_status', 'delivery_info_status', 'categories', 'carts', 'user', 'total', 'ShouldAuthenticated', 'ShouldVerify', 'CanOrdered', 'AutoOrdered'));
        }
        flash(translate('Your cart is empty'))->success();
        return back();
    }
    public function store_shipping_info(Request $request)
    {
        if (!$request->state_id || !$request->city_id || !$request->country_id) {
            flash(translate("make sure to select address info correctly"))->warning();
            return back();
        }
        $temp_user_id = $request->session()->get('temp_user_id');

        $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }


        $carrier_list = array();
        if (get_setting('shipping_type') == 'carrier_wise_shipping') {
            $zone = \App\Models\Country::where('id', $request->country_id)->first()->zone_id;

            $carrier_query = Carrier::query();
            $carrier_query->whereIn('id', function ($query) use ($zone) {
                $query->select('carrier_id')->from('carrier_range_prices')
                    ->where('zone_id', $zone);
            })->orWhere('free_shipping', 1);
            $carrier_list = $carrier_query->get();
        }
        if (Auth::check()) {
            $carts = Cart::where('user_id', auth()->user()->id)->get();
        } else {
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        if ($carts->isEmpty()) {
            return response()->json([
                'message' => 'your cart is empty'
            ]);
        }
        $shipping_info = [
            'city' => $request->city
        ];
        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;
        $shipping_details = [
            'city_id' => $request->city_id,
            'country_id' => $request->country_id,
        ];
        $carts_count = count($carts);
        if ($carts && $carts_count > 0) {
            foreach ($carts as $key => $cartItem) {
                $product = Product::find($cartItem['product_id']);
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                if (get_setting('shipping_type') != 'carrier_wise_shipping' || $request['shipping_type_' . $product->user_id] == 'pickup_point') {
                    if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                        $cartItem['shipping_type'] = 'pickup_point';
                        $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                    } else {
                        $cartItem['shipping_type'] = 'home_delivery';
                    }
                    $cartItem['shipping_cost'] = 0;
                    if ($cartItem['shipping_type'] == 'home_delivery') {
                        $cartItem['shipping_cost'] = getShippingCost($carts, $key, '', $shipping_details);
                    }
                } else {
                    $cartItem['shipping_type'] = 'carrier';
                    $cartItem['carrier_id'] = $request['carrier_id_' . $product->user_id];
                    $cartItem['shipping_cost'] = getShippingCost($carts, $key, $cartItem['carrier_id'], $shipping_details);
                }

                $shipping += $cartItem['shipping_cost'];
                $cartItem->save();
            }
            $total = $subtotal + $tax + $shipping;
            $payment_select_status = true;
            $html = view('frontend.partials.cart_summary', compact('carts', 'shipping_info'))->render();
            $htmlMobile = view('frontend.checkout_one_Step.cart_summary_for_mobile', compact('carts', 'shipping_info'))->render();
            return  [$html, $htmlMobile];
        } else {
            flash(translate('Your Cart was empty'))->warning();
            return redirect()->route('home');
        }
    }


    // 3 $request -> shipping_type  [ get_setting('shipping_type') , 'shipping_type_' . $product->user_id ]
    //return payment_select view with carts and shipping info that is  address
    public function store_delivery_info(Request $request)
    {

        $temp_user_id = $request->session()->get('temp_user_id');
        if (Auth::check()) {
            $carts = Cart::where('user_id', auth()->user()->id)->get();
        } else {
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }
        $shipping_info = [
            'city' => $request->city
        ];
        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;

        if ($carts && count($carts) > 0) {
            foreach ($carts as $key => $cartItem) {
                $product = Product::find($cartItem['product_id']);
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                if (get_setting('shipping_type') != 'carrier_wise_shipping' || $request['shipping_type_' . $product->user_id] == 'pickup_point') {
                    if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                        $cartItem['shipping_type'] = 'pickup_point';
                        $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                    } else {
                        $cartItem['shipping_type'] = 'home_delivery';
                    }
                    $cartItem['shipping_cost'] = 0;
                    if ($cartItem['shipping_type'] == 'home_delivery') {
                        $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                    }
                } else {
                    $cartItem['shipping_type'] = 'carrier';
                    $cartItem['carrier_id'] = $request['carrier_id_' . $product->user_id];
                    $cartItem['shipping_cost'] = getShippingCost($carts, $key, $cartItem['carrier_id']);
                }

                $shipping += $cartItem['shipping_cost'];
                $cartItem->save();
            }
            $total = $subtotal + $tax + $shipping;
            $payment_select_status = true;
            $cartSummeryHTML = view('frontend.partials.cart_summary', compact('carts', 'shipping_info'))->render();
            $cartSummeryHTMLMobile = view('frontend.checkout_one_Step.cart_summary_for_mobile', compact('carts', 'shipping_info'))->render();
            return response()->json(array('cartHTML' => $cartSummeryHTML, 'cartHTMLMobile' => $cartSummeryHTMLMobile));
        } else {
            flash(translate('Your Cart was empty'))->warning();
            return redirect()->route('home');
        }
    }
    public function checkout(CheckoutRequest $request)
    {
        if ($request->shipping_company == 4) {
            $jana_city = DB::table('jana_cities')->where('shipping_id', $request->state_id)->first();
            $jana_city_id = $jana_city->city_id;
            $request->merge(['jana_city_id' => $jana_city_id]);
        };
        if (get_setting('minimum_order_amount_check') == 1) {
            $subtotal = 0;
            foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem) {
                $product = Product::find($cartItem['product_id']);
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
            }
            if ($subtotal < get_setting('minimum_order_amount')) {
                flash(translate('You order amount is less then the minimum order amount'))->warning();
                return redirect()->route('home');
            }
        }
        if (Coupon::where('code', Cart::where('user_id', Auth::user()->id)->first()->coupon_code)->first() && !allowed_to_use_coupon(Coupon::where('code', Cart::where('user_id', Auth::user()->id)->first()->coupon_code)->first(), Auth::user()->id)) {
            foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem) {
                $cartItem->coupon_code = null;
                $cartItem->coupon_applied = null;
            }
            flash(translate('Your coupon has expired'))->warning();
            return redirect()->route('checkout.shipping_info');
        }
        if ($request->payment_option != null) {
            (new OrderController)->store($request);
            $request->session()->put('payment_type', 'cart_payment');
            $data['combined_order_id'] = $request->session()->get('combined_order_id');
            $request->session()->put('payment_data', $data);
            if ($request->session()->get('combined_order_id') != null) {
                if ($request->payment_option == 'tamara') {
                    return (new TamaraController)->pay($request);
                } elseif ($request->payment_option == 'myfatoorah') {
                    return (new MyfatoorahController)->pay($request);
                } elseif ($request->payment_option == 'edfa3') {
                    return (new Payment\Edfa3Controller)->pay($request);
                } else {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                    $manual_payment_data = array(
                        'name' => $request->payment_option,
                        'amount' => $combined_order->grand_total,
                        'trx_id' => $request->trx_id,
                        'photo' => $request->photo
                    );
                    foreach ($combined_order->orders as $order) {
                        $order->manual_payment = 1;
                        $order->manual_payment_data = json_encode($manual_payment_data);
                        $order->save();
                    }
                    flash(translate('Your order has been placed successfully. Please submit payment information from purchase history'))->success();
                    return redirect()->route('order_confirmed', ['code' => $order->code]);
                }
            } else {
                flash(translate('Select Payment Option.'))->warning();
                return back();
            }
        }
    }
    public function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::findOrFail($combined_order_id);
        foreach ($combined_order->orders as $key => $order) {
            $order = Order::findOrFail($order->id);
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();
            calculateCommissionAffilationClubPoint($order);
            Session::put('combined_order_id', $combined_order_id);
            return redirect()->route('order_confirmed', ['code' => $order->code]);
        }
    }
    public function apply_coupon_code(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        $response_message = array();
        $temp_user_id = $request->session()->get('temp_user_id');
        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date || $coupon->lifetime == 1) {
                if (allowed_to_use_coupon($coupon, Auth::check() ? auth()->user()->id : $temp_user_id)) {
                    $coupon_details = json_decode($coupon->details);
                    if (Auth::check()) {
                        $carts = Cart::where('user_id', auth()->user()->id)
                            ->where('owner_id', $coupon->user_id)
                            ->get();
                    } else {
                        $carts = Cart::where('temp_user_id', $temp_user_id)
                            ->where('owner_id', $coupon->user_id)
                            ->get();
                    }
                    $coupon_discount = 0;
                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                            $shipping += $cartItem['shipping_cost'];
                        }
                        $sum = $subtotal + $tax + $shipping;
                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                        }
                    } elseif ($coupon->type == 'product_base') {
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += (cart_product_price($cartItem, $product, false, false) * $coupon->discount / 100) * $cartItem['quantity'];
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                    }
                                }
                            }
                        }
                    }
                    if ($coupon_discount > 0) {
                        if (Auth::check()) {
                            Cart::where('user_id', auth()->user()->id)
                                ->where('owner_id', $coupon->user_id)
                                ->update(
                                    [
                                        'discount' => $coupon_discount / count($carts),
                                        'coupon_code' => $request->code,
                                        'coupon_applied' => 1
                                    ]
                                );
                        } else {
                            Cart::where('temp_user_id', $temp_user_id)
                                ->where('owner_id', $coupon->user_id)
                                ->update(
                                    [
                                        'discount' => $coupon_discount / count($carts),
                                        'coupon_code' => $request->code,
                                        'coupon_applied' => 1
                                    ]
                                );
                        }

                        $response_message['response'] = 'success';
                        $response_message['message'] = translate('Coupon has been applied');
                    } else {
                        $response_message['response'] = 'warning';
                        $response_message['message'] = translate('This coupon is not applicable to your cart products!');
                    }
                } else {
                    $response_message['response'] = 'warning';
                    $response_message['message'] = translate('You already used this coupon!');
                }
            } else {
                $response_message['response'] = 'warning';
                $response_message['message'] = translate('Coupon expired!');
            }
        } else {
            $response_message['response'] = 'danger';
            $response_message['message'] = translate('Invalid coupon!');
        }

        if (Auth::check()) {
            $carts = Cart::where('user_id', auth()->user()->id)
                ->get();
        } else {
            $carts = Cart::where('temp_user_id', $temp_user_id)
                ->get();
        }
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $returnHTML = view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();
        $cartSummeryHTMLMobile = view('frontend.checkout_one_Step.cart_summary_for_mobile', compact('coupon', 'carts', 'shipping_info'))->render();
        return response()->json(array('response_message' => $response_message, 'html' => $returnHTML, 'htmlMobile' => $cartSummeryHTMLMobile));
    }
    public function apply_coupon_code_checkout(Request $request, $code)
    {
        $coupon = Coupon::where('code', $code)->first();
        $temp_user_id = $request->session()->get('temp_user_id');
        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date || $coupon->lifetime == 1) {
                if (allowed_to_use_coupon($coupon, Auth::check() ? auth()->user()->id : $temp_user_id)) {
                    $coupon_details = json_decode($coupon->details);
                    if (Auth::check()) {
                        $carts = Cart::where('user_id', auth()->user()->id)
                            ->where('owner_id', $coupon->user_id)
                            ->get();
                    } else {
                        $carts = Cart::where('temp_user_id', $temp_user_id)
                            ->where('owner_id', $coupon->user_id)
                            ->get();
                    }
                    $coupon_discount = 0;
                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                            $shipping += $cartItem['shipping_cost'];
                        }
                        $sum = $subtotal + $tax + $shipping;
                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                        }
                    } elseif ($coupon->type == 'product_base') {
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += (cart_product_price($cartItem, $product, false, false) * $coupon->discount / 100) * $cartItem['quantity'];
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                    }
                                }
                            }
                        }
                    }
                    if ($coupon_discount > 0) {
                        if (Auth::check()) {
                            Cart::where('user_id', auth()->user()->id)
                                ->where('owner_id', $coupon->user_id)
                                ->update(
                                    [
                                        'discount' => $coupon_discount / count($carts),
                                        'coupon_code' => $code,
                                        'coupon_applied' => 1
                                    ]
                                );
                        } else {
                            Cart::where('temp_user_id', $temp_user_id)
                                ->where('owner_id', $coupon->user_id)
                                ->update(
                                    [
                                        'discount' => $coupon_discount / count($carts),
                                        'coupon_code' => $code,
                                        'coupon_applied' => 1
                                    ]
                                );
                        }

                        $response_message['response'] = 'success';
                        $response_message['message'] = translate('Coupon has been applied');
                    } else {
                        $response_message['response'] = 'warning';
                        $response_message['message'] = translate('This coupon is not applicable to your cart products!');
                    }
                } else {
                    $response_message['response'] = 'warning';
                    $response_message['message'] = translate('You already used this coupon!');
                }
            } else {
                $response_message['response'] = 'warning';
                $response_message['message'] = translate('Coupon expired!');
            }
        } else {
            $response_message['response'] = 'danger';
            $response_message['message'] = translate('Invalid coupon!');
        }

        if (Auth::check()) {
            $carts = Cart::where('user_id', auth()->user()->id)
                ->get();
        } else {
            $carts = Cart::where('temp_user_id', $temp_user_id)
                ->get();
        }


        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        $returnHTML = view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();
        $cartSummeryHTMLMobile = view('frontend.checkout_one_Step.cart_summary_for_mobile', compact('coupon', 'carts', 'shipping_info'))->render();
        return response()->json(array('response_message' => $response_message, 'html' => $returnHTML, 'htmlMobile' => $cartSummeryHTMLMobile));
    }

    public function remove_coupon_code_checkout(Request $request, $code)
    {
        $temp_user_id = $request->session()->get('temp_user_id');
        if (Auth::check()) {
            Cart::where('user_id', Auth::user()->id)->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ]
            );
            $carts = Cart::where('user_id', Auth::user()->id)
                ->get();
        } else {
            Cart::where('temp_user_id', $temp_user_id)->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ]
            );

            $carts = Cart::where('temp_user_id', $temp_user_id)
                ->get();
        }
        $coupon = Coupon::where('code',  $code)->first();

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $html = view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();
        $htmlMobile = view('frontend.checkout_one_Step.cart_summary_for_mobile', compact('coupon', 'carts', 'shipping_info'))->render();
        return ['html' => $html, 'htmlMobile' => $htmlMobile];
    }



    public function remove_coupon_code(Request $request)
    {
        $temp_user_id = $request->session()->get('temp_user_id');
        if (Auth::check()) {
            Cart::where('user_id', Auth::user()->id)->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ]
            );
            $carts = Cart::where('user_id', Auth::user()->id)
                ->get();
        } else {
            Cart::where('temp_user_id', $temp_user_id)->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ]
            );
            $carts = Cart::where('temp_user_id', $temp_user_id)
                ->get();
        }
        $coupon = Coupon::where('code', $request->code)->first();
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $html = view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'));
        $htmlMobile = view('frontend.checkout_one_Step.cart_summary_for_mobile', compact('coupon', 'carts', 'shipping_info'))->render();
        return [$html, $htmlMobile];
    }
    public function apply_club_point(Request $request)
    {
        if (addon_is_activated('club_point')) {

            $point = $request->point;

            if (Auth::user()->point_balance >= $point) {
                $request->session()->put('club_point', $point);
                flash(translate('Point has been redeemed'))->success();
            } else {
                flash(translate('Invalid point!'))->warning();
            }
        }
        return back();
    }
    public function remove_club_point(Request $request)
    {
        $request->session()->forget('club_point');
        return back();
    }
    public function get_edfa_status($id)
    {
        if (auth()->check()) {
            $order =    Order::find($id);
            $user_id = auth()->user()->id;
            if ($order->user_id != $user_id) {
                flash(translate('This Order Is Not For You'))->warning();
                return redirect()->route('home');
            } else {
                $order = Order::find($id);
                return view('frontend.loading', compact('order'));
            }
        } else {
            flash(translate('Please Login First'))->success();
            return redirect()->route('home');
        }
    }
    public function edfa_failed(Request $request, $id)
    {
        $request->session()->forget('order_id');
        $request->session()->forget('payment_data');
        flash(translate('Something went wrong'))->warning();
        return redirect()->route('home');
    }
    public function order_confirmed($code)
    {
        $combined_order = CombinedOrder::latest()->first();
        $order = Order::where('code', $code)->first();
        $combined_order = CombinedOrder::find($order->combined_order_id);
        session()->put('combined_order_id', $combined_order->id);
        $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
        if ($order->payment_type == "cash_on_delivery") {
            $payment_event = false;
        } else {
            $payment_event = true;
            TikTokConverasionTrait::TikTokPayment($combined_order, $pixel_event_id);
        }
        TikTokConverasionTrait::TikTokPurchase($combined_order , $pixel_event_id);
        $pixel_event_id = Str::random(30);
        if (get_setting('facebook_converasion_api') == 1) {
            ConversionApiTrait::Purchase($combined_order, $pixel_event_id);
        }
        if (get_setting('snapchat_converasion_api')  == 1) {
            SnapchatConversionTrait::SnapChatPurchase($carts, $pixel_event_id);
        }
        
        
        TikTokConverasionTrait::TikTokInitiateCheckout($carts, $pixel_event_id);
        

        $orders = $combined_order->orders()->pluck('id');
        $product_ids = \App\Models\OrderDetail::whereIn('order_id', $orders)->pluck('product_id');
        $products_name = \App\Models\Product::whereIn('id', $product_ids)->pluck('name')->toArray();
        $product_details = array_map(function ($id, $name) {
            return ['product_id' => $id, 'product_name' => $name];
        }, $product_ids->toArray(), $products_name);
        Cart::where('user_id', $combined_order->user_id)->delete();
        return view('frontend.order_confirmed', compact('combined_order', 'pixel_event_id','product_details','payment_event'));
    }
    public function register_for_order(Request $request)
    {
        try {
            DB::beginTransaction();
            $temp_user_id = $request->session()->get('temp_user_id');
            $password = Str::random(10);
            $country_code = "966";
            $isEmailUnique = !User::where('email', $request->email,)->exists();
            if (!$isEmailUnique) {
                return response()->json(['success' => false, 'message' => translate('The email is already exist')]);
            }
            $phone = '+' . $country_code . $request->phone;
            $isPhoneUnique = !User::where('phone', $phone)->exists();
            if (!$isPhoneUnique) {
                return response()->json(['success' => false, 'message' =>   translate('The phone number is already exist.')]);
            }
            $user = User::create([
                'name' => $request->name,
                'phone' => '+' . $country_code . $request->phone,
                'password' => Hash::make($password),
                'hashed_password' => $password,
                'otp_expire_at' => Carbon::now()->addMinutes(10),
                'address' => $request->address,
                'state' => $request->state,
                'email' => $request->email,
            ]);
            $cart = Cart::where('temp_user_id', $temp_user_id)->update([
                'user_id' => $user->id,
                'temp_user_id' => null,
            ]);
            Auth::login($user);
            $otpController = new OTPVerificationController;
            $otpController->code_for_complete_order($user);
            DB::commit();
            return response()->json(['success' => true, 'data' => $user->verification_code]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'data' => $e->getMessage()]);
            DB::rollback();
        }
    }
    public function return_mobile_checkout(Request $request)
    {
        $user = auth()->user();
        $ShouldAuthenticated = 0;
        $ShouldVerify = 0;
        $CanOrdered = 0;
        $AutoOrdered = 0;
        if ($user == null) {
            $ShouldAuthenticated = 1;
        }
        if ($user && $user->is_verified == 0) {
            $ShouldVerify = 1;
        }
        if ($user && $user->is_verified == 1) {
            $CanOrdered = 1;
        }
        if ($user != null && $user->email_verified_at != null && $user->is_verified == 1) {
            $verificationTime = Carbon::parse($user->email_verified_at);
            $currentTime = Carbon::now();
            if ($currentTime->diffInMinutes($verificationTime) <= 1) {
                $AutoOrdered = 1;
            }
        }
        $temp_user_id = $request->session()->get('temp_user_id');
        if (Auth::check()) {
            $carts = Cart::where('user_id', auth()->user()->id)->get();
        } else {
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        $total = $carts->sum('price');
        $view = view('frontend.checkout_one_Step.mobile_checkout', compact('carts', 'total', 'ShouldAuthenticated', 'ShouldVerify', 'CanOrdered', 'AutoOrdered'))->render();
        return response()->json(['success' => true, 'view' => $view]);
    }
    public function return_desktop_checkout(Request $request)
    {
        $user = auth()->user();
        $ShouldAuthenticated = 0;
        $ShouldVerify = 0;
        $CanOrdered = 0;
        $AutoOrdered = 0;
        if ($user == null) {
            $ShouldAuthenticated = 1;
        }
        if ($user && $user->is_verified == 0) {
            $ShouldVerify = 1;
        }
        if ($user && $user->is_verified == 1) {
            $CanOrdered = 1;
        }
        if ($user != null && $user->email_verified_at != null && $user->is_verified == 1) {
            $verificationTime = Carbon::parse($user->email_verified_at);
            $currentTime = Carbon::now();
            if ($currentTime->diffInMinutes($verificationTime) <= 1) {
                $AutoOrdered = 1;
            }
        }
        $temp_user_id = $request->session()->get('temp_user_id');
        if (Auth::check()) {
            $carts = Cart::where('user_id', auth()->user()->id)->get();
        } else {
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        $total = $carts->sum('price');

        $view = view('frontend.checkout_one_Step.desktop_checkout', compact('carts', 'total', 'ShouldAuthenticated', 'ShouldVerify', 'CanOrdered', 'AutoOrdered'))->render();
        return response()->json(['success' => true, 'view' => $view]);
    }
    public function check_auth_verify()
    {
        if (auth()->check()) {
            if (auth()->user()->is_verified == 1) {
                return 'allowed';
            } else {
                return 'not_verified';
            }
        } else {
            return 'not_auth';
        }
    }
}
