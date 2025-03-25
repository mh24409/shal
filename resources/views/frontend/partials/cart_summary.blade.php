<div class="  rounded  shadow-none cart_summary_to_read_more">
    <!-- Minimum Order Amount -->
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
    @if (get_setting('minimum_order_amount_check') == 1 &&
            $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
        <span class="badge badge-inline badge-primary fs-12 rounded px-2">
            {{ translate('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
        </span>
    @endif


    <div class=" ">
        @php
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $product_shipping_cost = 0;
            if (isset($shipping_info) && $shipping_info != null) {
                $shipping_region = $shipping_info['city'];
            }
        @endphp
        <div style="height: 50px; border-bottom:solid gray 0.1px"
            class=" rounded-0 mb-4 d-flex justify-content-between align-items-center pr-2 pl-2  fs-18 ">
            <strong>{{ translate('Product') }}</strong>
            <strong class=" ">
                {{ translate('total') }}
            </strong>
        </div>
        @foreach ($carts as $key => $cartItem)
            @php
                $product = \App\Models\Product::find($cartItem['product_id']);
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $product_shipping_cost = $cartItem['shipping_cost'];
                $shipping += $product_shipping_cost;
                $product_name_with_choice = $product->getTranslation('name');
                if ($cartItem['variant'] != null) {
                    $product_name_with_choice = $cartItem['variant'];
                }
            @endphp
            <div class="  d-flex justify-content-between align-items-center  pr-2 pl-2 mb-2">
                <div class="d-flex sm-gap ">

                    <a href="{{ route('product', $product->slug) }}" class="d-none d-lg-block">
                        <img class="rounded-0" width="64" src="{{ uploaded_asset($product->thumbnail_img) }}"
                            alt="">
                    </a>
                    <div class="d-flex flex-column sm-gap fw-400">
                        <a href="{{ route('product', $product->slug) }}">
                            <strong class=" fs-15">{{ $product->getTranslation('name') }}</strong>
                        </a>
                        @if ($cartItem['variant'] != null)
                            <a href="{{ route('product', $product->slug) }}">
                                <strong class=" fs-15">{{ $product_name_with_choice }}</strong>
                            </a>
                        @endif
                        <div class="fs-15">
                            × {{ $cartItem['quantity'] }}
                        </div>
                    </div>
                </div>
                <strong class="fs-18">
                    {{ single_price(cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']) }}
                </strong>
            </div>
        @endforeach
        <input type="hidden" id="sub_total" value="{{ $subtotal }}">
        <div class="hr"></div>

        <div class="d-flex justify-content-between align-items-center fs-19 text-soft-dark">
            <strong>{{ translate('total') }}: </strong>
            <span class="fw-600 breadcrumb-old">{{ single_price($subtotal) }}</span>
        </div> 
        <div class="hr"></div>
        <div class="d-flex justify-content-between align-items-center text-soft-dark fs-19">
            <strong class="fs-19">{{ translate('Total Shipping') }}:</strong>
            <span class="fw-600 breadcrumb-old ">
                @php
                    $shipping = get_setting('flat_rate_shipping_cost');
                @endphp
                @if ($shipping == 0)
                    {{ translate('Free Shipping') }}
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
        @if (Session::has('club_point'))
            <div class="hr"></div>
            <div class="d-flex justify-content-between align-items-center px-4 fs-19 text-soft-dark">
                <strong>{{ translate('Redeem point') }}: </strong>
                <span class="fw-600 breadcrumb-old">{{ single_price(Session::get('club_point')) }}</span>
            </div>
        @endif
        @if ($coupon_discount > 0)
            <div class="hr"></div>
            <div class="d-flex justify-content-between align-items-center  fs-19 text-soft-dark">
                <strong>{{ translate('Coupon Discount') }}:</strong>
                <span class="fw-600 breadcrumb-old">{{ single_price($coupon_discount) }}</span>
            </div>
        @endif
        @php
            $total = $subtotal + $tax + $shipping;
            if (Session::has('club_point')) {
                $total -= Session::get('club_point');
            }
            if ($coupon_discount > 0) {
                $total -= $coupon_discount;
            }
        @endphp
        <div id="cash_on_delivery_tax">
            <div class="hr"></div>
            <div class="d-flex justify-content-between align-items-center d-none fs-19 text-soft-dark">
                <strong>{{ translate('Cash On Delivery Tax') }}: </strong>
                <span class="fw-600 breadcrumb-old">{{ translate('25 RS') }} </span>
            </div>
        </div>

        <div class="hr"></div>
        <div class="d-flex justify-content-between align-items-center  mb-3  fs-19 text-soft-dark">
            <strong>{{ translate('Final Total') }}:</strong>
            <div>
                <div class="d-flex justify-content-end align-items-center">
                    <span class="fw-600 breadcrumb-old" id="order_total" data-value="{{ $total }}">
                        {{ single_price($total)}}
                    </span>
                </div>
                <div class="fs-14">
                    {{ translate('Includes') }} {{ single_price($tax) }} {{ translate('Vat') }}
                </div>
            </div>

        </div>
        <div id="tamara">
            @include('frontend.partials.tamara_widget', ['price_to_widget' => $total])
        </div>
        <div class="mt-3">
            @if (addon_is_activated('club_point') && Session::has('club_point'))
                <div class="hr"></div>
                <p>
                    <button class="  button-to-link w-100 d-flex justify-content-between align-items-center"
                        type="button" data-toggle="collapse" data-target="#collapseredeem" aria-expanded="false"
                        aria-controls="collapseredeem">
                        <span>{{ translate('Redeem Poin') }}</span>
                        <span style="font-size: 12px;color: gray;">{{ translate('Click Here') }}</span>
                    </button>
                </p>
                <div class="collapse" id="collapseredeem">
                    <div class="card card-body" style="box-shadow:none;border:unset;">
                        <!-- Remove Redeem Point -->
                        @if (addon_is_activated('club_point'))
                            @if (Session::has('club_point'))
                                <div class="mt-3">
                                    <form class="" action="{{ route('checkout.remove_club_point') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group">
                                            <div class="form-control">{{ Session::get('club_point') }}</div>
                                            <div class="input-group-append">
                                                <button type="submit"
                                                    class="btn btn-primary">{{ translate('Remove Redeem Point') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
            <div class="fs-18 fw-700 text-soft-dark">
                {{ translate('Do You Have coupon code?') }}
            </div>
            <!-- Coupon System -->
            @if (get_setting('coupon_system') == 1)
                <div class="hr"></div>
                @if ($coupon_discount > 0 && $coupon_code)
                    <div class="mt-3">
                        <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group position-relative coupon-container">
                                <input type="text" value="{{ $coupon_code }}" name="code" hidden>
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
                                <input type="text" class="px-3  border-0 h-100 w-100 checkout-input" name="code"
                                    onkeydown="return event.key != 'Enter';"
                                    placeholder="{{ translate('coupon code') }}" required>
                                <div class="input-group-append absolute-top-right h-100">
                                    <button type="button" id="coupon-apply" style="width: 115px;padding-top: 5px;"
                                        class="btn btn-primary h-100 main_add_to_cart_button fs-18">{{ translate('Apply') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
