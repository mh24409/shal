<?php

namespace App\Http\Controllers;

use PDF;
use Auth;
use Mail;
use Exception;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\City;
use App\Models\User;
use App\Models\Order;
use App\Models\State;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Carrier;
use App\Models\Country;
use App\Models\Product;
use App\Models\Language;
use App\Models\CouponUsage;
use App\Models\OrderDetail;
use App\Models\SmsTemplate;
use App\Utility\SmsUtility;
use Illuminate\Support\Str;
use App\Models\ProductStock;
use App\Traits\AymakanTrait;
use CoreComponentRepository;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Traits\FizpaIntegration;
use App\Mail\InvoiceEmailManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Shipping_Governments;
use App\Utility\NotificationUtility;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AffiliateController;
use App\Jobs\UpdateOrderShippingCompanyStatus;

class OrderController extends Controller
{
    use FizpaIntegration, AymakanTrait;

    public function __construct()
    {
        $this->middleware(['permission:view_all_orders|view_inhouse_orders|view_seller_orders|view_pickup_point_orders'])->only('all_orders');
        $this->middleware(['permission:view_order_details'])->only('show');
        $this->middleware(['permission:delete_order'])->only('destroy', 'bulk_order_delete');
    }
    public function fizpa()
    {
        $combined_order = CombinedOrder::latest()->first();
        return $this->AymakanNewOrder($combined_order);
    }
    public function all_orders(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';

        $orders = Order::orderBy('id', 'desc');
        $admin_user_id = User::where('user_type', 'admin')->first()->id;

        if (
            Route::currentRouteName() == 'inhouse_orders.index' &&
            Auth::user()->can('view_inhouse_orders')
        ) {
            $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
        } else if (
            Route::currentRouteName() == 'seller_orders.index' &&
            Auth::user()->can('view_seller_orders')
        ) {
            $orders = $orders->where('orders.seller_id', '!=', $admin_user_id);
        } else if (
            Route::currentRouteName() == 'pick_up_point.index' &&
            Auth::user()->can('view_pickup_point_orders')
        ) {
            $orders->where('shipping_type', 'pickup_point')->orderBy('code', 'desc');
            if (
                Auth::user()->user_type == 'staff' &&
                Auth::user()->staff->pick_up_point != null
            ) {
                $orders->where('shipping_type', 'pickup_point')
                    ->where('pickup_point_id', Auth::user()->staff->pick_up_point->id);
            }
        } else if (
            Route::currentRouteName() == 'all_orders.index' &&
            Auth::user()->can('view_all_orders')
        ) {
        } else {
            abort(403);
        }

        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }
        $orders = $orders->paginate(15);
        return view('backend.sales.index', compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date'));
    }

    public function show($id)
    {
        
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::where('city', $order_shipping_address->city)
            ->where('user_type', 'delivery_boy')
            ->get();
        $order->viewed = 1;
        $order->save();
        $shipping_status = DB::table('order_tracking')->where('order_id',$order->id)->orderBy('id','desc')->get();
        return view('backend.sales.show', compact('order', 'shipping_status','delivery_boys'));
    }
    public function create()
    {
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user && $request->name != $user->name && $user->generated_by_system == 1) {
            $user->name = $request->name;
            $user->generated_by_system = 0 ;
            $user->save();
        }
            
