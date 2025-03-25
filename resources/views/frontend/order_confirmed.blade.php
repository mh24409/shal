@extends('frontend.layouts.app')
@php
    $first_order = $combined_order->orders->first();
@endphp
@section('content')
    <section class="py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-7 mx-auto  py-2">
                    <div class="d-flex align-items-center sm-gap my-4">
                        <div class="fs-16 fw-400 text-dark">{{ translate('Home') }}</div>
                        <div class="fs-18 fw-400 text-dark">
                            > </div>
                        <div class="fs-16 fw-400 text-dark">{{ translate('Cart') }}</div>
                        <div class="fs-18 fw-400 text-dark">
                            > </div>
                        <div class="fs-16 fw-700 text-dark">{{ translate('Your Invoice') }}</div>
                    </div>
                    <div class="d-flex sm-gap">
                        <div style="font-size: 16px !important" class="fs-18 fw-400 text-dark">{{ translate('Thanks') }}</div>
                        <div style="font-weight: 900 !IMPORTANT;font-size: 16px !important;" class="fs-18 fw-400 text-dark">{{ $first_order->name }}</div>
                        <div style="font-size: 15px !important" class="fs-18 fw-400 text-dark">{{ translate('For Be Prietier') }}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-7 mx-auto d-flex flex-column align-items-center justify-content-center ">
                    <!-- Orders Info -->
                    @foreach ($combined_order->orders as $order)
                        <div style="background: unset;border: none !important;" class="card shadow-none  w-100 rounded mt-3" style=" border:unset">
                            <div  class="card-body p-0">
                                @foreach ($order->orderDetails as $key => $orderDetail)
                                    <div class="mb-3 p-2 d-flex align-items-center md-gap border"
                                        style="border-radius: var(--border-raduis) !important;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;gap:20px;border: 2px solid #efefef !important;background:#fff">
                                        <?php
                                        $product_image = '';
                                        $variant_image = \App\Models\ProductStock::where('variant', $orderDetail->variation)->first();
                                        if ($variant_image != null) {
                                            $images = json_decode($variant_image->image, true);

                                            if ($images !== null && is_array($images) && count($images) > 0) {
                                                $product_image = $images[0];
                                            }
                                        }

                                        if (empty($product_image)) {
                                            $product = \App\Models\Product::find($orderDetail->product_id);

                                            if ($product) {
                                                $product_image = $product->thumbnail_img;
                                            }
                                        }
                                        ?>
                                        <div class="image">
                                            <img width="80px" src="{{ uploaded_asset($product_image) }}" alt="">
                                        </div>
                                        <div class="info d-flex flex-column justify-content-between " style="height:130px;display:block !important">
                                            <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"
                                                class="text-dark fw-700 fs-18">
                                                {{ $orderDetail->product->getTranslation('name') }}
                                            </a>
                                            @php
                                                $product = $orderDetail->product;
                                                $discounted_price = home_discounted_price($product);
                                                $base_price = home_base_price($product);
                                            @endphp
                                            @if ($discounted_price != $base_price)
                                                <div class="d-flex align-items-center  sm-gap">
                                                    <span  class="text-primary fw-700 fs-15 d-flex">
                                                        {{ $discounted_price }}
                                                    </span>
                                                    <del style="color: #666" class="fs-15 fw-400 font-weight-bold ml-2">
                                                        {{ $base_price }}
                                                    </del>
                                                </div>
                                            @else
                                                <span class="text-primary fw-700 fs-15 d-flex">
                                                    {{ $discounted_price }}
                                                </span>
                                            @endif
                                            <div style="bottom: -55px;position: relative;gap:60px" class="d-flex justify-content-between align-items-center">
                                                <span class="text-dark  fs-15 d-flex sm-gap">
                                                    <span class="fw-400">{{ translate('Quantity') }} :</span>
                                                    <span class="fw-700">{{ $orderDetail->quantity }}</span>
                                                </span>
                                                <span class="text-dark fw-700 fs-15 d-flex sm-gap">
                                                    <span class="fw-400">{{ translate('Total') }} :</span>
                                                    <span class="fw-700">{{ single_price($orderDetail->price) }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div id="orders_info">
                                    <div class="card" style="border-radius: var(--border-raduis) !important;border: 2px solid #f1f1f1  !important;">
                                        <div class="" id="headingorders">
                                            <h5 class="mb-0">
                                                <button style="text-decoration: none"
                                                    class="btn btn-link fs-18 text-dark fw-700 w-100 h-100 d-flex justify-content-between align-items-center"
                                                    data-toggle="collapse" data-target="#collapseorders"
                                                    aria-expanded="true" aria-controls="collapseorders">
                                                    <span>{{ translate('Order Summary') }}</span>
                                                    <i style="display:none" id="orders-plus-icon"
                                                        class="fa-solid fa-plus"></i>
                                                    <i id="orders-minus-icon" class="fa-solid fa-minus"></i>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseorders" class="collapse show" aria-labelledby="headingorders"
                                            data-parent="#orders_info">
                                            {{-- <div class="card-body read-more w-100"> --}}
                                                <div style="padding: 10px" class="card-body w-100">
                                                <table class="table fs-14 ">
                                                    <thead>
                                                        <tr >
                                                            <th style="border: none !important" class=" border-top-0">
                                                                {{ translate('Subtotal') }}</th>
                                                            <th style="border: none !important" class="text-right border-top-0 pr-0">
                                                                {{ single_price($order->orderDetails->sum('price')) }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th class=" border-top-0">{{ translate('status') }}</th>
                                                            @if ($order->delivery_status === 'pending')
                                                                <th style="color:chartreuse" class="text-right border-top-0 pr-0">
                                                                    {{ translate('Pending') }}
                                                                </th>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th class=" border-top-0">
                                                                {{ translate('Shipping') }}</th>
                                                            <th class="text-right border-top-0 pr-0">
                                                                {{ single_price($order->shipping_cost) }}
                                                            </th>
                                                        </tr>
                                                        <tr style=" border-bottom:2px solid #efefef ;color: black;">
                                                            <th class=" border-top-0">
                                                                {{ translate('Tax') }}</th>
                                                            <th class="text-right border-top-0 pr-0">
                                                                {{ single_price($order->orderDetails->sum('tax')) }}
                                                            </th>
                                                        </tr>
                                                        @if ($order->payment_type === 'cash_on_delivery')
                                                            <tr style="color: black;">
                                                                <th class=" border-top-0">
                                                                    {{ translate('Cash On Delivery') }}</th>
                                                                <th class="text-right border-top-0 pr-0">
                                                                    {{ translate('25 RS') }}</th>
                                                            </tr>
                                                        @endif
                                                        <tr style="color: red !important;border-bottom:2px solid #efefef">
                                                            <th class=" border-top-0">
                                                                {{ translate('Coupon Discount') }}</th>
                                                            <th class="text-right border-top-0 pr-0">
                                                                {{ single_price($order->coupon_discount) }}</th>
                                                        </tr>
                                                        <tr style=" color: black;">
                                                            <th class=" border-top-0">
                                                                {{ translate('Total') }}</th>
                                                            <th style="font-weight: 900" class="text-right text-primary border-top-0 pr-0">
                                                                {{ single_price($order->grand_total) }}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="orders_info_title">
                                    <div class="card" style="border-radius: var(--border-raduis) !important;border: 2px solid #f1f1f1  !important;">
                                        <div class="" id="headingorderstitle">
                                            <h5 class="mb-0">
                                                <button style="text-decoration: none"
                                                    class="btn btn-link fs-18 text-dark fw-700 w-100 h-100 d-flex justify-content-between align-items-center"
                                                    data-toggle="collapse" data-target="#collapseorderstitle"
                                                    aria-expanded="true" aria-controls="collapseorderstitle">
                                                    <span>{{ translate('Order Info') }}</span>
                                                    <i style="display:none" id="orderstitle-plus-icon"
                                                        class="fa-solid fa-plus"></i>
                                                    <i id="orderstitle-minus-icon" class="fa-solid fa-minus"></i>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseorderstitle" class="collapse show" aria-labelledby="headingorderstitle"
                                            data-parent="#orders_info_title">
                                            <div style="padding:10px" class="card-body read-more w-100">
                                                <table class="table fs-14 ">
                                                    <tbody>
                                                        <tr>
                                                            <th class=" border-top-0">
                                                                {{ translate('Order Number') }}</th>
                                                            <th class="text-right border-top-0 pr-0">
                                                                {{ $first_order->code }}
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            {{-- <th class=" border-top-0">{{ translate('Date') }}</th> --}}
                                                            <th class=" border-top-0">{{ translate('Expected time of delivery') }}</th>
                                                            <?php
                                                                $delivery_date_timestamp = $first_order->date + (5 * 24 * 60 * 60); // Add 5 days in seconds
                                                                $delivery_date = date('d-m-Y', $delivery_date_timestamp);
                                                            ?>
                                                            <th class="text-right border-top-0 pr-0">
                                                                {{$delivery_date}}
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th class=" border-top-0">
                                                                {{ translate('Payment Method') }}</th>
                                                            <th class="text-right border-top-0 pr-0">
                                                                {{ translate(ucfirst(str_replace('_', ' ', $first_order->payment_type))) }}
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="card-body">
                                                <a style="    width: 100%;  display: inline-block; text-align: center;" href="{{ route('invoice_download',$order->id) }}" class="dark-button-style w-100 py-2 fs-18" >{{ translate('Print Invoice') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
@endsection


@section('script')
    <script>
        (function frame() {
            // launch a few confetti from the left edge
            confetti({
                particleCount: 2500,
                angle: 60,
                spread: 500,
                origin: {
                    x: 0
                }
            });
            // and launch a few from the right edge
            confetti({
                particleCount: 2500,
                angle: 120,
                spread: 500,
                origin: {
                    x: 1
                }
            });
        }());
    </script>
    @if (get_setting('facebook_pixel') == 1)
        @php
            $orders = $combined_order->orders()->pluck('id');
            $product_ids = App\Models\OrderDetail::whereIn('order_id', $orders)->pluck('product_id');

            $products_name = App\Models\Product::whereIn('id', $product_ids)->pluck('name');

        @endphp

        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');

            var currentDay = new Date().getDate();
            var currentMonth = new Date().getMonth() + 1;

            var timeZone = 'Africa/Cairo';
            var formattedTime = new Date().toLocaleTimeString('en-US', {
                timeZone,
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            var order_id = @json($first_order->id);
            var order_date = @json($first_order->date);
            var total = @json($combined_order->grand_total);
            var product_ids = @json($product_ids);


            var products_name = @json($products_name);



            var data = {

                event_day: new Date().getDate(),
                event_month: new Date().getMonth() + 1,

                page_title: document.title,
                page_url: window.location.href,
                total_price: total,
                //items_variations : variations,
                order_id: order_id,
                order_date: order_date,
                items_names: products_name,
                items_ids: product_ids,
                page_title: document.title,
                page_url: window.location.href,
                content_type: 'product',
                event_time: formattedTime
            };
            fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}');
            fbq('track', 'Purchase', data, {
                event_id: "{{ $pixel_event_id }}"
            });
            $('.read-more').readall({
                showheight: 300,
                showrows: null,
                animationspeed: 200,
                btnTextShowmore: '{{ translate('View More') }}',
                btnTextShowless: '{{ translate('View Less') }}',
                btnClassShowmore: 'readall-button',
                btnClassShowless: 'readall-button'
            });
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}&ev=PageView&noscript=1" />
        </noscript>
    @endif
    
    
         <script>
            var email = '{{ Auth::user()->email ?? "no-email" }}';
            var phone = '{{ Auth::user()->phone ?? "no-phone" }}';
            var order_id = {{ $first_order->id }};
            var order_date = '{{ $first_order->date }}';
            var total = {{ $combined_order->grand_total }};
            var product_ids = @json($product_ids);
            var products_name = @json($products_name);
        
            var data = {
                price: total,
                currency: 'SAR',
                item_ids: product_ids,
                item_category: 'static',
                number_items: product_ids.length,
                brands: ['shal'],
                firstname: '{{ $first_order->name }}',
                client_dedup_id: '{{ $pixel_event_id }}'
            };
        
            if (phone !== 'no-phone' && email !== 'no-email') {
                data.user_email = email;
                data.user_phone_number = phone;
            } else if (phone !== 'no-phone') {
                data.user_phone_number = phone;
            } else if (email !== 'no-email') {
                data.user_email = email;
            }
        
            snaptr('init', '{{ env("SNAPCHAT_PIXEL_ID") }}');
            snaptr('track', 'PURCHASE', data);
        </script>
        
           <script>
            var placeAnOrderContentsArray = [];
            var product_details = @json($product_details);
            product_details.forEach(function(product_detail) {
                var contentObject = {
                    "content_id": product_detail.product_id,
                    "content_type": "product",
                    "content_name": product_detail.product_name,
                };
                placeAnOrderContentsArray.push(contentObject);
            });
            ttq.track('PlaceAnOrder', {
                "contents": placeAnOrderContentsArray,
                "value": total,
                "currency": "SAR",
            });
        </script>
        @if ($payment_event)
            <script>
                var completePaymentContentsArray = [];
                var Paymentproduct_details = @json($product_details);
                var PaymentTotal = total;
                Paymentproduct_details.forEach(function(Paymentproduct_detail) {
                    var PaymentcontentObject = {
                        "content_id": Paymentproduct_detail.product_id,
                        "content_type": "product",
                        "content_name": Paymentproduct_detail.product_name,
                    };
                    completePaymentContentsArray.push(PaymentcontentObject);
                });
                ttq.track('CompletePayment', {
                    "contents": completePaymentContentsArray,
                    "value": PaymentTotal,
                    "currency": "SAR",
                });
            </script>
        @endif

 @endsection
