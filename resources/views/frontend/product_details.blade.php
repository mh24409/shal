@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator"
        content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:price:currency"
        content="{{ \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">


@endsection

@section('content')
    <section class="mb-4 pt-3 d-flex justify-content-center">
        <div class="custom-container">
            <div class=" d-flex align-items-center py-3">
                <a href="{{ route('home') }}"
                    class="h5 fs-13 fw-400 mb-0 text-capitalize d-inline-block hover-this-link " >
                    {{ translate('home') }}
                </a>
                <span class="mx-1" >|</span>
                <a href="{{ route('home') }}"
                    class="h5 fs-13 fw-400 mb-0 text-capitalize d-inline-block hover-this-link ">
                    {{ $detailedProduct->category->getTranslation('name') }}
                </a>
                <span class="mx-1">|</span>
                <span class="h5 fs-13 fw-700 mb-0 text-capitalize " >
                    {{ $detailedProduct->getTranslation('name') }}
                </span>
            </div>
            <div class=" py-3">
                <div class="row">
                    <!-- Product Image Gallery -->
                    <div class="col-xl-7 col-lg-8 mb-4 position-relative justify-content-center w-100">
                        @if (count(json_decode($detailedProduct->colors)) > 0)
                            <span style="right: 17px; z-index: 50;top:10px" class="absolute-top-left product_status_badge ">{{ translate('have many colors') }}</span>
                        @endif
                        @include('frontend.product_details.image_gallery')
                    </div>

                    <!-- Product Details -->
                    <div class="col-xl-5 col-lg-4">
                        @include('frontend.product_details.details')
                    </div>
                </div>
            </div>
            <div class="product-nav" >
                <nav>
                    <div class="nav nav-tabs sm-gap" id="nav-tab" role="tablist">
                      <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ translate('customer reviews') }}</button>
                      <button class="nav-link " id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">{{ translate('common questions') }}</button>
                    </div>
                  </nav>
                  <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    @include('frontend.product_details.review_section')

                    </div>
                    <div class="tab-pane fade " id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    @include('frontend.product_details.product_queries')

                    </div>
                  </div>
            </div>
        </div>
    </section>
    @include('frontend.product_details.related_products')


@endsection

