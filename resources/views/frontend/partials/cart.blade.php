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

    <a href="javascript:void(0)" class="d-flex sm-gap align-items-center px-2  dyna-color" data-toggle="dropdown"
        data-display="static" title="{{ translate('Cart') }}">
        <i class="fas fa-shopping-cart">
        </i>
        <span class="h5 fs-14 fw-700 mb-0 text-capitalize ">{{ translate('cart') }}</span>
        <span
            class="badge badge-third badge-inline badge-pill absolute-top-right--10px">{{ isset($cart) && count($cart) > 0 ? count($cart) : 0 }}</span>
    </a>
<!-- Cart Items -->
<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg p-0 stop-propagation rounded-0 cart-lg card-dropdown z-1045 ">
    <div class="w-100 d-flex justify-content-between align-items-center p-4">
        <span class="h5 fs-14 fw-700  mb-0 text-capitalize">{{ translate('Shopping Cart') }}</span>
        <button class="button-not-button" onclick="closeDropdown('cart-lg')">
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
