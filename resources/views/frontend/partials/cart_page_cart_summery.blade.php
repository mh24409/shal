<div class="d-none d-sm-none  d-md-block d-lg-block">
    <div class="mb-5">
        <strong class="h5 fs-20 fw-700 mb-0 text-capitaliz">{{ translate('Order summary') }}</strong>
    </div>
    @php
        $coupon_discount = 0;
    @endphp
    @if (get_setting('coupon_system') == 1)
        @php
            $coupon_code = null;
        @endphp

        @foreach ($carts as $key => $cartItem)
            @php
                $product = \App\Models\Product::find($cartItem['product_id']);
            @endphp
            @if ($cartItem->coupon_applied == 1)
                @php
                    $coupon_code = $cartItem->coupon_code;
                    break;
                @endphp
            @endif
        @endforeach
        @php
            $coupon_discount = carts_coupon_discount($coupon_code);
        @endphp
    @endif

    @php $subtotal_for_min_order_amount = 0; @endphp
    @foreach ($carts as $key => $cartItem)
        @php $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']; @endphp
    @endforeach
    @php
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        $product_shipping_cost = 0;
        if (isset($shipping_info) && $shipping_info != null) {
            $shipping_region = $shipping_info['city'];
        }
    @endphp
    @foreach ($carts as $key => $cartItem)
        @php
            $product = \App\Models\Product::find($cartItem['product_id']);
            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
            $product_shipping_cost = $cartItem['shipping_cost'];
            $shipping += $product_shipping_cost;
            $product_name_with_choice = $product->getTranslation('name');
            if ($cartItem['variant'] != null) {
                $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variant'];
            }
        @endphp
    @endforeach
    @if (get_setting('minimum_order_amount_check') == 1 &&
            $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
        <span class="badge badge-inline badge-primary fs-12 rounded px-2">
            {{ translate('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
        </span>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">{{ translate('subtotal') }}</span>
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">{{ single_price($subtotal + $tax) }}</span>
    </div>
    <span class="custom-hr"></span>
    {{-- <div class=" w-100 d-flex justify-content-between align-items-center  my-4">
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">{{ translate('tax') }}</span>
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">{{ single_price($tax) }}</span>
    </div> --}}
    <div class="w-100 d-flex justify-content-between align-items-center   my-4">
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">{{ translate('shpping') }}</span>
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">
            @php
                $shipping = get_setting('flat_rate_shipping_cost');
            @endphp
            @if ($shipping == 0)
                {{ translate('Free') }}
            @elseif(count($carts) >= get_setting('allowed_free_shipping_quantity') ||
                    $subtotal >= get_setting('allwed_free_shipping_discount'))
                @php
                    $shipping = 0;
                @endphp
                {{ translate('Free Shipping') }}
            @else
                {{ single_price($shipping) }}
            @endif
        </span>
    </div>
    <div class=" w-100 d-flex justify-content-between align-items-center  my-4">
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz coupon">{{ translate('coupon') }}</span>
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz coupon">{{ single_price($coupon_discount) }}</span>
    </div>
    <span class="custom-hr"></span>

    <div class="w-100 d-flex justify-content-between align-items-center mb-3">
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">{{ translate('total_cart') }}</span>
        @php
            $total = $subtotal + $tax + $shipping;
            if (Session::has('club_point')) {
                $total -= Session::get('club_point');
            }
            if ($coupon_discount > 0) {
                $total -= $coupon_discount;
            }
        @endphp
        <span class="h5 fs-20 fw-700 mb-0 text-capitaliz title">{{ single_price($total) }} <div class="fs-13 fw-400">
                {{ translate('Taxes included') }}
            </div></span>

    </div>
    {{-- <div class="fs-18 fw-700">
        {{ translate('Do You Have coupon code?') }}
    </div> --}}
    <!-- Coupon System -->
    {{-- @if (get_setting('coupon_system') == 1)
        <div class="hr"></div>
        @if ($coupon_discount > 0 && $coupon_code)
            <div class="mt-3">
                <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group position-relative coupon-container">
                        <div class="form-control w-100 h-100">{{ $coupon_code }}</div>
                        <div class="input-group-append absolute-top-right h-100">
                            <button type="button" id="coupon-remove" style="width: 115px;"
                                class="btn btn-primary main_add_to_cart_button  fs-18  h-100">{{ translate('Change Coupon') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="mt-3">
                <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                    <div class="input-group position-relative coupon-container">
                        <input type="text" class=" border-0 h-100 w-100 checkout-input" name="code"
                            onkeydown="return event.key != 'Enter';" placeholder="{{ translate('coupon code') }}" required>
                        <div class="input-group-append absolute-top-right h-100">
                            <button type="button" id="coupon-apply" style="width: 115px;padding-top: 5px;"
                                class="btn btn-primary h-100 main_add_to_cart_button fs-18">{{ translate('Apply') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    @endif --}}
    {{-- <span class="custom-hr"></span> --}}
    <div class="d-flex">
        <a @disabled($total == 0) href="{{ route('checkout.shipping_info') }}"
            class="btn btn-primary main_add_to_cart_button fs-14 fw-700 rounded-0 px-4 w-100">
            {{ translate('continue to complete the application') }}
        </a>
    </div>

</div>



<div class="d-block d-sm-block aa d-md-none d-lg-none ">
    <strong class="h5 fs-17 fw-700 my-2 text-capitaliz">{{ translate('Order summary') }}</strong>
    @php
        $coupon_discount = 0;
    @endphp
    @if (get_setting('coupon_system') == 1)
        @php
            $coupon_code = null;
        @endphp

        @foreach ($carts as $key => $cartItem)
            @php
                $product = \App\Models\Product::find($cartItem['product_id']);
            @endphp
            @if ($cartItem->coupon_applied == 1)
                @php
                    $coupon_code = $cartItem->coupon_code;
                    break;
                @endphp
            @endif
        @endforeach
        @php
            $coupon_discount = carts_coupon_discount($coupon_code);
        @endphp
    @endif

    @php $subtotal_for_min_order_amount = 0; @endphp
    @foreach ($carts as $key => $cartItem)
        @php $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']; @endphp
    @endforeach
    @php
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        $product_shipping_cost = 0;
        if (isset($shipping_info) && $shipping_info != null) {
            $shipping_region = $shipping_info['city'];
        }
    @endphp
    @foreach ($carts as $key => $cartItem)
        @php
            $product = \App\Models\Product::find($cartItem['product_id']);
            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
            $product_shipping_cost = $cartItem['shipping_cost'];
            $shipping += $product_shipping_cost;
            $product_name_with_choice = $product->getTranslation('name');
            if ($cartItem['variant'] != null) {
                $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variant'];
            }
        @endphp
    @endforeach
    @if (get_setting('minimum_order_amount_check') == 1 &&
            $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
        <span class="badge badge-inline badge-primary fs-12 rounded px-2">
            {{ translate('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
        </span>
    @endif

    <div class="d-flex justify-content-between align-items-center my-3">
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">{{ translate('subtotal') }}</span>
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">{{ single_price($subtotal) }}</span>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3">
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz text-danger">{{ translate('discount') }}</span>
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz text-danger">{{ single_price($coupon_discount) }}</span>
    </div>

    {{-- <div class=" w-100 d-flex justify-content-between align-items-center  my-4">
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">{{ translate('tax') }}</span>
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">{{ single_price($tax) }}</span>
    </div> --}}
    <!-- <div class="w-100 d-flex justify-content-between align-items-center d-none  my-4">
        <span class="h5 fs-14 fw-700 mb-0 d-none text-capitaliz">{{ translate('shpping') }}</span>
        <span class="h5 fs-14 fw-700 mb-0 d-none text-capitaliz">
            @php
                $shipping = get_setting('flat_rate_shipping_cost');
            @endphp
            @if ($shipping == 0)
{{ translate('Free') }}
@elseif(count($carts) >= get_setting('allowed_free_shipping_quantity') ||
        $subtotal >= get_setting('allwed_free_shipping_discount'))
@php
    $shipping = 0;
@endphp
                {{ translate('Free Shipping') }}
@else
{{ single_price($shipping) }}
@endif
        </span>
    </div> -->
    <!-- <div class=" w-100 d-flex justify-content-between align-items-center  my-4">
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz coupon">{{ translate('coupon') }}</span>
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz coupon">{{ single_price($coupon_discount) }}</span>
    </div> -->

    <div class="w-100 d-flex justify-content-between align-items-center mb-1">
        <span class="h5 fs-14 fw-700 mb-0 text-capitaliz">{{ translate('total') }}</span>
        @php
            $total = $subtotal + $tax + $shipping;
            if (Session::has('club_point')) {
                $total -= Session::get('club_point');
            }
            if ($coupon_discount > 0) {
                $total -= $coupon_discount;
            }
        @endphp
        <span class="h5 fs-17 fw-700 mb-0 text-capitaliz title">{{ single_price($total) }}
        </span>

    </div>
    <div class="fs-13 d-flex justify-content-end fw-400">
        {{ translate('Taxes included') }}
    </div>
    {{-- <div class="fs-18 fw-700">
        {{ translate('Do You Have coupon code?') }}
    </div> --}}
    <!-- Coupon System -->
    {{-- @if (get_setting('coupon_system') == 1)

        @if ($coupon_discount > 0 && $coupon_code)
            <div class="mt-3">
                <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group position-relative coupon-container">
                        <div class="form-control w-100 h-100">{{ $coupon_code }}</div>
                        <div class="input-group-append absolute-top-right h-100">
                            <button type="button" id="coupon-remove" style="width: 115px;"
                                class="btn btn-primary main_add_to_cart_button  fs-18  h-100">{{ translate('Change Coupon') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="mt-3">
                <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                    <div class="input-group position-relative coupon-container">
                        <input type="text" class=" border-0 h-100 w-100 checkout-input" name="code"
                            onkeydown="return event.key != 'Enter';" placeholder="{{ translate('coupon code') }}" required>
                        <div class="input-group-append absolute-top-right h-100">
                            <button type="button" id="coupon-apply" style="width: 115px;padding-top: 5px;"
                                class="btn btn-primary h-100 main_add_to_cart_button fs-18">{{ translate('Apply') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    @endif --}}
    {{-- <span class="custom-hr"></span> --}}
    <div class="d-none d-sm-none  d-md-flex d-lg-flex ">
        <a @disabled($total == 0) href="{{ route('checkout.shipping_info') }}"
            class="btn btn-primary main_add_to_cart_button fs-14 fw-700 rounded-0 px-4 w-100">
            {{ translate('continue to complete the application') }}
        </a>
    </div>



</div>