@section('modal')
    <!-- Image Modal -->
    <div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="p-4">
                    <div class="size-300px size-lg-450px">
                        <img class="img-fit h-100 lazyload"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src=""
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3 rounded-0" name="title"
                                value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control rounded-0" rows="8" name="message" required
                                placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600 rounded-0"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary fw-600 rounded-0 w-100px">{{ translate('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bid Modal -->
    @if ($detailedProduct->auction_product == 1)
        @php
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid+1 : $detailedProduct->starting_bid;
        @endphp
        <div class="modal fade" id="bid_for_detail_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('Bid For Product') }} <small>({{ translate('Min Bid Amount: ') . $min_bid_amount }})</small> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ route('auction_product_bids.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                            <div class="form-group">
                                <label class="form-label">
                                    {{ translate('Place Bid Price') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-group">
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="amount" min="{{ $min_bid_amount }}" placeholder="{{ translate('Enter Amount') }}" required>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>
    <a href="https://wa.me/{{ get_setting('whatsapp_number') }}" target="_blank" id="whatsapp_icon"  >
        <svg width="100%" height="100%" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg"><circle class="color-element" cx="19.4395" cy="19.4395" r="19.4395" fill="#49E670"></circle><path d="M12.9821 10.1115C12.7029 10.7767 11.5862 11.442 10.7486 11.575C10.1902 11.7081 9.35269 11.8411 6.84003 10.7767C3.48981 9.44628 1.39593 6.25317 1.25634 6.12012C1.11674 5.85403 2.13001e-06 4.39053 2.13001e-06 2.92702C2.13001e-06 1.46351 0.83755 0.665231 1.11673 0.399139C1.39592 0.133046 1.8147 1.01506e-06 2.23348 1.01506e-06C2.37307 1.01506e-06 2.51267 1.01506e-06 2.65226 1.01506e-06C2.93144 1.01506e-06 3.21063 -2.02219e-06 3.35022 0.532183C3.62941 1.19741 4.32736 2.66092 4.32736 2.79397C4.46696 2.92702 4.46696 3.19311 4.32736 3.32616C4.18777 3.59225 4.18777 3.59224 3.90858 3.85834C3.76899 3.99138 3.6294 4.12443 3.48981 4.39052C3.35022 4.52357 3.21063 4.78966 3.35022 5.05576C3.48981 5.32185 4.18777 6.38622 5.16491 7.18449C6.42125 8.24886 7.39839 8.51496 7.81717 8.78105C8.09636 8.91409 8.37554 8.9141 8.65472 8.648C8.93391 8.38191 9.21309 7.98277 9.49228 7.58363C9.77146 7.31754 10.0507 7.1845 10.3298 7.31754C10.609 7.45059 12.2841 8.11582 12.5633 8.38191C12.8425 8.51496 13.1217 8.648 13.1217 8.78105C13.1217 8.78105 13.1217 9.44628 12.9821 10.1115Z" transform="translate(12.9597 12.9597)" fill="#FAFAFA"></path><path d="M0.196998 23.295L0.131434 23.4862L0.323216 23.4223L5.52771 21.6875C7.4273 22.8471 9.47325 23.4274 11.6637 23.4274C18.134 23.4274 23.4274 18.134 23.4274 11.6637C23.4274 5.19344 18.134 -0.1 11.6637 -0.1C5.19344 -0.1 -0.1 5.19344 -0.1 11.6637C-0.1 13.9996 0.624492 16.3352 1.93021 18.2398L0.196998 23.295ZM5.87658 19.8847L5.84025 19.8665L5.80154 19.8788L2.78138 20.8398L3.73978 17.9646L3.75932 17.906L3.71562 17.8623L3.43104 17.5777C2.27704 15.8437 1.55796 13.8245 1.55796 11.6637C1.55796 6.03288 6.03288 1.55796 11.6637 1.55796C17.2945 1.55796 21.7695 6.03288 21.7695 11.6637C21.7695 17.2945 17.2945 21.7695 11.6637 21.7695C9.64222 21.7695 7.76778 21.1921 6.18227 20.039L6.17557 20.0342L6.16817 20.0305L5.87658 19.8847Z" transform="translate(7.7758 7.77582)" fill="white" stroke="white" stroke-width="0.2"></path></svg>
    </a>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
            Fancybox.bind('[data-fancybox="image-fancy-box"]', {
            });
        });
        function fireThisFancyBox(stockId) {
            var fancyBoxSelector = 'a[data-fancybox="image-fancy-box-' + stockId + '"]';
            Fancybox.bind(fancyBoxSelector, {
            }).open();
        }
        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }

        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        // Pagination using ajax
        $(window).on('hashchange', function() {
            if(window.history.pushState) {
                window.history.pushState('', '/', window.location.pathname);
            } else {
                window.location.hash = '';
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.product-queries-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'query', 'queries-area');
                e.preventDefault();
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.product-reviews-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'review', 'reviews-area');
                e.preventDefault();
            });
        });

        function getPaginateData(page, type, section) {
            $.ajax({
                url: '?page=' + page,
                dataType: 'json',
                data: {type: type},
            }).done(function(data) {
                $('.'+section).html(data);
                location.hash = page;
            }).fail(function() {
                alert('Something went worng! Data could not be loaded.');
            });
        }
        // Pagination end

        function showImage(photo) {
            $('#image_modal img').attr('src', photo);
            $('#image_modal img').attr('data-src', photo);
            $('#image_modal').modal('show');
        }

        function bid_modal(){
            @if (Auth::check() && (isCustomer() || isSeller()))
                $('#bid_for_detail_product').modal('show');
          	@elseif (Auth::check() && isAdmin())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers & Sellers can Bid.') }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function product_review(product_id) {
            @if (Auth::check() && isCustomer())
                @if ($review_status == 1)
                    $.post('{{ route('product_review_modal') }}', {
                        _token: '{{ @csrf_token() }}',
                        product_id: product_id
                    }, function(data) {
                        $('#product-review-modal-content-section').html(data);
                        $('#product-review-modal-content-section').show();
                        // $('#product-review-modal').modal('show', {
                        //     backdrop: 'static'
                        // });
                        AIZ.extra.inputRating();

                    });
                @else
                    AIZ.plugins.notify('warning', '{{ translate('Sorry, You need to buy this product to give review.') }}');
                @endif
            @elseif (Auth::check() && !isCustomer())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers can give review.') }}');
            @else
                $('#login_modal').modal('show'); @endif
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            fetchFlashDealHour();
        });

        function fetchFlashDealHour() {
            $.ajax({
                url: '/get-flash-deal-hour', // Replace with your actual endpoint
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const flashDealHour = data.flash_deal_hour;
                    initializeCountdown(flashDealHour);
                },
                error: function(error) {
                    console.error('Error fetching flash deal hour:', error);
                }
            });
        }

        function initializeCountdown(flashDealHour) {
            // Parse the flashDealHour string into a JavaScript Date object
            const endTime = new Date(flashDealHour);

            // Your remaining countdown logic
            displayCountdown();
            displayFlashDealContent(endTime);

            // Update the countdown every second
            setInterval(function() {
                updateCountdown(endTime);
            }, 1000);
        }

        function displayCountdown() {
            const countdownSection = $('#countdown-section');
            const circleContainer = $('<div class="circle-container"></div>');

            ['days', 'hours', 'minutes', 'seconds'].forEach(unit => {
                const circle = $(`<div class="circle" id="${unit}">
                                    <div id="${unit}-value">00</div>
                                    <div>${unit.charAt(0).toUpperCase() + unit.slice(1)}</div>
                                </div>`);
                circleContainer.append(circle);
            });

            countdownSection.html(circleContainer);
        }

        function displayFlashDealContent(endTime) {
            const flashDealContent = $('#flash-deal-content');
            const dealEndTime = $('#deal-end-time');
            dealEndTime.text(endTime.toLocaleString());
        }

        function updateCountdown(endTime) {
            const now = new Date();
            const timeRemaining = endTime - now;

            if (timeRemaining <= 0) {
                // Show the flash deal content when the countdown ends
                $('#countdown-section').hide();
                $('#flash-deal-content').show();
            } else {
                // Update the countdown display
                const units = {
                    days: Math.floor(timeRemaining / (24 * 60 * 60 * 1000)),
                    hours: Math.floor((timeRemaining % (24 * 60 * 60 * 1000)) / (60 * 60 * 1000)),
                    minutes: Math.floor((timeRemaining % (60 * 60 * 1000)) / (60 * 1000)),
                    seconds: Math.floor((timeRemaining % (60 * 1000)) / 1000),
                };

                Object.keys(units).forEach(unit => {
                    $(`#${unit}-value`).text(formatValue(units[unit]));
                });
            }
        }
        function getUnitMilliseconds(unit) {
            switch (unit) {
                case 'days':
                    return 24 * 60 * 60 * 1000;
                case 'hours':
                    return 60 * 60 * 1000;
                case 'minutes':
                    return 60 * 1000;
                case 'seconds':
                    return 1000;
                default:
                    return 1;
            }
        }
        function formatValue(value) {
            return value < 10 ? '0' + value : value;
        }
    </script>

