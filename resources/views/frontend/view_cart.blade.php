@extends('frontend.layouts.app')

@section('content')
    @if (get_setting('cart_banner') != null && get_setting('cart_banner_small') != null)
        <div class="container p-0 position-relative  d-flex justify-content-around align-items-center">
            <img class="w-100 d-none d-lg-block" src="{{ uploaded_asset(get_setting('cart_banner')) }}" alt="">
            <img class="w-100 d-bloack d-lg-none" src="{{ uploaded_asset(get_setting('cart_banner_small')) }}" alt="">
        </div>
    @endif
    <div class="container-fluid row steps-container align-items-center  d-none d-lg-flex">
        <a href="#" class="step step-1 col-4">
            <div class="content active d-flex sm-gap">
                <h3 class="number">
                    {{ translate(' 01') }}
                </h3>
                <div class="text">
                    <h4 class="title">{{ translate('shipping cart') }}</h4>
                    <div class="subtitle">{{ translate('review your cart items') }}</div>
                </div>
            </div>
        </a>
        <a href="{{ route('checkout.shipping_info') }}" class="step step-2 col-4 ">
            <div class="content d-flex sm-gap">
                <h3 class="number">
                    {{ translate('02') }}
                </h3>
                <div class="text">
                    <h4 class="title">{{ translate('Place Order') }}</h4>
                    <div class="subtitle">{{ translate('Enter your data') }}</div>
                </div>
            </div>
        </a>
        <a href="" class="step step-3 col-4">
            <div class="content d-flex sm-gap">
                <h3 class="number">
                    {{ translate('03') }}
                </h3>
                <div class="text">
                    <h4 class="title">{{ translate('Your order is ready') }}</h4>
                    <div class="subtitle">{{ translate('Review your order') }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-md-6 " id="cart-summary">
                <div class="row">
                    @include('frontend.partials.cart_details', ['carts' => $carts])
                </div>
            </div>
            <div class="col-md-6 bg-white cart_totals" style="border:solid 2px #f1f1f1" id="cart_page_cart_summery">
                @include('frontend.partials.cart_page_cart_summery', ['carts' => $carts])
            </div>
            <div class="d-flex d-sm-flex aa d-md-none d-lg-none w-100 pt-4" style="border-radius:0 0 0 20px" >
                <a  @disabled($total == 0) href="{{ route('checkout.shipping_info') }}"
                    class="btn  main_add_to_cart_button fs-14 fw-700  px-4 w-100" style="background: linear-gradient(to right, #ED48FF, #00DEFF) !important;">
                    {{ translate('continue to complete the application') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        AIZ.extra.plusMinus();
    </script>

    <script type="text/javascript">



        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key);
        }

        function updateQuantity(key, element) {
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            }, function(data) {
                updateNavCart(data.nav_cart_view, data.cart_count);
                $('#cart-summary').html(data.cart_view);
                $('#cart_page_cart_summery').html(data.cart_page_cart_summery_view); 
            });
        }

        function showLoginModal() {
            $('#login_modal').modal();
        }
        $(document).on("click", "#coupon-apply", function() {
            var data = new FormData($('#apply-coupon-form')[0]);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('checkout.apply_coupon_code') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                    AIZ.plugins.notify(data.response_message.response, data.response_message.message);
                    location.reload();
                }
            })
        });

        $(document).on("click", "#coupon-remove", function() {
            var data = new FormData($('#remove-coupon-form')[0]);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('checkout.remove_coupon_code') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                    location.reload();
                }
            })
        })
    </script>
@endsection