        $COD_tax = get_setting('COD_tax');
        if (Auth::check()) {
            $carts = Cart::where('user_id', Auth::user()->id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }
        $country = Country::where('id', $request->country_id)->first();
        $city = city::where('state_id', $request->state_id)->first();
        $state = State::where('id', $request->state_id)->first();
        $shippingAddress = [];
        $shippingAddress['name']        = $request->name ?? Auth::user()->name  ;
        $shippingAddress['email']       = (Auth::user()->email ?? $request->email) ?? 'N/A';
        $shippingAddress['address']     = $request->address;
        $shippingAddress['country']     = $country->name;
        $shippingAddress['state']       = $state->name;
        $shippingAddress['shipping_state'] = $state->shipping_name;
        $shippingAddress['city']        = $city->name;
        $shippingAddress['shipping_city'] = $city->shipping_name;
        $shippingAddress['postal_code'] = '000000';
        $shippingAddress['phone']       = $request->phone ?? Auth::user()->phone ;
        $combined_order = new CombinedOrder;
        $combined_order->user_id = Auth::user()->id ?? null;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();
        $seller_products = array();
        foreach ($carts as $cartItem) {
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
            $order->user_id = Auth::user()->id ?? null;
            $order->guest_id = $request->session()->get('temp_user_id') ?? null;
            $order->shipping_address = $combined_order->shipping_address;
            $order->additional_info = $request->additional_info;
            $order->name = $request->name ?? null;
            $order->phone = $request->phone ?? null;
            
            $order->COD_tax = $COD_tax;
            $order->email = $request->email ?? null;
            $order->payment_type = $request->payment_option;
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = rand(10000, 99999);
            $order->date = strtotime('now');
            $order->save();
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;
            $carts_count = count($seller_product);
            $carts_total_price = collect($seller_product)->sum('price');
            foreach ($seller_product as $cartItem) {
                $back_order = 0;
                $product = Product::find($cartItem['product_id']);
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax +=  cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $coupon_discount += $cartItem['discount'];

                $product_variation = $cartItem['variation'];
                $product_stock = $product->stocks->where('variant', $product_variation)->first();
                $cost_price = $product_stock->cost_price ??0;
                
                if ($product->digital != 1 && $cartItem['quantity'] > $product_stock->qty && $product->back_order == 0) {
                    flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
                    $order->delete();
                    return redirect()->route('cart')->send();
                } elseif ($product->digital != 1) {
                    $product_stock->qty -= $cartItem['quantity'];
                    $product_stock->save();
                }
                if ($cartItem['quantity'] > $product_stock->qty && $product->back_order == 1) {
                    $back_order = 1;
                }
                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->cost_price = $cost_price;
                $order_detail->back_order = $back_order;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->price = cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $order_detail->tax = cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $order_detail->product_referral_code = $cartItem['product_referral_code'];
                if ($carts_count >= get_setting('allowed_free_shipping_quantity') || $carts_total_price >= get_setting('allwed_free_shipping_discount')) {
                    $order_detail->shipping_type = 'free';
                    $order_detail->shipping_cost = 0;
                    $shipping += $order_detail->shipping_cost;
                } else {
                    $order_detail->shipping_type = $cartItem['shipping_type'];
                    $order_detail->shipping_cost = $cartItem['shipping_cost'];
                    $shipping += $order_detail->shipping_cost;
                }
                $order_detail->quantity = $cartItem['quantity'];
                if (addon_is_activated('club_point')) {
                    $order_detail->earn_point = $product->earn_point;
                }
                $order_detail->save();
                $product->num_of_sale += $cartItem['quantity'];
                $product->save();
                $order->seller_id = $product->user_id;
                $order->shipping_type = $cartItem['shipping_type'];

                if ($cartItem['shipping_type'] == 'pickup_point') {
                    $order->pickup_point_id = $cartItem['pickup_point'];
                }
                $order->carrier_id = $request->shipping_company;
                if ($product->added_by == 'seller' && $product->user->seller != null) {
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
            $shipping = get_setting('flat_rate_shipping_cost');
            if ($carts_count >= get_setting('allowed_free_shipping_quantity') || $carts_total_price >= get_setting('allwed_free_shipping_discount')) {
                $shipping = 0;
            }
            $order->grand_total = $subtotal + $tax + $shipping;
            $order->shipping_cost = $shipping;
            $order->shipping_fees = $shipping - get_setting('shipping_profit');

            if ($seller_product[0]->coupon_code != null) {
                if (allowed_to_use_coupon(Coupon::where('code', $seller_product[0]->coupon_code)->first(), Auth::check() ? auth()->user()->id : $temp_user_id)) {
                    $order->coupon_discount = $coupon_discount;
                    $order->grand_total -= $coupon_discount;
                    
                    $order->coupon_code = $seller_product[0]->coupon_code;
                    $coupon_usage = new CouponUsage;
                    $coupon_usage->user_id = Auth::user()->id ?? null;
                    $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
                    $coupon_usage->save();
                    $plus_one = Coupon::where('code', $seller_product[0]->coupon_code)->first()->num_of_uses + 1;
                    Coupon::where('code', $seller_product[0]->coupon_code)->update([
                        'num_of_uses' => $plus_one,
                    ]);
                }
            }
            $combined_order->grand_total += $order->grand_total;
            $order->save();
        }
        if ($order->payment_type === "cash_on_delivery") {
            $order->grand_total = $order->grand_total + 25;
            $order->save();
            $combined_order->grand_total = $combined_order->grand_total + 25;
            $combined_order->save();
        }
        $combined_order->save();
        if ($request->shipping_company == 4) {
            $this->fizpaNewOrder($combined_order, $request->jana_city_id);
        } elseif ($request->shipping_company == 3) {
            $this->AymakanNewOrder($combined_order);
        }
        foreach ($combined_order->orders as $order) {
            NotificationUtility::sendOrderPlacedNotification($order);
        }

        $request->session()->put('combined_order_id', $combined_order->id);
    }

    public function edit_order_by_admin(Order $order)
    {
        $products = Product::where('current_stock', '>', 0)->with('stocks')->orderBy('id', 'desc')->get();
        $newRowId = 1;

        $states = State::where('status', 1)->whereHas('cities', function ($query) {
            $query->where('status', 1);
        })->with(['cities' => function ($query) {
            $query->where('status', 1);
        }])->get();
        foreach ($products as $test) {
            if ($test->stocks->count() <= 0) {
                $product[] = $test;
            }
        }

        $cities = City::get();

        $customers = User::where('user_type', 'customer')->where('banned', 0)->get();

        $now = Carbon::now()->timestamp;
        $coupons = Coupon::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get();

        $shipping_address = json_decode($order->shipping_address, true);



        $order_state_cities = City::whereHas('state', function ($q) use ($shipping_address) {
            $q->where('name', $shipping_address['state']);
        })->get();



        return view('backend.sales.edit_order', compact('shipping_address', 'order_state_cities', 'order', 'products', 'newRowId', 'customers', 'states', 'coupons'));
    }


    public function update_order_by_admin(Request $request, Order $order)
    {
        // try{

        DB::beginTransaction();
        $shippingCost = 0;
        if (get_setting('shipping_type') == "flat_rate") {
            $shippingCost =  get_setting('flat_rate_shipping_cost');
        } elseif (get_setting('shipping_type') == "area_wise_shipping"  && $order->shipping_type != "pick_up_from_store") {
            $city = City::find($request->city_id);
            $shippingCost =  $city->cost;
        }
        $request->shipping_method = $order->shipping_type;
        $customer = $order->user ?? $order->guest_id;


        if ($order->shipping_type == "home_delivery" || $order->shipping_type == "shipping_company") {

            $shippingAddress = json_decode($order->shipping_address, true);
            $address = Address::where('id', $order->address_id)->first();

            if ($address !== null) {
                $address->country_id = 64;
                $address->state_id = $request->state_id;
                $address->city_id = $request->city_id;
                $address->phone =  $request->phone ?? $customer->phone;
                $address->address = $request->address;
                $address->Save();
            }
            $shippingAddress['phone']       = $request->phone ?? $customer->phone;
            $shippingAddress['address']     = ($request->address ?? $address->address) ??  $shippingAddress['address'];
            $shippingAddress['country']     = $address->country->name ?? $shippingAddress['country'];
            $shippingAddress['shipping_state'] = $address->state->shipping_name ?? $shippingAddress['shipping_state'];
            $shippingAddress['state']       = $address->state->name ?? $shippingAddress['state'];
            $shippingAddress['shipping_city'] = $address->city->shipping_name ?? $shippingAddress['shipping_city'];
            $shippingAddress['city']        = $address->city->name ?? $shippingAddress['city'];
            $shippingAddress['postal_code'] = "20";

            if ($order->shipping_type == "shipping_company") {
                $otheCompanyShippingAddress = [];

                $validation_values = [
                    'shipping_company_name', 'shipping_customer_name', 'shipping_customer_phone', 'shipping_customer_email'
                ];

                foreach ($validation_values as $value) {
                    if (empty($request->$value)) {
                        flash(translate(str_replace('shipping', '', str_replace('_', ' ', $value)) . ' can\'t be empty'))->error();
                        return redirect()->back();
                    }
                };

                $shippingData['company_name']        = $request->shipping_company_name;
                $shippingData['name']        = $request->shipping_customer_name;
                $shippingData['email']       = $request->shipping_customer_email;
                $shippingData['phone']       = $request->shipping_customer_phone;
                $shippingData['address']     = $address->address;
                $shippingData['country']     = $address->country->name;
                $shippingData['state']       = $address->state->name;
                $shippingData['city']        = $address->city->name;
            }
        } else {

            $address = Address::where('user_id', auth()->user()->id)->first();
            $shippingAddress = [];
            $shippingAddress['phone']       = $customer->phone ?? $request->phone;
            $shippingAddress['country']     = "Egypt";
            $shippingAddress['address']     = "Pick Up From Store";
            $shippingAddress['state']       = "Pick Up From Store";
            $shippingAddress['city']        = "Pick Up From Store";
            $shippingAddress['postal_code'] = "20";
        }


        // $old_shipping_type = $order->shipping_type;//the value for checking if order is already in the shipping company or not before any changes

        $carts = $this->createCart($request, $customer, $address, $shippingCost);


        if ($carts->isEmpty()) {
            flash(translate('Order Cannot Be Empty!'))->warning();
            return redirect()->back();
        } else {
            $order_old_details_id_array = $order->orderDetails->pluck('id');
            $order_details = $order->orderDetails;

            foreach ($order_details as $item) {
                $item_product = $item->product->stocks->where('variant', "$item->variation")->first();
                $product = $item->product;
                $item_product->qty = $item->quantity + $item_product->qty;
                $item_product->save();
                $product->num_of_sale -= $item->quantity;
                $product->save();
            }
            OrderDetail::whereIn('id', $order_old_details_id_array)->delete();
        }

        $combined_order = CombinedOrder::where('id', $order->combined_order_id)->first();
        $shipping_address =  $shippingAddress ?? $order->shipping_address;
        $combined_order->shipping_address = $shipping_address;
        $combined_order->save();
        $seller_products = array();
        foreach ($carts as $cartItem) {
            $product_ids = array();
            $product = Product::find($cartItem['product_id']);
            if (isset($seller_products[$product->user_id])) {
                $product_ids = $seller_products[$product->user_id];
            }
            array_push($product_ids, $cartItem);
            $seller_products[$product->user_id] = $product_ids;
        }

        foreach ($seller_products as $seller_product) {
            // $order = new Order;
            // $order->user_id = $customer->id;
            // $order->guest_id = $request->session()->get('temp_user_id') ?? null;
            // $order->address_id = $address->id;
            // $order->shipping_address = $shipping_address ?? $order->shipping_address;
            // $order->additional_info = $request->additional_info ?? $order->additional_info;
            // $order->phone = $request->phone ?? $order->phone;



            // $order->payment_type = $request->payment_option ?? $order->payment_type;
            // $order->save();
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;


            foreach ($seller_product as $cartItem) {
                $product = Product::find($cartItem['product_id']);
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                $tax +=  $cartItem['tax'] * $cartItem['quantity'];
                $product_variation = $cartItem['variation'];

                $product_stock = $product->stocks->where('variant', $product_variation)->first();
                if ($product_stock == null) {
                    $product_stock = $product->stocks->where('variant', "")->first();
                }
                $qty = $product_stock->qty;
                if ($product->digital != 1 && $cartItem['quantity'] > $qty) {
                    flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
                    return redirect()->back();
                } elseif ($product->digital != 1) {
                    $product_stock->qty -= $cartItem['quantity'];
                    $product_stock->save();
                }
                if (get_setting('shipping_type') == "product_wise_shipping") {
                    $shippingCost = 0;
                    $shippingCost += $product->shipping_cost;
                }
                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
                $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
                $order_detail->shipping_type = $cartItem['shipping_type'];
                $order_detail->product_referral_code = $cartItem['product_referral_code'];

                $price_type = $request->product_info;
                switch ($price_type) {
                    case $product->price:
                        $order_detail->price_type = 'unit_price';
                        break;
                    case $product->wholesale_price:
                        $order_detail->price_type = 'wholesale_price';
                        break;
                    case $product->wholesale_price_variant:
                        $order_detail->price_type = 'wholesale_price_variant';
                        break;
                    default:
                        $order_detail->price_type = 'unit_price';
                        break;
                }


                if (get_setting('shipping_type') == "flat_rate") {
                    $order_detail->shipping_cost = $cartItem['shipping_cost'] / count($seller_product);
                } else {
                    $order_detail->shipping_cost = $cartItem['shipping_cost'];
                }

                $shipping = $shippingCost;
                $order_detail->quantity = $cartItem['quantity'];

                if (addon_is_activated('club_point')) {
                    $order_detail->earn_point = $product->earn_point;
                }
                $order_detail->save();
                $product->num_of_sale += $cartItem['quantity'];
                $product->save();
                $order->seller_id = $product->user_id;
                $order->shipping_type = $cartItem['shipping_type'];

                if ($cartItem['shipping_type'] == 'pickup_point') {
                    $order->pickup_point_id = $cartItem['pickup_point'];
                }
                if ($cartItem['shipping_type'] == 'carrier') {
                    $order->carrier_id = $cartItem['carrier_id'];
                }
                if (addon_is_activated('affiliate_system')) {
                    if ($order_detail->product_referral_code) {
                        $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();
                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
                    }
                }
            }
            $taxForAdmin = 0;
            if ($request->tax_type == "fixed") {
                $taxForAdmin = $request->tax;
            } elseif ($request->tax_type == "percent") {
                $taxForAdmin = ($request->tax * $subtotal) / 100;
            }
            if ($request->discount_type == "fixed") {
                $coupon_discount = $request->discount;
                $discount_type = 'fixed';
            } elseif ($request->discount_type == "percent") {
                $coupon_discount = ($request->discount * $subtotal) / 100;
                $discount_type = 'percent';
            }
            // if ($request->shipping_method == "pick_up_from_store") {
            //     $shipping = 0;
            // }
            // if($request->shipping_method =="pick_up_from_store")
            // {
            //     $order->payment_status = "paid";
            //     $order->payment_type = "cash";
            // }else{
            // }
            if ($request->is_paid == "on") {
                $is_paid = "paid";
                $update_status = OrderDetail::where('order_id', $order->id)->update([
                    'payment_status' => 'paid'
                ]);
            } else {
                $is_paid = "unpaid";
                $update_status = OrderDetail::where('order_id', $order->id)->update([
                    'payment_status' => 'unpaid'
                ]);
            }



            $order->payment_status = $is_paid;
            $order->payment_type = $request->payment_type ?? "cash";


            $tax_value = $request->tax;
            $discount_value = $request->discount;

            $order->AddedTax = $taxForAdmin;
            $order->tax_type = $request->tax_type ?? null;
            $total = $subtotal - $coupon_discount;
            $order->grand_total = $total + $tax + $shipping + $taxForAdmin;
            $order->coupon_discount = $coupon_discount;
            $order->discount_type = $discount_type;
            $order->tax_value = $tax_value ?? $order->tax_value;
            $order->discount_value = $discount_value ?? $order->discount_value;
            $order->save();
            $combined_order->grand_total = $order->grand_total;
        }
        $combined_order->save();


        $customer_id = $customer->id ?? $customer;
        $cart = Cart::where('user_id', $customer_id)->where('is_admin', '1')->pluck('id');

        Cart::whereIn('id', $cart)->delete();

        if ($request->is_open == "on") {
            $is_open = '1';
        } else {
            $is_open = '0';
        }
        if ($request->is_shipping == "on") {
            $is_shipping = '1';
        } else {
            $is_shipping = '0';
        }

        $order->is_open = $is_open;
        $order->is_shipping = $is_shipping;
        $order->shipping_address = $shipping_address ?? $order->shipping_address;
        $order->phone = $request->phone ?? $order->phone;
        if ($order->shipping_type == "shipping_company" && isset($shippingData)) {
            $order->other_shipping_company = json_encode($shippingData);
            $order->save();
            // $this->otherShippingCompanyAddressPdf($shippingData);
        } else {
            $order->save();
        }
        DB::commit('order updated');

        Log::info('order update successfully');

        flash(translate('Order Has Been updated Successfully'))->success();
        return redirect()->route('edit_order_by_admin', ['order' => $order->id]);
    }



    public function cancel_order_from_shipping_company($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            if ($order->shipping_barcode != null) {
                $data =
                    [
                        'authentication_key' => env('TURBO_AUTHENTICATION_KEY'),
                        'id' => $order->shipping_barcode,
                        'type' => 1
                    ];
                $jsonData = json_encode($data);
                $apiUrl = env('TURBO_CANCEL_ORDER_URL');
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Accept: application/json',
                    'Content-Type: application/json',
                ));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                $response = curl_exec($ch);
                dd($response);
            }
            flash(translate('Order has been canceld successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }







    public function edit($id)
    {
    }


    public function update(Request $request, $id)
    {
    }


    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                try {

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->where('variant', $orderDetail->variation)->first();
                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                } catch (\Exception $e) {
                }

                $orderDetail->delete();
            }
            if ($order->shipping_barcode != null) {
                $data =
                    [
                        'authentication_key' => env('TURBO_AUTHENTICATION_KEY'),
                        'search_Key' => $order->shipping_barcode,
                    ];
                $jsonData = json_encode($data);
                $apiUrl = env('TURBO_Delete_ORDER_URL');
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Accept: application/json',
                    'Content-Type: application/json',
                ));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                $response = curl_exec($ch);
            }
            $order->delete();
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->destroy($order_id);
            }
        }

        return 1;
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('seller.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {

                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }

                if (addon_is_activated('affiliate_system')) {
                    if (($request->status == 'delivered' || $request->status == 'cancelled') &&
                        $orderDetail->product_referral_code
                    ) {

                        $no_of_delivered = 0;
                        $no_of_canceled = 0;

                        if ($request->status == 'delivered') {
                            $no_of_delivered = $orderDetail->quantity;
                        }
                        if ($request->status == 'cancelled') {
                            $no_of_canceled = $orderDetail->quantity;
                        }

                        $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, 0, $no_of_delivered, $no_of_canceled);
                    }
                }
            }
        }
        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
            try {
                SmsUtility::delivery_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {
            }
        }

        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        $device_token = $order->user->device_token ?? null;
        if (get_setting('google_firebase') == 1 &&  $device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->delivery_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('delivery_boy')) {
            if (Auth::user()->user_type == 'delivery_boy') {
                $deliveryBoyController = new DeliveryBoyController;
                $deliveryBoyController->store_delivery_history($order);
            }
        }

        return 1;
    }

    public function update_tracking_code(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();


        if (
            $order->payment_status == 'paid' &&
            $order->commission_calculated == 0
        ) {
            calculateCommissionAffilationClubPoint($order);
        }

        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        $device_token = $order->user->device_token ?? null;
        if (get_setting('google_firebase') == 1 &&  $device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->payment_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
            try {
                SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {
            }
        }
        return 1;
    }

    public function assign_delivery_boy(Request $request)
    {
        if (addon_is_activated('delivery_boy')) {

            $order = Order::findOrFail($request->order_id);
            $order->assign_delivery_boy = $request->delivery_boy;
            $order->delivery_history_date = date("Y-m-d H:i:s");
            $order->save();

            $delivery_history = \App\Models\DeliveryHistory::where('order_id', $order->id)
                ->where('delivery_status', $order->delivery_status)
                ->first();

            if (empty($delivery_history)) {
                $delivery_history = new \App\Models\DeliveryHistory;

                $delivery_history->order_id = $order->id;
                $delivery_history->delivery_status = $order->delivery_status;
                $delivery_history->payment_type = $order->payment_type;
            }
            $delivery_history->delivery_boy_id = $request->delivery_boy;

            $delivery_history->save();

            if (env('MAIL_USERNAME') != null && get_setting('delivery_boy_mail_notification') == '1') {
                $array['view'] = 'emails.invoice';
                $array['subject'] = translate('You are assigned to delivery an order. Order code') . ' - ' . $order->code;
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['order'] = $order;

                try {
                    Mail::to($order->delivery_boy->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                }
            }

            if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'assign_delivery_boy')->first()->status == 1) {
                try {
                    SmsUtility::assign_delivery_boy($order->delivery_boy->phone, $order->code);
                } catch (\Exception $e) {
                }
            }
        }

        return 1;
    }
    public function reciept($id)
    {
        $order = Order::find($id);
        $shippingAddress = json_decode($order->shipping_address);


        $order = Order::with('orderDetails.product')->where('id', $id)->first();
        $productNames = [];
        foreach ($order->orderDetails as $orderDetail) {
            $product = $orderDetail->product;
            if ($product) {
                $productNames[] = $product->getTranslation('name');
            }
        }
        $productNamesString = implode(', ', $productNames);
        $data = [
            'code' => $order->shipping_barcode ?? 1234567879,
            'city' => $shippingAddress->city,
            'state' => $shippingAddress->state,
            'sender' => 'Shall',
            'reciever' => $shippingAddress->name,
            'total' => $order->grand_total,
            'address' => $shippingAddress->address,
            'content' => $productNamesString,
            'phone' => $shippingAddress->phone,
            'notes' => $order->additional_info ?? translate('No Notes Found'),
        ];
        return view('backend.sales.reciept', compact('data'));
    }




    public function new_order_by_admin(Request $request)
    {
        $products = Product::where('current_stock', '>', 0)->with('stocks')->orderBy('id', 'desc')->get();
        $newRowId = 1;

        $states = State::where('status', 1)->whereHas('cities', function ($query) {
            $query->where('status', 1);
        })->with(['cities' => function ($query) {
            $query->where('status', 1);
        }])->get();
        foreach ($products as $test) {
            if ($test->stocks->count() <= 0) {
                $product[] = $test;
            }
        }
        $customers = User::where('user_type', 'customer')->where('banned', 0)->get();

        $now = Carbon::now()->timestamp;
        $coupons = Coupon::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get();
        return view('backend.sales.new_order', compact('products', 'newRowId', 'customers', 'states', 'coupons'));
    }

    public function add_order_by_admin(Request $request)
    {
        if (in_array($request->shipping_method, ["shipping_company", "home_delivery"]) && empty($request->city_id)) {
            flash(translate('make sure to fill shipping address fields'))->error();
            return redirect()->back();
        }

        $shippingCost = 0;
        if (get_setting('shipping_type') == "flat_rate") {
            $shippingCost =  get_setting('flat_rate_shipping_cost');
        } elseif (get_setting('shipping_type') == "area_wise_shipping" && $request->shipping_method != "pick_up_from_store") {
            $city = City::find($request->city_id);
            $shippingCost =  $city->cost;
        }

        if ($request->customer_id == 0 && $request->phone == null) {
            flash(translate('Please Enter Phone Number'))->warning();
            return redirect()->back();
        }



        $customer = $this->createCustomer($request);

        if ($request->shipping_method == "home_delivery" || $request->shipping_method == "shipping_company") {
            $address = new Address();
            $address->country_id = 64;
            $address->state_id = $request->state_id;
            $address->city_id = $request->city_id;
            $address->user_id = $customer->id;
            $address->postal_code = $request->postal_code;
            $address->phone =  $request->phone ?? $customer->phone;
            $address->address = $request->address;
            $address->Save();
            $shippingAddress = [];
            $shippingAddress['name']        = $customer->name;
            $shippingAddress['email']       = $customer->email;
            $shippingAddress['phone']       = $request->phone ?? $customer->phone;
            $shippingAddress['address']     = $address->address;
            $shippingAddress['country']     = $address->country->name;
            $shippingAddress['shipping_state'] = $address->state->shipping_name;
            $shippingAddress['state']       = $address->state->name;
            $shippingAddress['shipping_city'] = $address->city->shipping_name;
            $shippingAddress['city']        = $address->city->name;
            $shippingAddress['postal_code'] = "20";

            if ($request->shipping_method == "shipping_company") {
                $otheCompanyShippingAddress = [];

                $validation_values = [
                    'shipping_company_name', 'shipping_customer_name', 'shipping_customer_phone', 'shipping_customer_email'
                ];

                foreach ($validation_values as $value) {
                    if (empty($request->$value)) {
                        flash(translate(str_replace('shipping', '', str_replace('_', ' ', $value)) . ' can\'t be empty'))->error();
                        return redirect()->back();
                    }
                };

                $shippingData['company_name']        = $request->shipping_company_name;
                $shippingData['name']        = $request->shipping_customer_name;
                $shippingData['email']       = $request->shipping_customer_email;
                $shippingData['phone']       = $request->shipping_customer_phone;
                $shippingData['address']     = $address->address;
                $shippingData['country']     = $address->country->name;
                $shippingData['state']       = $address->state->name;
                $shippingData['city']        = $address->city->name;
            }
        } else {

            $address = Address::where('user_id', auth()->user()->id)->first();
            if (empty($address)) {
                $address = Address::first();
            }
            $shippingAddress = [];
            $shippingAddress['name']        = $customer->name;
            $shippingAddress['email']       = $customer->email;
            $shippingAddress['phone']       = $request->phone ?? $customer->phone;
            $shippingAddress['country']     = "Egypt";
            $shippingAddress['address']     = "Pick Up From Store";
            $shippingAddress['state']       = "Pick Up From Store";
            $shippingAddress['city']        = "Pick Up From Store";
            $shippingAddress['postal_code'] = "20";
        }

        $carts = $this->createCart($request, $customer, $address, $shippingCost);
        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->back();
        }
        $combined_order = new CombinedOrder;
        $combined_order->user_id = $customer->id;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();
        $seller_products = array();
        foreach ($carts as $cartItem) {
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
            $order->user_id = $customer->id;
            $order->guest_id = $request->session()->get('temp_user_id') ?? null;
            $order->shipping_address = $combined_order->shipping_address;
            $order->additional_info = $request->additional_info;
            $order->name = $request->name ?? $customer->name;
            $order->phone = $request->phone ?? $customer->phone;
            $order->email = $request->email ?? $customer->email;
            $order->address_id = $address->id;

            $order->payment_type = $request->payment_option;
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');
            $order->save();
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;


            foreach ($seller_product as $cartItem) {
                $product = Product::find($cartItem['product_id']);
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                $tax +=  $cartItem['tax'] * $cartItem['quantity'];
                $product_variation = $cartItem['variation'];
                $product_stock = $product->stocks->where('variant', $product_variation)->first();
                if ($product_stock == null) {
                    $product_stock = $product->stocks->where('variant', "")->first();
                }
                $qty = $product_stock->qty;

                if ($product->digital != 1 && $cartItem['quantity'] > $qty) {
                    flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
                    $order->delete();
                    return redirect()->back();
                } elseif ($product->digital != 1) {
                    $product_stock->qty -= $cartItem['quantity'];
                    $product_stock->save();
                }
                if (get_setting('shipping_type') == "product_wise_shipping") {
                    $shippingCost = 0;
                    $shippingCost += $product->shipping_cost;
                }
                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
                $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
                $order_detail->shipping_type = $cartItem['shipping_type'];

                $order_detail->product_referral_code = $cartItem['product_referral_code'];

                $price_type = $request->product_info;
                switch ($price_type) {
                    case $product->price:
                        $order_detail->price_type = 'unit_price';
                        break;
                    case $product->wholesale_price:
                        $order_detail->price_type = 'wholesale_price';
                        break;
                    case $product->wholesale_price_variant:
                        $order_detail->price_type = 'wholesale_price_variant';
                        break;
                    default:
                        $order_detail->price_type = 'unit_price';
                        break;
                }


                if (get_setting('shipping_type') == "flat_rate") {
                    $order_detail->shipping_cost = $cartItem['shipping_cost'] / count($seller_product);
                } else {
                    $order_detail->shipping_cost = $cartItem['shipping_cost'];
                }

                $shipping = $shippingCost;
                $order_detail->quantity = $cartItem['quantity'];

                if (addon_is_activated('club_point')) {
                    $order_detail->earn_point = $product->earn_point;
                }
                $order_detail->save();
                $product->num_of_sale += $cartItem['quantity'];
                $product->save();
                $order->seller_id = $product->user_id;
                $order->shipping_type = $cartItem['shipping_type'];

                if ($cartItem['shipping_type'] == 'pickup_point') {
                    $order->pickup_point_id = $cartItem['pickup_point'];
                }
                if ($cartItem['shipping_type'] == 'carrier') {
                    $order->carrier_id = $cartItem['carrier_id'];
                }
                if (addon_is_activated('affiliate_system')) {
                    if ($order_detail->product_referral_code) {
                        $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();
                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
                    }
                }
            }
            $taxForAdmin = 0;
            $tax_value = $request->tax;
            $discount_value = $request->discount;
            if ($request->tax_type == "fixed") {
                $taxForAdmin = $request->tax;
            } elseif ($request->tax_type == "percent") {
                $taxForAdmin = ($request->tax * $subtotal) / 100;
            }
            if ($request->discount_type == "fixed") {
                $coupon_discount = $request->discount;
                $discount_type = 'fixed';
            } elseif ($request->discount_type == "percent") {
                $coupon_discount = ($request->discount * $subtotal) / 100;
                $discount_type = 'percent';
            }
            if ($request->shipping_method == "pick_up_from_store") {
                $shipping = 0;
            }
            if ($request->shipping_method == "pick_up_from_store") {
                $order->payment_status = "paid";
                $order->payment_type = "cash";
            } else {
                if ($request->is_paid == "on") {
                    $is_paid = "paid";
                    $update_status = OrderDetail::where('order_id', $order->id)->update([
                        'payment_status' => 'paid'
                    ]);
                } else {
                    $is_paid = "unpaid";
                    $update_status = OrderDetail::where('order_id', $order->id)->update([
                        'payment_status' => 'unpaid'
                    ]);
                }
                $order->payment_status = $is_paid;
                $order->payment_type = $request->payment_type ?? "cash";
            }
            $order->AddedTax = $taxForAdmin;
            $order->tax_type = $request->tax_type ?? null;
            $total = $subtotal - $coupon_discount;
            $order->grand_total = $total + $tax + $shipping + $taxForAdmin;
            $order->coupon_discount = $coupon_discount;
            $order->discount_type = $discount_type;
            $order->tax_value = $tax_value;
            $order->discount_value = $discount_value;
            $combined_order->grand_total += $order->grand_total;
            $order->save();
        }
        $combined_order->save();



        $cart = Cart::where('user_id', $customer->id)->where('is_admin', '1')->get();
        foreach ($cart as $cartItem) {
            $cartItem->delete();
        }

        if ($request->is_open == "on") {
            $is_open = '1';
        } else {
            $is_open = '0';
        }

        if ($request->is_shipping == "on") {
            $is_shipping = '1';
        } else {
            $is_shipping = '0';
        }

        $order->is_open = $is_open;
        $order->is_shipping = $is_shipping;
        $order->added_by_admin = '1';
        if ($request->shipping_method == "shipping_company" && isset($shippingData)) {
            $order->other_shipping_company = json_encode($shippingData);
            $order->save();
            // $this->otherShippingCompanyAddressPdf($shippingData);
        } else {
            $order->save();
        }

        flash(translate('Your Order Has Been Submitted Successfully'))->success();
        return redirect()->route('all_orders.index');
    }

    private function createCustomer($request)
    {


        if ($request->customer_id == 0 && $request->phone == null) {
            flash(translate('Please Enter Phone Number'))->warning();
            return redirect()->back();
        }

        if ($request->customer_id == 0) {
            $customer = new User();
            $customer->name = $request->client_name;
            $customer->phone = '+2' . $request->phone;
            $customer->email = $request->email;
            $password = Str::random(12);
            $customer->password = bcrypt($password);
            $customer->save();
        } else {
            $customer = User::find($request->customer_id);
            if ($customer->phone == null && $request->phone == null) {
                flash(translate('Please Enter Phone Number'))->warning();
                return redirect()->back();
            }
            if ($customer->phone == null && $request->phone != null) {
                $customer->phone = '+2' . $request->phone;
                $customer->save();
            }
        }
        return $customer;
    }


    private function CreateShippingMethod($request, $customer)
    {
        if ($request->shipping_method == "home_delivery") {
            $address = new Address();
            $address->country_id = 64;
            $address->state_id = $request->state_id;
            $address->city_id = $request->city_id;
            $address->user_id = $customer->id;
            $address->postal_code = $request->postal_code;
            $address->phone = $customer->phone;
            $address->address = $request->address;
            $address->Save();



            $shippingAddress = [];
            $shippingAddress['name']        = $customer->name;
            $shippingAddress['email']       = $customer->email;
            $shippingAddress['phone']       = $customer->phone;

            $shippingAddress['address']     = $address->address;
            $shippingAddress['country']     = $address->country->name;
            $shippingAddress['state']       = $address->state->name;
            $shippingAddress['city']        = $address->city->name;
            $shippingAddress['postal_code'] = $address->postal_code;
        } else {

            $address = Address::where('user_id', auth()->user()->id)->first();
            $shippingAddress = [];
            $shippingAddress['name']        = $customer->name;
            $shippingAddress['email']       = $customer->email;
            $shippingAddress['phone']       = $customer->phone;

            $shippingAddress['country']     = "Egypt";
            $shippingAddress['address']     = "Pick Up From Store";
            $shippingAddress['state']       = "Pick Up From Store";
            $shippingAddress['city']        = "Pick Up From Store";
            $shippingAddress['postal_code'] = "Pick Up From Store";
        }


        return [$address, $shippingAddress];
    }



    private function createCart($request, $customer, $address, $shippingCost)
    {

        $shipping_Method = $request->shipping_method;
        if ($shipping_Method == "pick_up_from_store") {
            $shippingCost = 0;
        }
        if (get_setting('shipping_type') == "flat_rate") {
            $shippingCost =  get_setting('flat_rate_shipping_cost');
        } elseif (get_setting('shipping_type') == "area_wise_shipping" && $request->shipping_method != "pick_up_from_store" && $request->shipping_method != null) {
            $city = City::find($request->city_id);
            $shippingCost =  $city->cost;
        }

        $productDetails = [];

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'product_info') === 0) {
                $productIndex = substr($key, strlen('product_info'));
                $productQuantityKey = "product_quantity{$productIndex}";
                $productIdKey = "product_id{$productIndex}";
                if (array_key_exists($productQuantityKey, $request->all()) && array_key_exists($productIdKey, $request->all())) {
                    $productDetails[] = [
                        'id' => $request->input($productIdKey),
                        'price' => $value,
                        'quantity' => $request->input($productQuantityKey),
                    ];
                }
            }
        }

        $products = [];
        $variations = [];


        foreach ($productDetails as $productDetail) {
            $id = $productDetail['id'];
            if (Str::startsWith($id, 'P_')) {
                $products[$id] = $productDetail;
            } elseif (Str::startsWith($id, 'V_')) {
                $variations[$id] = $productDetail;
            }
        }
        foreach ($products as $productId => $productDetail) {
            $product = Product::find(Str::after($productId, 'P_'));
            $tax = $product->taxes[0];
            $taxAmount = 0;
            if ($tax != null) {
                if ($tax->type == 'amount') {
                    $taxAmount = $tax->tax;
                } else {
                    $taxAmount = $productDetail['price'] * $tax->tax / 100;
                }
            }
            $cart = new Cart;
            $cart->product_id = $product->id;
            $cart->price = $productDetail['price'];
            $cart->quantity = $productDetail['quantity'];
            $cart->variation = "";
            $cart->tax = $taxAmount;
            $cart->user_id = $customer->id ?? $customer;
            $cart->address_id = $address->id;
            $cart->shipping_cost = $shippingCost;
            $cart->shipping_type = $shipping_Method;
            $cart->is_admin = '1';
            $cart->save();
        }
        foreach ($variations as $variationId => $variationDetail) {
            $productId = Str::after($variationId, 'V_');
            $stock = ProductStock::find($productId);
            $product = Product::find($stock->product_id);
            $tax = $product->taxes[0];
            $taxAmount = 0;

            if ($tax != null) {
                if ($tax->type == 'amount') {
                    $taxAmount = $tax->tax;
                } else {
                    $taxAmount = $variationDetail['price'] * $tax->tax / 100;
                }
            }
            $cart = new Cart;
            $cart->product_id = $product->id;
            $cart->price = $variationDetail['price'];
            $cart->quantity = $variationDetail['quantity'];
            $cart->variation = $stock->variant;
            $cart->tax = $taxAmount;
            $cart->user_id = $customer->id ?? $customer;
            // $cart->address_id = $address->id;
            $cart->shipping_cost = $shippingCost;
            $cart->shipping_type = $shipping_Method;
            $cart->is_admin = '1';
            $cart->save();
        }
        $carts = Cart::where('user_id', $customer->id ?? $customer)->where('is_admin', '1')->get();


        return $carts;
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
        if ($order->payment_status == "paid") {
            $total = 0;
        } else {
            $total = $order->grand_total;
        }
        $shippingDetail = json_decode($order->shipping_address);

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
            'government' => $shipping_address['shipping_state'],
            'area' => $shipping_address['shipping_city'],
            'address' => $shippingDetail->address,
            'return_amount' => 0,
            'is_order' => 0,
            'order_summary' => $productNamesString,
            'amount_to_be_collected' => $total,
            'can_open' => $is_open,
            'notes' => $order->additional_info,
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
            flash(translate('Your Been Has Been Submitted To The Shipping Company Successfully'))->success();
        } else {
            flash(translate('Some Error Happended While Sending Order To The Shipping Company'))->warning();
        }
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        }
        curl_close($ch);
    }


    private function update_shipping($id, $is_open)
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

        if ($order->payment_status == "paid") {
            $total = 0;
        } else {
            $total = $order->grand_total;
        }
        $shippingDetail = json_decode($order->shipping_address);
        $data = array(
            'authentication_key' => env('TURBO_AUTHENTICATION_KEY'),
            'main_client_code' =>  env('TURBO_CLIENT_Code'),
            'code' => $order->shipping_barcode,
            'receiver' => $shipping_address['name'],
            'phone1' => $shipping_address['phone'],
            // 'government' => $governments->name,
            // 'area' => 'السيدة زينب',
            // 'address' => $shippingDetail->address,
            // 'government' => 'القاهرة'   ,
            'government' => $shipping_address['shipping_state'],
            'area' => $shipping_address['shipping_city'],
            'address' => $shippingDetail->address,
            'return_amount' => 0,
            'is_order' => 0,
            'order_summary' => $productNamesString,
            'amount_to_be_collected' => $total,
            'can_open' => $is_open,
            'notes' => $order->additional_info,
        );
        $jsonData = json_encode($data);
        $apiUrl = env('TURBO_EDIT_ORDER_URL');
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
            flash(translate('Order Has Been Updated At The Shipping Company'))->success();
        } else {
            flash(translate('Some Error Happended While Sending Order To The Shipping Company'))->warning();
        }

        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        }

        curl_close($ch);
    }

    public function otherShippingCompanyAddressPdf(Order $order)
    {

        if (empty($order->other_shipping_company)) {
            abort(404);
        }

        $data = json_decode($order->other_shipping_company, true);

        $language_code = Session::get('locale', Config::get('app.locale'));


        if (Language::where('code', $language_code)->first()->rtl == 1) {
            $direction = 'rtl';
            $text_align = 'right';
            $not_text_align = 'left';
        } else {
            $direction = 'ltr';
            $text_align = 'left';
            $not_text_align = 'right';
        }

        if ($language_code == 'bd') {
            // bengali font
            $font_family = "'Hind Siliguri','sans-serif'";
        } elseif ($language_code == 'kh') {
            // khmer font
            $font_family = "'Hanuman','sans-serif'";
        } elseif ($language_code == 'sa' || $language_code == 'ir' || $language_code == 'om' || $language_code == 'jo') {
            // middle east/arabic/Israeli font
            $font_family = "'Baloo Bhaijaan 2','sans-serif'";
        } else {
            // general for all
            $font_family = "'Roboto','sans-serif'";
        }

        $config = [];
        return PDF::loadView('backend.pdf.shipping_data', [
            'shipping_address' => $data,
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align
        ], [], $config)->download($data['company_name'] . '.pdf');
    }

    public function add_notes(Request $request)
    {
        $orderId = $request->input('order_id');
        $note = $request->input('note');

        DB::table('order_notes')->insert([
            'order_id' => $orderId,
            'user_id' => auth()->user()->id,
            'notes' => $note,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => translate('Your Note has been submitted successfully'),
        ]);
    }
    public function fizpaTrackingOrder($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fizzapi.anyitservice.com/api/Tracking/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: UF3M0Q1F7W5ZLWWCN2DLZQBDYDTLMMN6F5HUQ1ABHJH7K5Y17KGSJR5EDZWW5P1UI7UOPCVV1BEPJD11O1NCG3XWADQZENF0QVYL",
            "Referer: https://shal.store"
        ));
        $response = curl_exec($ch);
         $responseData = json_decode($response, true);

        $order = Order::where('tracking_code', $id)->first();
        
        $order->shipping_company_status = $responseData[0]['Note'];
        $order->save();
        DB::table('order_tracking')->insert([
           'order_id' => $order->id,
           'shipping_company' => 'JANA',
            'tracking_info' =>  json_encode($responseData)
        ]);

    }
    public function aymakanTrackingOrder($tracking_code)
    {

        $authorizationToken = env('AYMAKAN_AUTH_KEY');

        $url = "https://api.aymakan.net/v2/shipping/track/{$tracking_code}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: ' . $authorizationToken
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            curl_close($ch);
            return "Error: " . $error_message;
        }
        curl_close($ch);
        $responseData = json_decode($response, true);
        $order = Order::where('tracking_code',$tracking_code)->first();
        $order->tracking_info = json_encode($responseData['data']['shipments'][0]['tracking_info']);
        DB::table('order_tracking')->insert([
           'order_id' => $order->id,
           'shipping_company' => 'Aymakan',
            'tracking_info' =>  json_encode($responseData['data']['shipments'][0]['tracking_info'])
        ]);
        $order->save();
        flash(translate('Orders are being processed'))->success();
        return redirect()->route('all_orders.index');
    }
    public function update_shipping_status($id)
    {
        $order = Order::find($id);
        $carrier = Carrier::find($order->carrier_id);
        if (isset($carrier) && $carrier->name == 'AyMakan') {
             return $this->aymakanTrackingOrder($order->tracking_code);
                          flash(translate('Orders are being processed'))->success();
            return redirect()->route('all_orders.index');

        } elseif (isset($carrier) && $carrier->name == 'JANA') {
              $this->fizpaTrackingOrder($order->tracking_code);
            flash(translate('Orders are being processed'))->success();
            return redirect()->route('all_orders.index');
        } else {
            flash(translate('No Carrier Available'))->success();
            return redirect()->route('all_orders.index');
        }
    }
}
