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
<!-- Cart button with cart count -->
    <a href="javascript:void(0)" data-toggle="dropdown" data-display="static" title="{{ translate('Cart') }}"
        class="h5 fs-30 fw-700  mb-0 text-capitalize position-relative  d-flex flex-column align-items-center justify-content-center">
        <svg class="svg-inline--fa fa-cart-shopping" aria-hidden="true" focusable="false" data-prefix="fas"
            data-icon="cart-shopping" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
            data-fa-i2svg="" width="20" height="20">
            <defs>
                <linearGradient id="customGradient" gradientTransform="rotate(90)">
                    <stop offset="0%" style="stop-color: #ED48FF" />
                    <stop offset="100%" style="stop-color: #00DEFF" />
                </linearGradient>
            </defs>
            <path fill="url(#customGradient)"
                d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z">
            </path>
        </svg>
        <span class="fs-15"> {{ translate('shopping') }} </span>
        <span
            class="badge badge-third badge-inline badge-pill absolute-top-right--10px">{{ isset($cart) && count($cart) > 0 ? count($cart) : 0 }}</span>
    </a>
<!-- Cart Items -->
<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg p-0 stop-propagation cart-sm rounded-0 card-dropdown z-1045 ">
    <div class="w-100 d-flex justify-content-between align-items-center p-4">
        <span class="h5 fs-14 fw-700  mb-0 text-capitalize">{{ translate('Shopping Cart') }}</span>
        <button class="button-not-button" onclick="closeDropdown('cart-sm')">
            <i class="fa-solid fa-xmark h5 fs-14 fw-700  mb-0 text-capitalize"></i>
        </button>
    </div>
    <div class="cart-body">
        @if (isset($cart) && count($cart) > 0)
            <ul class="h-360px overflow-auto c-scrollbar-light list-group list-group-flush mx-1">
                @foreach ($cart as $key => $cartItem)
                    @php
                        $product = \App\Models\Product::find($cartItem['product_id']);
                        $variationImg = \App\Models\ProductStock::where('product_id', $cartItem['product_id'])
                            ->where('variant', $cartItem->variation)
                            ->first();
                    @endphp
                    @if ($product != null)
                        <li class="list-group-item border-0 hov-scale-img">
                            <span class="d-flex align-items-center">
                                <a href="{{ route('product', $product->slug) }}"
                                    class="text-reset d-flex align-items-center flex-grow-1">

                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($variationImg->image != null ? $variationImg->image : $product->thumbnail_img) }}"
                                        class="img-fit lazyload size-60px has-transition"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    <span class="minw-0 pl-2 flex-grow-1">
                                        <span class="fw-700 fs-13 text-dark mb-2 text-truncate-2"
                                            title="{{ $product->getTranslation('name') }}">
                                            {{ $product->getTranslation('name') }}
                                        </span>
                                        <div> {{ $cartItem->variation }}</div>
                                        <span class="fs-14 fw-400 text-secondary">{{ $cartItem['quantity'] }}x</span>
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
                <h3 class="h5 fs-14 fw-700  mb-0 text-capitalize">{{ translate('Your Cart is empty') }}</h3>
            </div>
        @endif
    </div>
    @if (single_price($total) != '0.00EGP' || true)
        <div class="w-100 p-4 cust">
            <div class="w-100 mb-1">
                <a class=" fs-14 button-not-button w-100 d-flex justify-content-center align-items-center mb-3"
                    href="{{ route('cart') }}">
                    <span class="border-bottom-primary">{{ translate('cart') }}</span> </a>
            </div>
            <div class="w-100">
                <a class="fs-14 place-order-button w-100 d-flex justify-content-between align-items-center"
                    @if (single_price($total) != '0.00EGP') href="{{ route('checkout.shipping_info') }}" @endif>
                    <span>{{ translate('Checkout') }}</span> <span
                        class="total-in-button">{{ single_price($total) }}</span> </a>
            </div>
        </div>
    @endif
</div>
