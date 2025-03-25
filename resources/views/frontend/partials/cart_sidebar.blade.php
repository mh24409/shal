@php
    if (auth()->user() != null) {
        $user_id = Auth::user()->id;
        $cart = \App\Models\Cart::where('user_id', $user_id)->get();
    } else {
        $temp_user_id = Session()->get('temp_user_id');
        if ($temp_user_id) {
            $cart = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
        }
    }

    $total = 0;
    if (isset($cart) && count($cart) > 0) {
        foreach ($cart as $key => $cartItem) {
            $product = \App\Models\Product::find($cartItem['product_id']);
            $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
        }
    }
@endphp
<div class="aiz-top-menu-sidebar-cart collapse-sidebar-wrap sidebar-all sidebar-left z-1035">
    <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar-cart"
        data-same=".hide-top-menu-bar"></div>
    <div style="overflow: visible;"
        class="collapse-sidebar c-scrollbar-light text-left d-flex justify-content-start align-items-start flex-column p-4">
        <button type="button" class=" hide-top-menu-bar close-sidebar" data-toggle="class-toggle"
            data-target=".aiz-top-menu-sidebar-cart">
            <i class="fa-solid fa-angle-left"></i>
        </button>
        <div class="h-100 w-100 d-flex justify-content-between align-items-center flex-column">
            <div>
                <h3 class="w-100 h5 mb-5 fs-20 fw-700  mb-0 text-capitalize text-center">
                    {{ translate('Your Cart') }}
                </h3>
                <div>
                    @if (isset($cart) && count($cart) > 0)
                        <ul class="h-360px overflow-auto c-scrollbar-light list-group list-group-flush mx-1">
                            @foreach ($cart as $key => $cartItem)
                                @php
                                    $product = \App\Models\Product::find($cartItem['product_id']);
                                    $variationImg = \App\Models\ProductStock::where('product_id', $cartItem['product_id'])
                                        ->where('variant', $cartItem->variation)
                                        ->first();
                                    if($variationImg->image != null){  
                                            $imageIdsArray = explode(',', $variationImg->image); 
                                            $firstImageId = $imageIdsArray[0];  
                                        }
                                @endphp
                                @if ($product != null) 
                                    <li class="list-group-item border-0 hov-scale-img p-0 mb-3"> 
                                        <span class="d-flex align-items-center">
                                            <a href="{{ route('product', $product->slug) }}"
                                                class="text-reset d-flex align-items-center flex-grow-1">
                                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($variationImg->image != null ? $firstImageId : $product->thumbnail_img) }}"
                                                    class="img-fit lazyload w-60px has-transition"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                <span class="minw-0 pl-2 flex-grow-1">
                                                    <span class="fw-700 fs-13 text-dark mb-2 text-truncate-2"
                                                        title="{{ $product->getTranslation('name') }}">
                                                        {{ $product->getTranslation('name') }}
                                                    </span>
                                                    <div> {{ $cartItem->variation }}</div>
                                                    <span
                                                        class="fs-14 fw-400 text-secondary">{{ $cartItem['quantity'] }}x</span>
                                                    <span
                                                        class="fs-14 fw-400 text-secondary">{{ cart_product_price($cartItem, $product) }}</span>
                                                </span>
                                            </a>
                                            <span class="">
                                                <button onclick="removeFromCart({{ $cartItem['id'] }})"
                                                    class="btn btn-sm btn-icon stop-propagation">
                                                    <i class="la la-close fs-18 fw-600 text-secondary"></i>
                                                </button>
                                            </span>
                                        </span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center p-3 align-items-center justify-content-center d-flex flex-column">
                            <h3 class="h5 fs-14 fw-700  mb-0 text-capitalize">{{ translate('Your Cart is empty') }}
                            </h3>
                        </div>
                    @endif
                </div>
            </div>
            <div class="w-100 px-4">
                    <div class="w-100 p-1 cust">
                        
                        @if(auth()->check() && auth()->user()->is_verified ==1)

                        <div class="w-100 mb-1 ">
                            <a  class=" fs-14 button-not-button w-100 d-flex justify-content-center align-items-center mb-3"
                            @if ($total != 0) href="{{ route('cart') }}" @endif>
                                <span class="border-bottom-primary">{{ translate('cart') }}</span> </a>
                        </div>
                        <div class="w-100">
                            <a style="background:black" class="fs-14 place-order-button text-white w-100 d-flex justify-content-between align-items-center"
                                @if ($total != 0) href="{{ route('checkout.shipping_info') }}" @endif>
                                <span>{{ translate('Complete_Checkout') }}</span> <span
                                    class="total-in-button">{{ single_price($total) }} </span> </a>
                        </div>
                        @else 
                        <div class="w-100 mb-1 ">
                            <a onclick="checkUserAuthAndVerified(event,'cart')" class=" fs-14 button-not-button w-100 d-flex justify-content-center align-items-center mb-3"
                            @if ($total != 0) href="{{ route('cart') }}" @endif>
                                <span class="border-bottom-primary">{{ translate('cart') }}</span> </a>
                        </div>
                        <div class="w-100">
                            <a style="background:black" onclick="checkUserAuthAndVerified(event,'checkout')" class="fs-14 place-order-button text-white w-100 d-flex justify-content-between align-items-center"
                                @if ($total != 0) href="{{ route('cart') }}" @endif>
                                <span>{{ translate('Complete_Checkout') }}</span> <span
                                    class="total-in-button">{{ single_price($total) }} </span> </a>
                        </div>
                        @endif
                        
                    </div>
            </div>
        </div>
    </div>
</div>