@if (get_setting('facebook_pixel') == 1)
@php
    $base_data = $detailedProduct;
    $category = $detailedProduct->category()->pluck('name');
    $brand = $detailedProduct->brand()->pluck('name');
    
    $lowest_price = $detailedProduct->unit_price;
    $highest_price = $detailedProduct->unit_price;

    if ($detailedProduct->variant_product) {
        foreach ($detailedProduct->stocks as $key => $stock) {
            if ($lowest_price > $stock->price) {
                $lowest_price = $stock->price;
            }
            if ($highest_price < $stock->price) {
                $highest_price = $stock->price;
            }
        }
    }

    $discount_applicable = false;

    if ($detailedProduct->discount_start_date == null) {
        $discount_applicable = true;
    } elseif (
        strtotime(date('d-m-Y H:i:s')) >= $detailedProduct->discount_start_date &&
        strtotime(date('d-m-Y H:i:s')) <= $detailedProduct->discount_end_date
    ) {
        $discount_applicable = true;
    }

    if ($discount_applicable) {
        if ($detailedProduct->discount_type == 'percent') {
            $lowest_price -= ($lowest_price * $detailedProduct->discount) / 100;
            $highest_price -= ($highest_price * $detailedProduct->discount) / 100;
        } elseif ($detailedProduct->discount_type == 'amount') {
            $lowest_price -= $detailedProduct->discount;
            $highest_price -= $detailedProduct->discount;
        }
    }

    foreach ($detailedProduct->taxes as $product_tax) {
        if ($product_tax->tax_type == 'percent') {
            $lowest_price += ($lowest_price * $product_tax->tax) / 100;
            $highest_price += ($highest_price * $product_tax->tax) / 100;
        } elseif ($product_tax->tax_type == 'amount') {
            $lowest_price += $product_tax->tax;
            $highest_price += $product_tax->tax;
        }
    }
