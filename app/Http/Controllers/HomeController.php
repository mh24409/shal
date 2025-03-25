<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Mail;
use Cache;
use Cookie;
use App\Models\Page;
use App\Models\Shop;
use App\Models\User;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\OrderDetail;
use App\Models\PickupPoint;
use Illuminate\Support\Str;
use App\Models\ProductQuery;
use Illuminate\Http\Request;
use App\Models\AffiliateConfig;
use App\Models\CustomerPackage;
use App\Utility\CategoryUtility;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Auth\Events\PasswordReset;
use App\Mail\SecondEmailVerifyMailManager;
use Illuminate\Support\Facades\Http;
use App\Traits\ConversionApiTrait;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\AttributeCategory;
use App\Models\Color;
use Illuminate\Support\Facades\Session;
use App\Traits\TikTokConverasionTrait;

class HomeController extends Controller
{
    use ConversionApiTrait ,TikTokConverasionTrait;
    public function FetchCountries(Request $request)
    {

        $requestData = [
            "ClientInfo" => [
                "UserName" => "testingapi@aramex.com",
                "Password" => "R123456789\$r",
                "Version" => "v1",
                "AccountNumber" => "987654",
                "AccountPin" => "226321",
                "AccountEntity" => "CAI",
                "AccountCountryCode" => "EG",
            ],
            "Transaction" => [
                "Reference1" => "",
                "Reference2" => "",
                "Reference3" => "",
                "Reference4" => "",
                "Reference5" => "",
            ],
        ];
        $response = Http::withOptions(['verify' => false])->post('https://ws.dev.aramex.net/ShippingAPI.V2/Location/Service_1_0.svc/json/FetchCountries', [
            'json' => $requestData,
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            // Process the response data
        } else {
            dd($response);
            return response()->json(['error' => 'Failed to fetch countries'], 500);
        }
    }



    public function index($login = null)
    {

        app()->setLocale("sa");

        Session::put('locale', "sa");

        $featured_categories = Cache::rememberForever('featured_categories', function () {
            return Category::withCount('products')->where('parent_id', 0)->where('featured', 1)->get();
        });
        $categories = Cache::rememberForever('categories', function () {
            return Category::get();
        });
        $parent_categories = Cache::rememberForever('categories', function () {
            return Category::withCount('products')->where('parent_id', 0)->get();
        });

        $child_categories =  Category::withCount('products')->where('parent_id', '!=', 0)->get();

        $todays_deal_products = Cache::rememberForever('todays_deal_products', function () {
            return filter_products(Product::where('published', 1)->where('todays_deal', '1'))->get()->filter(function ($product) {
                return $product->all_stocks_qty > 0;
            });
        });
        $featured_products = Cache::remember('featured_products', 3600, function () {
            return filter_products(Product::latest())->where('featured', 1)->limit(24)->get()->filter(function ($product) {
                return $product->all_stocks_qty > 0;
            });
        });
        $newest_products = Cache::remember('newest_products', 3600, function () {
            return filter_products(Product::latest())->limit(8)->get()->filter(function ($product) {
                return $product->all_stocks_qty > 0;
            });
        });
        $brands = Cache::remember('brands', 3600, function () {
            return  Brand::latest()->get();
        });
        if(Auth::check() && $login != 1 ){
            $login = null ;
        }  
        return view('frontend.index', compact('brands','login', 'featured_categories', 'featured_products', 'categories', 'todays_deal_products', 'newest_products', 'parent_categories', 'child_categories'));
    }


    public function login()
    {
        return  $this->index($login = 1);
        if (Auth::check()) {
            return redirect()->route('home');
        }

        if (Route::currentRouteName() == 'seller.login' && get_setting('vendor_system_activation') == 1) {
            return view('frontend.seller_login');
        } else if (Route::currentRouteName() == 'deliveryboy.login' && addon_is_activated('delivery_boy')) {
            return view('frontend.deliveryboy_login');
        }
        return view('frontend.user_login');
    }

    public function registration(Request $request)
    {
        return  $this->index( $login = 1);
        if (Auth::check()) {
            return redirect()->route('home');
        }
        if ($request->has('referral_code') && addon_is_activated('affiliate_system')) {
            try {
                $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute = 30 * 24;
                if ($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }

                Cookie::queue('referral_code', $request->referral_code, $cookie_minute);
                $referred_by_user = User::where('referral_code', $request->referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            } catch (\Exception $e) {
            }
        }
        return view('frontend.user_registration');
    }

    public function cart_login(Request $request)
    {
        $user = null;
        if ($request->get('phone') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('phone', "+{$request['country_code']}{$request['phone']}")->first();
        } elseif ($request->get('email') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
        }

        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                if ($request->has('remember')) {
                    auth()->login($user, true);
                } else {
                    auth()->login($user, false);
                }
            } else {
                flash(translate('Invalid email or password!'))->warning();
            }
        } else {
            flash(translate('Invalid email or password!'))->warning();
        }
        return back();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if (Auth::user()->user_type == 'seller') {
            return redirect()->route('seller.dashboard');
        } elseif (Auth::user()->user_type == 'customer') {
            return view('frontend.user.customer.dashboard');
        } elseif (Auth::user()->user_type == 'delivery_boy') {
            return view('delivery_boys.dashboard');
        } else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
        if (Auth::user()->user_type == 'seller') {
            return redirect()->route('seller.profile.index');
        } elseif (Auth::user()->user_type == 'delivery_boy') {
            return view('delivery_boys.profile');
        } else {
            return view('frontend.user.profile');
        }
    }

