<?php

namespace App\Http\Controllers;

use Auth;
use Cookie;
use Session;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ConversionApiTrait;
use App\Traits\TikTokConverasionTrait;

class CartController extends Controller
{
    use ConversionApiTrait ,TikTokConverasionTrait;
    public function index(Request $request)
    {
        
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            if ($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                    ->update(
                        [
                            'user_id' => $user_id,
                            'temp_user_id' => null
                        ]
                    );
                Session::forget('temp_user_id');
            }
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            // $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            $carts = ($temp_user_id != null) ? Cart::where('temp_user_id', $temp_user_id)->get() : [];
        }
        if (count($carts)>0) {
            return view('frontend.view_cart', compact('carts'));
        }else{
            return redirect()->route('search');
        }
    }

    public function showCartModal(Request $request)
    {
        $product = Product::with('category')->with('brand')->find($request->id);
        
        return array( 
                'product' => $product,
                'modal_view' =>  view('frontend.partials.addToCart', compact('product'))->render(), 
            );
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->id);
        $pixel_event_id = Str::random(30);
        $carts = array();
        $data = array();

        $data['product_name']= $product->name ;
        $data['quantity']= $request->quantity ;
        $data['pixel_event_id'] = $pixel_event_id;

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $data['user_id'] = $user_id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            if ($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            } else {
                $temp_user_id = bin2hex(random_bytes(10));
                $request->session()->put('temp_user_id', $temp_user_id);
            }
            $data['temp_user_id'] = $temp_user_id;
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        if($carts->count() >= get_setting('allowed_free_shipping_quantity') || $carts->sum('price') >=  get_setting('allwed_free_shipping_discount'))
        {
            if (auth()->user() != null) {
                Cart::where('user_id', $user_id)->update(['shipping_cost'=>0]);
            }else{
                Cart::where('temp_user_id', $temp_user_id)->update(['shipping_cost'=>0]);
            }
        }


        $data['product_id'] = $product->id;
        $data['owner_id'] = $product->user_id;

        $str = '';
        $tax = 0;
        if ($product->auction_product == 0) {
          if ($product->digital != 1 && $product->back_order == 0 && $request->quantity < $product->min_qty) {

                return array(
                    'status' => 0,
                    'msg' => translate('min quantity not satisfied'),
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.partials.minQtyNotSatisfied', ['min_qty' => $product->min_qty])->render(),
                    'nav_cart_view' => view('frontend.partials.cart_sidebar')->render(),
                    'data' => $data

                );
            }
            if ($request->has('color')) {
                $str = $request['color'];
            }

            if ($product->digital != 1) {
                foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                    if ($str != null) {
                        $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                    } else {
                        $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                    }
                }
            }

            $data['variation'] = $str;

            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;

            if ($product->wholesale_product) {
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                if ($wholesalePrice) {
                    $price = $wholesalePrice->price;
                }
            }

            $quantity = $product_stock->qty; 
                if ($product->back_order == 0 && $quantity < $request['quantity'] ) {
                           return array(
                                'status' => 0,
                                'cart_count' => count($carts),
                                'msg' => translate('Product is out of stock'),
                                'modal_view' => view('frontend.partials.outOfStockCart')->render(),
                                'nav_cart_view' => view('frontend.partials.cart_sidebar')->render(),
                                'data' => $data
                            );
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

            //calculation of taxes
            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $tax += ($price * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $tax += $product_tax->tax;
                }
            }

            $data['quantity'] = $request['quantity'];
            $data['price'] = $price;
            $data['tax'] = $tax;
            //$data['shipping'] = 0;
            $data['shipping_cost'] = 0;
            $data['product_referral_code'] = null;
            $data['cash_on_delivery'] = $product->cash_on_delivery;
            $data['digital'] = $product->digital;

            if ($request['quantity'] == null) {
                $data['quantity'] = 1;
            }


            if (Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
                $data['product_referral_code'] = Cookie::get('product_referral_code');
            }
            if ($carts && count($carts) > 0) {
                $foundInCart = false;

                foreach ($carts as $key => $cartItem) {
                    $cart_product = Product::where('id', $cartItem['product_id'])->first();
                    if ($cart_product->auction_product == 1) {
                        return array(
                            'status' => 0,
                            
                            'cart_count' => count($carts),
                            'modal_view' => view('frontend.partials.auctionProductAlredayAddedCart')->render(),
                            'nav_cart_view' => view('frontend.partials.cart_sidebar')->render(),
                            'msg' => translate('Product already added to cart'),
                            'data' => $data

                        );
                    }
                    if ($cartItem['product_id'] == $request->id) {

                        $product_stock = $cart_product->stocks->where('variant', $str)->first();
                        $quantity = $product_stock->qty;

                        if ($product->back_order == 0 && $quantity < $cartItem['quantity'] + $request['quantity'] && $cartItem['variation'] == $str) {
                            return array(
                                'status' => 0,
                                'cart_count' => count($carts),
                                'modal_view' => view('frontend.partials.outOfStockCart')->render(),
                                'nav_cart_view' => view('frontend.partials.cart_sidebar')->render(),
                                'msg' => translate('Product is out of stock'),
                                'data' => $data

                            );
                        }
                        if (($str != null && $cartItem['variation'] == $str) || $str == null) {
                            $foundInCart = true;

                            $cartItem['quantity'] += $request['quantity'];

                            if ($cart_product->wholesale_product) {
                                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                                if ($wholesalePrice) {
                                    $price = $wholesalePrice->price;
                                }
                            }
                            $cartItem['price'] = $price;
                            $cartItem->save();
                        }
                    }
                }
            if (!$foundInCart) {
                    Cart::create($data);
                    // if(get_setting('facebook_converasion_api' == 1))
                    // {
                    //     ConversionApiTrait::AddToCart($data);
                    // }

                }
            } else {
                Cart::create($data);
                // if(get_setting('facebook_converasion_api' == 1))
                // {
                //     ConversionApiTrait::AddToCart($data);
                // }
            }

            if (auth()->user() != null) {
                $user_id = Auth::user()->id;
                $carts = Cart::where('user_id', $user_id)->get();
            } else {
                $temp_user_id = $request->session()->get('temp_user_id');
                $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            }

            if(get_setting('facebook_pixel') == 1){
                $data['pixel'] = true;
            }

            if(get_setting('facebook_converasion_api' ) == 1)
            {
                ConversionApiTrait::AddToCart($data,$pixel_event_id);
            }
              TikTokConverasionTrait::TikTokAddToCart($data, $pixel_event_id);

            return array(
                'status' => 1,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.addedToCart', compact('product', 'data'))->render(),
                'nav_cart_view' => view('frontend.partials.cart_sidebar')->render(),
                'data' => $data,

            );
        } else {
            $price = $product->bids->max('amount');

            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $tax += ($price * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $tax += $product_tax->tax;
                }
            }

            $data['quantity'] = 1;
            $data['price'] = $price;
            $data['tax'] = $tax;
            $data['shipping_cost'] = 0;
            $data['product_referral_code'] = null;
            $data['cash_on_delivery'] = $product->cash_on_delivery;
            $data['digital'] = $product->digital;
            if(get_setting('facebook_pixel') == '1'){
                $data['pixel'] = true;
            }
            if (count($carts) == 0) {
                Cart::create($data);
                }
            if (auth()->user() != null) {
                $user_id = Auth::user()->id;
                $carts = Cart::where('user_id', $user_id)->get();
            } else {
                $temp_user_id = $request->session()->get('temp_user_id');
                $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            }

            if(get_setting('facebook_pixel') == 1){
                $data['pixel'] = true;
            }
            if(get_setting('facebook_converasion_api' ) == 1)
            {
                ConversionApiTrait::AddToCart($data,$pixel_event_id);
            }
             TikTokConverasionTrait::TikTokAddToCart($data, $pixel_event_id);

            return array(
                'status' => 1,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.addedToCart', compact('product', 'data'))->render(),
                'nav_cart_view' => view('frontend.partials.cart_sidebar')->render(),

                'data' => $data
            );
        }
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        Cart::destroy($request->id);
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart_sidebar')->render(),
            'cart_page_cart_summery' => view('frontend.partials.cart_page_cart_summery', compact('carts'))->render(),


        );
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::findOrFail($request->id);

        if ($cartItem['id'] == $request->id) {
            $product = Product::find($cartItem['product_id']);
            $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
            $quantity = $product->back_order == 0 ? $product_stock->qty : 1000;
            $price = $product_stock->price;

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

            if ($quantity >= $request->quantity) {
                if ($request->quantity >= $product->min_qty) {
                    $cartItem['quantity'] = $request->quantity;
                }
            }

            if ($product->wholesale_product) {
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                if ($wholesalePrice) {
                    $price = $wholesalePrice->price;
                }
            }

            $cartItem['price'] = $price;
            $cartItem->save();
        }

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart_details', compact('carts'))->render(),
            'cart_page_cart_summery_view' => view('frontend.partials.cart_page_cart_summery', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart_sidebar')->render(),
        );
    }
}