@endphp

    <script>
     setTimeout(function() {
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
        var currentMonth = new Date().getMonth() + 1; // Months are zero-based, so we add 1
        var product = @json($base_data);
        var category = @json($category);
        var brand = @json($brand);
        var price = $('#chosen_price').text();
        var timeZone = 'Africa/Cairo'; // Egypt timezone
        var formattedTime = new Date().toLocaleTimeString('en-US', {
            timeZone,
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        var data = {
            product_id: product.id,
            product_name: product.name,
            product_price: price,
            event_day: new Date().getDate(),
            event_month: new Date().getMonth() + 1,
            category_name: category,
            brand_name: brand,
            page_title: document.title,
            page_url: window.location.href,
            content_type: 'product',
            event_time: formattedTime

        };
        fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}');
        fbq('track', 'ViewContent', data, {
            event_id: "{{ $pixel_event_id }}"
        });
        }, 2000);
    </script>
    <noscript>
        <img height="1"
        width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}&ev=PageView&noscript=1" />
    </noscript>
    <script>
    setTimeout(function() {
        (function(e, t, n) {
            if (e.snaptr) return;
            var a = e.snaptr = function() {
                a.handleRequest ? a.handleRequest.apply(a, arguments) : a.queue.push(arguments)
            };
            a.queue = [];
            var s = 'script';
            r = t.createElement(s);
            r.async = !0;
            r.src = n;
            var u = t.getElementsByTagName(s)[0];
            u.parentNode.insertBefore(r, u);
        })(window, document,
            'https://sc-static.net/scevent.min.js');

        snaptr('init', '{{ env('SNAPCHAT_PIXEL_ID') }}', {});
        var currentDay = new Date().getDate();
        var currentMonth = new Date().getMonth() + 1;
        var product = @json($base_data);
        var category = @json($category);
        var brand = @json($brand);
        var price = $('#chosen_price').text();
        console.log(price)
        var timeZone = 'Asia/Riyadh';
        var formattedTime = new Date().toLocaleTimeString('en-US', {
            timeZone,
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        var contentsArray = [{
            id: product.id,
            quantity: 1
        }];
        var customerStatus = '{{ Auth::check() ? "user" : "guest" }}';
        snaptr('track', 'VIEW_CONTENT', {
            'price': price,
            'currency': 'SAR',
            'item_ids': [product.id],
            'item_category': category,
            'brands': [brand],
            'client_deduplication_id': "{{ $pixel_event_id }}",
            'customer_status': customerStatus,
            'number_items': 1,
            'description': product.description,
            'success': 1,
        });
    }, 2000);
</script>

                        
                        
                        
    <script>
        ttq.track('ViewContent', {
            "contents": [{
                "content_id": product.id,
                "content_type": "product",
                "content_name": product.name
            }],
            "value": <?php echo $lowest_price; ?>,
            "currency": "SAR"
        });
    </script>
    @endif
@endsection