    public function userProfileUpdate(Request $request)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }

        $user->avatar_original = $request->photo;
        $user->save();

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();
    }

    public function flash_deal_details($slug)
    {
        $flash_deal = FlashDeal::where('slug', $slug)->first();
        if ($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }

    public function load_featured_section()
    {
        return view('frontend.partials.featured_products_section');
    }

    public function load_best_selling_section()
    {
        return view('frontend.partials.best_selling_section');
    }

    public function load_auction_products_section()
    {
        if (!addon_is_activated('auction')) {
            return;
        }
        return view('auction.frontend.auction_products_section');
    }

    public function load_home_categories_section()
    {
        return view('frontend.partials.home_categories_section');
    }

    public function load_best_sellers_section()
    {
        return view('frontend.partials.best_sellers_section');
    }

    public function trackOrder(Request $request)
    {
        if ($request->has('order_code') && $request->has('order_phone')) {
            $order = Order::where('code', $request->order_code)->where('phone', $request->order_phone)->first();
            if ($order != null) {
                return view('frontend.track_order', compact('order'));
            }
        }
        return view('frontend.track_order');
    }

    public function product(Request $request, $slug)
    {
        $detailedProduct = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop')->where('auction_product', 0)->where('slug', $slug)->where('approved', 1)->first();

        // return $detailedProduct;

        if ($detailedProduct != null && $detailedProduct->published) {

            $product_queries = ProductQuery::where('product_id', $detailedProduct->id)->where('customer_id', '!=', Auth::id())->latest('id')->paginate(3);
            $total_query = ProductQuery::where('product_id', $detailedProduct->id)->count();
            $reviews = $detailedProduct->reviews()->paginate(3);

            // Pagination using Ajax
            if (request()->ajax()) {
                if ($request->type == 'query') {
                    return Response::json(View::make('frontend.partials.product_query_pagination', array('product_queries' => $product_queries))->render());
                }
                if ($request->type == 'review') {
                    return Response::json(View::make('frontend.product_details.reviews', array('reviews' => $reviews))->render());
                }
            }

            // review status
            $review_status = 0;
            if (Auth::check()) {
                $OrderDetail = OrderDetail::with([
                    'order' => function ($q) {
                        $q->where('user_id', Auth::id());
                    }
                ])->where('product_id', $detailedProduct->id)->where('delivery_status', 'delivered')->first();
                $review_status = $OrderDetail ? 1 : 0;
            }
            if ($request->has('product_referral_code') && addon_is_activated('affiliate_system')) {
                $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute = 30 * 24;
                if ($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }
                Cookie::queue('product_referral_code', $request->product_referral_code, $cookie_minute);
                Cookie::queue('referred_product_id', $detailedProduct->id, $cookie_minute);

                $referred_by_user = User::where('referral_code', $request->product_referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            }
            $pixel_event_id = Str::random(30);
            if (get_setting('facebook_converasion_api') == 1) {
                $this->ViewContent($detailedProduct, $pixel_event_id);
            }
            TikTokConverasionTrait::TikTokViewContent($detailedProduct, $pixel_event_id);

            return view('frontend.product_details', compact('detailedProduct', 'pixel_event_id', 'product_queries', 'total_query', 'reviews', 'review_status'));
        }
        abort(404);
    }

    public function shop($slug)
    {
        $shop = Shop::where('slug', $slug)->first();
        if ($shop != null) {
            if ($shop->verification_status != 0) {
                return view('frontend.seller_shop', compact('shop'));
            } else {
                return view('frontend.seller_shop_without_verification', compact('shop'));
            }
        }
        abort(404);
    }

    public function filter_shop(Request $request, $slug, $type)
    {
        $shop = Shop::where('slug', $slug)->first();
        if ($shop != null && $type != null) {

            if ($type == 'all-products') {
                $sort_by = $request->sort_by;
                $min_price = $request->min_price;
                $max_price = $request->max_price;
                $selected_categories = array();
                $brand_id = null;
                $rating = null;

                $conditions = ['user_id' => $shop->user->id, 'published' => 1, 'approved' => 1];

                if ($request->brand != null) {
                    $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
                    $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
                }

                $products = Product::where($conditions);

                if ($request->has('selected_categories')) {
                    $selected_categories = $request->selected_categories;
                    $products->whereIn('category_id', $selected_categories);
                }

                if ($min_price != null && $max_price != null) {
                    $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
                }

                if ($request->has('rating')) {
                    $rating = $request->rating;
                    $products->where('rating', '>=', $rating);
                }

                switch ($sort_by) {
                    case 'newest':
                        $products->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $products->orderBy('created_at', 'asc');
                        break;
                    case 'price-asc':
                        $products->orderBy('unit_price', 'asc');
                        break;
                    case 'price-desc':
                        $products->orderBy('unit_price', 'desc');
                        break;
                    default:
                        $products->orderBy('id', 'desc');
                        break;
                }

                $products = $products->paginate(24)->appends(request()->query());

                return view('frontend.seller_shop', compact('shop', 'type', 'products', 'selected_categories', 'min_price', 'max_price', 'brand_id', 'sort_by', 'rating'));
            }

            return view('frontend.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    public function all_categories(Request $request)
    {
        $categories = Category::where('level', 0)->orderBy('order_level', 'desc')->get();
        return view('frontend.all_category', compact('categories'));
    }

    public function all_brands(Request $request)
    {
        $brands = Brand::all();
        return view('frontend.all_brand', compact('brands'));
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::all() as $key => $category) {
            if (is_array($request->top_categories) && in_array($category->id, $request->top_categories)) {
                $category->top = 1;
                $category->save();
            } else {
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if (is_array($request->top_brands) && in_array($brand->id, $request->top_brands)) {
                $brand->top = 1;
                $brand->save();
            } else {
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(translate('Top 10 categories and brands have been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    { 
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;
        $tax = 0;
        $max_limit = 0;
        if ($request->has('color')) {
            $str = $request['color'];
        }
        if (json_decode($product->choice_options) != null) {
            foreach (json_decode($product->choice_options) as $key => $choice) {
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                } else {
                    $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                }
            }
        }
        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;
        if ($product->wholesale_product) {
            if ($product->back_order == 1) {
                $wholesalePrice = $product_stock->wholesalePrices->first();
            } else {
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
            }
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }
        $quantity = $product->back_order == 0 ? $product_stock->qty : 1000 ;
        $max_limit = $product->back_order == 0 ? $product_stock->qty : 1000;
        $img = uploaded_asset($product_stock->image);
        if ($quantity >= 1 && $product->min_qty <= $quantity) {
            $in_stock = 1;
        } else {
            $in_stock = 0;
        }

        //Product Stock Visibility
        if ($product->stock_visibility_state == 'text') {
            if ($quantity >= 1 && $product->min_qty < $quantity) {
                $quantity = translate('In Stock');
            } else {
                $quantity = translate('Out Of Stock');
            }
        }

        //discount calculation
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        // taxes
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        $price += $tax;
        $suits = $product_stock->suits;
        $color = $product_stock->image;

        $base = $price * $request->quantity;
        $reduced = discount_in_percentage($product);
        $discount_value = $base * ($reduced / 100) ;
         
        $color_selected = Color::where('name', $request->color)->first(); 

        if ($color_selected) {
            $desiredStock = $product->stocks()->where('color_id', $color_selected->id)->get(); 
        }else{
            $desiredStock = $product->stocks; 
        }
        $Attribute_values = AttributeValue::get();
        return array(
            'attribute_values' => $Attribute_values ,
            'stocks' => $desiredStock ,
            'is_back_order' => $product->back_order ,
            'price' => $price * $request->quantity,
            'back_order' => $product->back_order,
            'quantity' => $quantity,
            'discount_value' =>  single_price($discount_value),
            'digital' => $product->digital,
            'variation' => $str,
            'color' => $color,
            'sku' => $product_stock->sku,
            'stock_id' => $product_stock->id,
            'image' => $product_stock->image,
            'max_limit' => $max_limit,
            'in_stock' => $in_stock,
            'img' => $img,
            'suits' => $suits,
            'tamara_view' => view('frontend.partials.tamara_widget', ['price_to_widget' => $price * $request->quantity])->render(),
        );
    }

    public function sellerpolicy()
    {
        $page = Page::where('type', 'seller_policy_page')->first();
        return view("frontend.policies.sellerpolicy", compact('page'));
    }

    public function returnpolicy()
    {
        $page = Page::where('type', 'return_policy_page')->first();
        return view("frontend.policies.returnpolicy", compact('page'));
    }
    public function contactus()
    {
        return view("frontend.policies.contactus");
    }

    public function supportpolicy()
    {
        $page = Page::where('type', 'support_policy_page')->first();
        return view("frontend.policies.supportpolicy", compact('page'));
    }

    public function terms()
    {
        $page = Page::where('type', 'terms_conditions_page')->first();
        return view("frontend.policies.terms", compact('page'));
    }

    public function privacypolicy()
    {
        $page = Page::where('type', 'privacy_policy_page')->first();
        return view("frontend.policies.privacypolicy", compact('page'));
    }

    public function get_pick_up_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request)
    {
        $category = Category::findOrFail($request->id);
        return view('frontend.partials.category_elements', compact('category'));
    }

    public function premium_package_index()
    {
        $customer_packages = CustomerPackage::all();
        return view('frontend.user.customer_packages_lists', compact('customer_packages'));
    }

    // public function new_page()
    // {
    //     $user = User::where('user_type', 'admin')->first();
    //     auth()->login($user);
    //     return redirect()->route('admin.dashboard');

    // }


    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if (isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = translate('Email already exists!');
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }


    // Form request
    public function update_email(Request $request)
    {
        $email = $request->email;
        if (isUnique($email)) {
            $this->send_email_change_verification_mail($request, $email);
            flash(translate('A verification mail has been sent to the mail you provided us with.'))->success();
            return back();
        }

        flash(translate('Email already exists!'))->warning();
        return back();
    }

    public function send_email_change_verification_mail($request, $email)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = translate('Email Verification');
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Verify your account');
        $array['link'] = route('email_change.callback') . '?new_email_verificiation_code=' . $verification_code . '&email=' . $email;
        $array['sender'] = Auth::user()->name;
        $array['details'] = translate("Email Second");

        $user = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status'] = 1;
            $response['message'] = translate("Your verification mail has been Sent to your email.");
        } catch (\Exception $e) {
            // return $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function email_change_callback(Request $request)
    {
        if ($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param = $request->input('new_email_verificiation_code');
            $user = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();

            if ($user != null) {

                $user->email = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();

                auth()->login($user, true);

                flash(translate('Email Changed successfully'))->success();
                if ($user->user_type == 'seller') {
                    return redirect()->route('seller.dashboard');
                }
                return redirect()->route('dashboard');
            }
        }

        flash(translate('Email was not verified. Please resend your mail!'))->error();
        return redirect()->route('dashboard');
    }

    public function reset_password_with_code(Request $request)
    {

        if (($user = User::where('email', $request->email)->where('verification_code', $request->code)->first()) != null) {
            if ($request->password == $request->password_confirmation) {
                $user->password = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                event(new PasswordReset($user));
                auth()->login($user, true);

                flash(translate('Password updated successfully'))->success();

                if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            } else {
                flash(translate("Password and confirm password didn't match"))->warning();
                return view('auth.passwords.reset');
            }
        } else {
            flash(translate("Verification code mismatch"))->error();
            return view('auth.passwords.reset');
        }
    }


    public function all_flash_deals()
    {
        $today = strtotime(date('Y-m-d H:i:s'));

        $data['all_flash_deals'] = FlashDeal::where('status', 1)
            ->where('start_date', "<=", $today)
            ->where('end_date', ">", $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return view("frontend.flash_deal.all_flash_deal_list", $data);
    }

    public function todays_deal()
    {
        $todays_deal_products = Cache::rememberForever('todays_deal_products', function () {
            return filter_products(Product::where('published', 1)->where('todays_deal', '1'))->get();
        });

        return view("frontend.todays_deal", compact('todays_deal_products'));
    }

    public function all_seller(Request $request)
    {
        $shops = Shop::whereIn('user_id', verified_sellers_id())
            ->paginate(15);

        return view('frontend.shop_listing', compact('shops'));
    }

    public function all_coupons(Request $request)
    {
        $coupons = Coupon::where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->paginate(15);
        return view('frontend.coupons', compact('coupons'));
    }

    public function inhouse_products(Request $request)
    {
        $products = filter_products(Product::where('added_by', 'admin'))->with('taxes')->paginate(12)->appends(request()->query());
        return view('frontend.inhouse_products', compact('products'));
    }
    public function common_questions()
    {
        return view('frontend.common_questions');
    }
    public function aboutus()
    {
        $page = Page::where('type', 'custom_page')->where('title', 'aboutus')->first();
        return view("frontend.policies.aboutus", compact('page'));
    }
    public function get_category_details($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if ($category->level != 0) {
            $category = Category::where('id', $category->parent_id)->first();
            if ($category->level != 0) {
                $category = Category::where('id', $category->parent_id)->first();
            }
        }
        return view('frontend.category-details', compact('category'));
    }
    public function abayat_best_selling()
    {
        if(get_setting('best_selling')==1)
        {
            $categories = Category::whereHas('products', function ($q) {
                $q->where('best_selling',1);
            })
            ->with(['products' => function ($q) {
                $q->where('best_selling',1)->orderBy('best_selling_index', 'desc')->take(9);
            }])
           ->get();
        }else{
            $categories = Category::whereHas('products', function ($q) {
            $q->where('num_of_sale', '>', 0);
            })
            ->with(['products' => function ($q) {
                $q->where('num_of_sale', '>', 0)->orderBy('num_of_sale', 'desc')->take(9);
            }])
            ->get();
        }

        return view('frontend.categories.abayat_best_selling', compact('categories'));
    }
    public function abayat_shal()
    {
         $parent_category = Category::where('name', 'عبايات شال')->first();
         $categories = Category::where('parent_id', $parent_category->id)
            ->orWhere('id', $parent_category->id)
            ->has('products')
            ->with('products')
            ->get();
        return view('frontend.categories.abayat_shall', compact('categories'));
    }
    public function abayat_150_or_less()
    {
        $products = Product::all()->filter(function ($product) {
            return home_discounted_base_price($product, false) <= 150;
        });
        return view('frontend.categories.abayat_150_or_less', compact('products'));
    }
    public function offers(Request $request, $category_id = null, $brand_id = null)
     {
        $query = $request->keyword;
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;
        $attributes = Attribute::all();
        $selected_attribute_values = array();
        $colors = Color::all();
        $selected_color = null;
        $selected_tag = $request->tag;
        $selected_event = $request->event;
        $selected_design = $request->design;

        $now = now()->toDateTimeString();

        $conditions = [
            ['published', '=', 1],
            ['discount', '>', 0],
        ];
        $products = Product::where($conditions)->where(function($query) use ($now) {
        $query->whereNull('discount_start_date')
              ->orWhere('discount_start_date', '<=', $now);
        })
        ->where(function($query) use ($now) {
            $query->whereNull('discount_end_date')
            ->orWhere('discount_end_date', '>=', $now);
        });


        $selected_close_type = $request->close_type;
        $selected_hand_type = $request->hand_type;
        $selected_fabric_type = $request->fabric_type;
         $selected_season = $request->season;
        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);}
        elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        if ($category_id != null) {
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;

            $products->whereIn('category_id', $category_ids)
            ->orWhere(function($query) use ($category_ids) {
                $query->where(function($query) use ($category_ids) {
                    foreach ($category_ids as $id) {
                        $idWithoutQuotes = trim($id, '"');
                        $query->orWhereRaw("FIND_IN_SET(?, REPLACE(sub_category_id, '\"', ''))", [$idWithoutQuotes]);
                    }
                });
            });

            $attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();
            $attributes = Attribute::whereIn('id', $attribute_ids)->get();}
        if ($min_price != null && $max_price != null) {
            $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }
        if ($query != null) {
            $searchController = new SearchController;
            $searchController->store($request);

            $products->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('product_translations', function ($q) use ($word) {
                            $q->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            });

            $case1 = $query . '%';
            $case2 = '%' . $query . '%';

            $products->orderByRaw("CASE
                WHEN name LIKE '$case1' THEN 1
                WHEN name LIKE '$case2' THEN 2
                ELSE 3
                END");
        }
        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            case 'trending':
                $products->where('trending', '1');
                break;
            case 'best_selling':
                if (get_setting('best_selling')) {
                    $products->where('best_selling', '1')->orderBy('best_selling_index', 'desc');
                } else {
                    $products->orderBy('num_of_sale', 'desc');
                }
                break;
            default:
                $products->orderBy('id', 'desc');
                break;
        }
        if ($request->has('color')) {
            $str = '"' . $request->color . '"';
            $products->where('colors', 'like', '%' . $str . '%');
            $selected_color = $request->color;
        }
        if ($request->has('selected_attribute_values')) {
            $selected_attribute_values = $request->selected_attribute_values;
            $products->where(function ($query) use ($selected_attribute_values) {
                foreach ($selected_attribute_values as $key => $value) {
                    $str = '"' . $value . '"';

                    $query->orWhere('choice_options', 'like', '%' . $str . '%');
                }
            });
        }
        if ($selected_tag != null) {
            $products->where('tags', 'like', "%{$selected_tag}%");
        }
        if($selected_event != null){
                    $products->whereHas('product_translations', function ($query) use ($selected_event) {
                        $query->where('event', 'like', "%{$selected_event}%");
                    });
        }
        if($selected_event != null){
            $products->where(function($query) use ($selected_event) {
                $query->whereJsonContains('event', $selected_event)
                    ->orWhereJsonContains('event', 'like', "%{$selected_event}%");
            });
        }
        if($selected_design != null){
            $products->where(function($query) use ($selected_design) {
                $query->whereJsonContains('design', $selected_design)
                    ->orWhereJsonContains('design', 'like', "%{$selected_design}%");
            });
        }
        if($selected_close_type != null){
            $products->where(function($query) use ($selected_close_type) {
                $query->whereJsonContains('close_type', $selected_close_type)
                    ->orWhereJsonContains('close_type', 'like', "%{$selected_close_type}%");
            });
        }
        if($selected_hand_type != null){
            $products->where(function($query) use ($selected_hand_type) {
                $query->whereJsonContains('hand_type', $selected_hand_type)
                    ->orWhereJsonContains('hand_type', 'like', "%{$selected_hand_type}%");
            });
        }
        if($selected_fabric_type != null){
            $products->where(function($query) use ($selected_fabric_type) {
                $query->whereJsonContains('fabric_type', $selected_fabric_type)
                    ->orWhereJsonContains('fabric_type', 'like', "%{$selected_fabric_type}%");
            });
        }
        if($selected_season != null){
            $products->where(function($query) use ($selected_season) {
                $query->whereJsonContains('seasons', $selected_season)
                    ->orWhereJsonContains('seasons', 'like', "%{$selected_season}%");
            });
        }

        $products = filter_products($products)->with('taxes')->paginate(24)->appends(request()->query());
        $categories = Cache::rememberForever('categories', function () {
            return Category::where('level', 0)->get();
        });
        $featuredProducts = Product::where('published',1)->where('featured',1)->get()->take(4);

        if ($request->ajax()) {
             return view('frontend.categories.offers', compact('products', 'selected_fabric_type','featuredProducts','selected_season','selected_hand_type','selected_close_type','categories', 'selected_tag', 'query','selected_event','selected_design', 'category_id', 'brand_id', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color'));
        }
        return view('frontend.categories.offers', compact('products', 'categories', 'selected_tag','featuredProducts','selected_season', 'selected_fabric_type','selected_hand_type','selected_close_type','query','selected_event','selected_design', 'category_id', 'brand_id', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color'));

    }
    public function offersByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->offers($request, $category->id);
        }
        abort(404);
    }
    public function abayat_klosh(Request $request, $category_id = null, $brand_id = null)
     {
        $query = $request->keyword;
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;
        $attributes = Attribute::all();
        $selected_attribute_values = array();
        $colors = Color::all();
        $selected_color = null;
        $selected_tag = $request->tag;
        $selected_event = $request->event;
        $selected_design = $request->design;
        $conditions = ['published' => 1];
        $selected_close_type = $request->close_type;
        $selected_hand_type = $request->hand_type;
        $selected_fabric_type = $request->fabric_type;
         $selected_season = $request->season;


        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        } elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        $products = Product::where($conditions);

        if ($category_id != null) {
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;

            $products->whereIn('category_id', $category_ids)
            ->orWhere(function($query) use ($category_ids) {
                $query->where(function($query) use ($category_ids) {
                    foreach ($category_ids as $id) {
                        $idWithoutQuotes = trim($id, '"');
                        $query->orWhereRaw("FIND_IN_SET(?, REPLACE(sub_category_id, '\"', ''))", [$idWithoutQuotes]);
                    }
                });
            });

            $attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();
            $attributes = Attribute::whereIn('id', $attribute_ids)->get();
        } else {
        }

        if ($min_price != null && $max_price != null) {
            $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }
        if ($query != null) {
            $searchController = new SearchController;
            $searchController->store($request);

            $products->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('product_translations', function ($q) use ($word) {
                            $q->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            });

            $case1 = $query . '%';
            $case2 = '%' . $query . '%';

            $products->orderByRaw("CASE
                WHEN name LIKE '$case1' THEN 1
                WHEN name LIKE '$case2' THEN 2
                ELSE 3
                END");
        }

        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            case 'trending':
                $products->where('trending', '1');
                break;
            case 'best_selling':
                if (get_setting('best_selling')) {
                    $products->where('best_selling', '1')->orderBy('best_selling_index', 'desc');
                } else {
                    $products->orderBy('num_of_sale', 'desc');
                }
                break;
            default:
                $products->orderBy('id', 'desc');
                break;
        }

        if ($request->has('color')) {
            $str = '"' . $request->color . '"';
            $products->where('colors', 'like', '%' . $str . '%');
            $selected_color = $request->color;
        }

        if ($request->has('selected_attribute_values')) {
            $selected_attribute_values = $request->selected_attribute_values;
            $products->where(function ($query) use ($selected_attribute_values) {
                foreach ($selected_attribute_values as $key => $value) {
                    $str = '"' . $value . '"';

                    $query->orWhere('choice_options', 'like', '%' . $str . '%');
                }
            });
        }
        if ($selected_tag != null) {
            $products->where('tags', 'like', "%{$selected_tag}%");
        }
                 if($selected_event != null){
                    $products->whereHas('product_translations', function ($query) use ($selected_event) {
                        $query->where('event', 'like', "%{$selected_event}%");
                    });
        }
         if($selected_event != null){
            $products->where(function($query) use ($selected_event) {
                $query->whereJsonContains('event', $selected_event)
                    ->orWhereJsonContains('event', 'like', "%{$selected_event}%");
            });
        }
        if($selected_design != null){
            $products->where(function($query) use ($selected_design) {
                $query->whereJsonContains('design', $selected_design)
                    ->orWhereJsonContains('design', 'like', "%{$selected_design}%");
            });
        }
        if($selected_close_type != null){
            $products->where(function($query) use ($selected_close_type) {
                $query->whereJsonContains('close_type', $selected_close_type)
                    ->orWhereJsonContains('close_type', 'like', "%{$selected_close_type}%");
            });
        }
        if($selected_hand_type != null){
            $products->where(function($query) use ($selected_hand_type) {
                $query->whereJsonContains('hand_type', $selected_hand_type)
                    ->orWhereJsonContains('hand_type', 'like', "%{$selected_hand_type}%");
            });
        }
        if($selected_fabric_type != null){
            $products->where(function($query) use ($selected_fabric_type) {
                $query->whereJsonContains('fabric_type', $selected_fabric_type)
                    ->orWhereJsonContains('fabric_type', 'like', "%{$selected_fabric_type}%");
            });
        }
        if($selected_season != null){
            $products->where(function($query) use ($selected_season) {
                $query->whereJsonContains('seasons', $selected_season)
                    ->orWhereJsonContains('seasons', 'like', "%{$selected_season}%");
            });
        }

        $products = filter_products($products)->with('taxes')->paginate(24)->appends(request()->query());
        $categories = Cache::rememberForever('categories', function () {
            return Category::where('level', 0)->get();
        });
          if ($request->ajax()) {
             return view('frontend.categories.abayat_klosh', compact('products', 'selected_fabric_type','selected_season','selected_hand_type','selected_close_type','categories', 'selected_tag', 'query','selected_event','selected_design', 'category_id', 'brand_id', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color'));
        }
        return view('frontend.categories.abayat_klosh', compact('products', 'categories', 'selected_tag','selected_season', 'selected_fabric_type','selected_hand_type','selected_close_type','query','selected_event','selected_design', 'category_id', 'brand_id', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color'));

    }
    public function kloshByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->abayat_klosh($request, $category->id);
        }
        abort(404);
    }
     public function summer(Request $request, $category_id = null, $brand_id = null)
    {
        $query = $request->keyword;
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;
        $attributes = Attribute::all();
        $selected_attribute_values = array();
        $colors = Color::all();
        $selected_color = null;
        $selected_tag = $request->tag;
        $selected_event = $request->event;
        $selected_design = $request->design;
        $conditions = ['published' => 1];
        $selected_close_type = $request->close_type;
        $selected_hand_type = $request->hand_type;
        $selected_fabric_type = $request->fabric_type;
         $selected_season = $request->season;


        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        } elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        $products = Product::where($conditions);

        if ($category_id != null) {
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;

            $products->whereIn('category_id', $category_ids)
            ->orWhere(function($query) use ($category_ids) {
                $query->where(function($query) use ($category_ids) {
                    foreach ($category_ids as $id) {
                        $idWithoutQuotes = trim($id, '"');
                        $query->orWhereRaw("FIND_IN_SET(?, REPLACE(sub_category_id, '\"', ''))", [$idWithoutQuotes]);
                    }
                });
            });

            $attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();
            $attributes = Attribute::whereIn('id', $attribute_ids)->get();
        } else {
        }

        if ($min_price != null && $max_price != null) {
            $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }
        if ($query != null) {
            $searchController = new SearchController;
            $searchController->store($request);

            $products->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('product_translations', function ($q) use ($word) {
                            $q->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            });

            $case1 = $query . '%';
            $case2 = '%' . $query . '%';

            $products->orderByRaw("CASE
                WHEN name LIKE '$case1' THEN 1
                WHEN name LIKE '$case2' THEN 2
                ELSE 3
                END");
        }

        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            case 'trending':
                $products->where('trending', '1');
                break;
            case 'best_selling':
                if (get_setting('best_selling')) {
                    $products->where('best_selling', '1')->orderBy('best_selling_index', 'desc');
                } else {
                    $products->orderBy('num_of_sale', 'desc');
                }
                break;
            default:
                $products->orderBy('id', 'desc');
                break;
        }

        if ($request->has('color')) {
            $str = '"' . $request->color . '"';
            $products->where('colors', 'like', '%' . $str . '%');
            $selected_color = $request->color;
        }

        if ($request->has('selected_attribute_values')) {
            $selected_attribute_values = $request->selected_attribute_values;
            $products->where(function ($query) use ($selected_attribute_values) {
                foreach ($selected_attribute_values as $key => $value) {
                    $str = '"' . $value . '"';

                    $query->orWhere('choice_options', 'like', '%' . $str . '%');
                }
            });
        }
        if ($selected_tag != null) {
            $products->where('tags', 'like', "%{$selected_tag}%");
        }
                 if($selected_event != null){
                    $products->whereHas('product_translations', function ($query) use ($selected_event) {
                        $query->where('event', 'like', "%{$selected_event}%");
                    });
        }
         if($selected_event != null){
            $products->where(function($query) use ($selected_event) {
                $query->whereJsonContains('event', $selected_event)
                    ->orWhereJsonContains('event', 'like', "%{$selected_event}%");
            });
        }
        if($selected_design != null){
            $products->where(function($query) use ($selected_design) {
                $query->whereJsonContains('design', $selected_design)
                    ->orWhereJsonContains('design', 'like', "%{$selected_design}%");
            });
        }
        if($selected_close_type != null){
            $products->where(function($query) use ($selected_close_type) {
                $query->whereJsonContains('close_type', $selected_close_type)
                    ->orWhereJsonContains('close_type', 'like', "%{$selected_close_type}%");
            });
        }
        if($selected_hand_type != null){
            $products->where(function($query) use ($selected_hand_type) {
                $query->whereJsonContains('hand_type', $selected_hand_type)
                    ->orWhereJsonContains('hand_type', 'like', "%{$selected_hand_type}%");
            });
        }
        if($selected_fabric_type != null){
            $products->where(function($query) use ($selected_fabric_type) {
                $query->whereJsonContains('fabric_type', $selected_fabric_type)
                    ->orWhereJsonContains('fabric_type', 'like', "%{$selected_fabric_type}%");
            });
        }
        if($selected_season != null){
            $products->where(function($query) use ($selected_season) {
                $query->whereJsonContains('seasons', $selected_season)
                    ->orWhereJsonContains('seasons', 'like', "%{$selected_season}%");
            });
        }

        $products = filter_products($products)->with('taxes')->paginate(24)->appends(request()->query());
        $categories = Cache::rememberForever('categories', function () {
            return Category::where('level', 0)->get();
        });
          if ($request->ajax()) {
             return view('frontend.partials.paginate_products_summer', compact('products', 'selected_fabric_type','selected_season','selected_hand_type','selected_close_type','categories', 'selected_tag', 'query','selected_event','selected_design', 'category_id', 'brand_id', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color'));
        }
        return view('frontend.categories.summer', compact('products', 'categories', 'selected_tag','selected_season', 'selected_fabric_type','selected_hand_type','selected_close_type','query','selected_event','selected_design', 'category_id', 'brand_id', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color'));

    }
    public function summerByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->summer($request, $category->id);
        }
        abort(404);
    }

}
