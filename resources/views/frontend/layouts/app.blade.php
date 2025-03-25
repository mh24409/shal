<!DOCTYPE html>

@php

    app()->setLocale('sa');
    Session::put('locale', 'sa');

@endphp

@if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
    <html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">
    <title>@yield('meta_title', get_setting('website_name') . ' | ' . get_setting('site_motto'))</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('meta_description', get_setting('meta_description'))" />
    <meta name="keywords" content="@yield('meta_keywords', get_setting('meta_keywords'))">
    @yield('meta')
    @if (!isset($detailedProduct) && !isset($customer_product) && !isset($shop) && !isset($page) && !isset($blog))
        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="{{ get_setting('meta_title') }}">
        <meta itemprop="description" content="{{ get_setting('meta_description') }}">
        <meta itemprop="image" content="{{ uploaded_asset(get_setting('meta_image')) }}">

        <!-- Twitter Card data -->
        <meta name="twitter:card" content="product">
        <meta name="twitter:site" content="@publisher_handle">
        <meta name="twitter:title" content="{{ get_setting('meta_title') }}">
        <meta name="twitter:description" content="{{ get_setting('meta_description') }}">
        <meta name="twitter:creator"
            content="@author_handle">
        <meta name="twitter:image" content="{{ uploaded_asset(get_setting('meta_image')) }}">

        <!-- Open Graph data -->
        <meta property="og:title" content="{{ get_setting('meta_title') }}" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ route('home') }}" />
        <meta property="og:image" content="{{ uploaded_asset(get_setting('meta_image')) }}" />
        <meta property="og:description" content="{{ get_setting('meta_description') }}" />
        <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
        <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    @endif
    <link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
    <link href="{{ static_asset('assets/fonts/IBMPLEXSANSARABIC-ZILLA-SLAB-ITAL.ttf') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css') }}">

    <script>
    !function(w,d,t){var s=d.createElement(t);s.src="https://pxl.tiktok.com/v1/js?_t="+Date.now();s.async=!0;
    s.onload=function(){w.ttq=w.ttq||[];w.ttq.push(['init', '{{ env('TIKTOK_PIXEL_ID') }}'])};
    var firstScript=d.getElementsByTagName(t)[0];firstScript.parentNode.insertBefore(s,firstScript)}
    (window,document,'script');
    </script>
    
    
        <script>
        !function (w, d, t) {
            w.TiktokAnalyticsObject = t;
            var ttq = w[t] = w[t] || [];
            ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias", "group", "enableCookie", "disableCookie"],
            ttq.setAndDefer = function (t, e) {
                t[e] = function () {
                    t.push([e].concat(Array.prototype.slice.call(arguments, 0)))
                }
            };
            for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]);
            ttq.instance = function (t) {
                for (var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++) ttq.setAndDefer(e, ttq.methods[n]);
                return e
            },
            ttq.load = function (e, n) {
                var i = "https://analytics.tiktok.com/i18n/pixel/events.js";
                ttq._i = ttq._i || {},
                ttq._i[e] = [],
                ttq._i[e]._u = i,
                ttq._t = ttq._t || {},
                ttq._t[e] = +new Date,
                ttq._o = ttq._o || {},
                ttq._o[e] = n || {};
                var o = document.createElement("script");
                o.type = "text/javascript",
                o.async = !0,
                o.src = i + "?sdkid=" + e + "&lib=" + t;
                var a = document.getElementsByTagName("script")[0];
                a.parentNode.insertBefore(o, a)
            };
    
            ttq.load('{{ env('TIKTOK_PIXEL_ID') }}');
            ttq.page();
        }(window, document, 'ttq');
    </script>
    
    <script>
        var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
            nothing_found: '{!! translate('Nothing found', null, true) !!}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
        }
    </script>
    <style>
        :root{
            --blue: #3490f3;
            --gray: #606062;
            --gray-dark: #8d8d8d;
            --secondary: #919199;
            --soft-secondary: rgba(145, 145, 153, 0.15);
            --success: #85b567;
            --soft-success: rgba(133, 181, 103, 0.15);
            --warning: #f3af3d;
            --soft-warning: rgba(243, 175, 61, 0.15);
            --light: #f5f5f5;
            --soft-light: #3b3b3d ;
            --soft-white: #b5b5bf;
            --dark: #292933;
            --soft-dark: #373739;
            --third : #00DEFF;
            --border-raduis : 0px 0px 0px 20px;
            --primary: {{ get_setting('base_color', '#d43533') }};
            --hov-primary: {{ get_setting('base_hov_color', '#9d1b1a') }};
            --soft-primary: {{ hex2rgba(get_setting('base_color', '#d43533'), 0.15) }};
        }
        @font-face {
        font-family: 'WebFont';
        src: url({{ static_asset('assets/fonts/ArbFONTS-cocon-next-arabic.ttf') }}) format('truetype');
        }
        @font-face {
        font-family: 'VeryCustomWebFont';
        src: url({{ static_asset('assets/fonts/ArbFONTS-cocon-next-arabic.ttf') }}) format('truetype');
         }

        *:not(i){
          font-family: 'WebFont', sans-serif !important;
        }

        .pagination .page-link,
        .page-item.disabled .page-link {
            min-width: 32px;
            min-height: 32px;
            line-height: 32px;
            text-align: center;
            padding: 0;
            border: 1px solid var(--soft-light);
            font-size: 0.875rem;
            border-radius: 0 !important;
            color: var(--dark);
        }
        .pagination .page-item {
            margin: 0 5px;
        }

        .aiz-carousel.coupon-slider .slick-track{
            margin-left: 0;
        }

        .form-control:focus {
            border-width: 2px !important;
        }
        .iti__flag-container {
            padding: 2px;
        }
        .modal-content {
            border: 0 !important;
            border-radius: 0 !important;
        }

        #map{
            width: 100%;
            height: 250px;
        }
        #edit_map{
            width: 100%;
            height: 250px;
        }

        .pac-container { z-index: 100000; }

        .aiz-megabox>input:checked~.aiz-megabox-elem,
        .aiz-megabox>input:checked~.aiz-megabox-elem {
           background-color: black;
            color: white;
        }
        @if (url()->current() == url('/') || url()->current() == url('/home'))
            .dyna-color{
                color: white;
            }

        @else
            .header-din-bg{
                background-color: white;
            }
        @endif
         body {
                background-color: #F9F9F9;
            }

    </style>

@if (get_setting('google_analytics') == 1)
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('TRACKING_ID') }}"></script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env('TRACKING_ID') }}');
    </script>
@endif

@if (get_setting('facebook_pixel') == '1')
    <script>
    
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}&ev=PageView&noscript=1"/>
    </noscript>
 @endif

<script>
    (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
    {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
    a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
    r.src=n;var u=t.getElementsByTagName(s)[0];
    u.parentNode.insertBefore(r,u);})(window,document,
    'https://sc-static.net/scevent.min.js');
    
    snaptr('init', '{{ env('SNAPCHAT_PIXEL_ID') }}', {
    });
    
    
    snaptr('track', 'PAGE_VIEW');
</script>
@php
    echo get_setting('header_script');
@endphp
<link rel="stylesheet" href="{{ static_asset('assets/css/fontawesome/all.min.css') }}" />
<link rel="stylesheet" href="{{ static_asset('assets/css/fontawesome/brands.min.css') }}" />
<link rel="stylesheet" href="{{ static_asset('assets/css/fontawesome/fontawesome.min.css') }}" />
<link rel="stylesheet" href="{{ static_asset('assets/css/fontawesome/regular.min.css') }}" />
<link rel="stylesheet" href="{{ static_asset('assets/css/fontawesome/solid.min.css') }}" />
<link rel="stylesheet" href="{{ static_asset('assets/css/fontawesome/svg-with-js.min.css') }}" />
<link rel="stylesheet" href="{{ static_asset('assets/css/fontawesome/v4-font-face.min.css') }}" />
<link rel="stylesheet" href="{{ static_asset('assets/css/fontawesome/v4-shims.min.css') }}" />
<link rel="stylesheet" href="{{ static_asset('assets/css/fontawesome/v5-font-face.min.css') }}" />
<link rel="stylesheet" href="{{ static_asset('assets/css/eocjs-newsticker.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/css/hc-offcanvas-nav.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/css/fancybox.css') }}">
<link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css') }}">

<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic&family=Tajawal:wght@500&display=swap');
  </style>
     <script defer type="text/javascript" src="https://cdn.tamara.co/widget-v2/tamara-widget.js"></script>

</head>
<body>
    <!-- aiz-main-wrapper -->



    <div class="aiz-main-wrapper d-flex flex-column">

        <!-- Header -->
        @include('frontend.inc.nav')

        @yield('content')

        @include('frontend.inc.footer')

    </div>
    @include('frontend.checkout_one_Step.popups')

    <!-- cookies agreement -->
    @if (get_setting('show_cookies_agreement') == 'on')
        <div class="aiz-cookie-alert shadow-xl">
            <div class="p-3 bg-dark rounded">
                <div class="text-white mb-3">
                    @php
                        echo get_setting('cookies_agreement_text');
                    @endphp
                </div>
                <button class="btn btn-primary aiz-cookie-accept">
                    {{ translate('Ok. I Understood') }}
                </button>
            </div>
        </div>
    @endif

    <!-- website popup -->
    @if (get_setting('show_website_popup') == 'on')
        <div class="modal website-popup removable-session d-none" data-key="website-popup" data-value="removed">
            <div class="absolute-full bg-black opacity-60"></div>
            <div class="modal-dialog modal-dialog-centered modal-dialog-zoom modal-lg mx-4 mx-md-auto">
                <div class="modal-content position-relative " style="border-radius: var(--border-raduis) !important;">
                   <div class="row">
                    <div class="col-md-6 p-2">
                        <div class="aiz-editor-data p-4">
                            {!! get_setting('website_popup_content') !!}
                        </div>
                        @if (get_setting('show_subscribe_form') == 'on')
                            <div class="pb-5 pt-4 px-3 px-md-5">
                                <form method="POST" action="{{ route('subscribers.store') }}">
                                    @csrf
                                    <div class="subscribe-form-action position-relative">
                                        <input type="email" class=" form-control border-secondary rounded-0 bg-white"
                                            placeholder="{{ translate('Your Email Address') }}" name="email" required>
                                        <button type="submit" class=" absolute-top-right btn ">{{ translate('Subscribe') }}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 d-none d-lg-block" style="border-radius: var(--border-raduis) !important;overflow:hidden">
                        <img style="border-radius: var(--border-raduis) !important" src="{{ uploaded_asset(get_setting('popup_image')) }}" class="w-100"  alt="">
                    </div>
                   </div>
                    <button class="absolute-top-left bg-white shadow-lg btn btn-circle btn-icon mr-n3 mt-n3 set-session" data-key="website-popup" data-value="removed" data-toggle="remove-parent" data-parent=".website-popup">
                        <i class="la la-close fs-20"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @include('frontend.partials.modal')

    @include('frontend.partials.account_delete_modal')

    <div class="modal fade right" id="addToCart">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader text-center p-3">
                    <img width="100px" src="{{ static_asset('assets/img/checkoutLoading.gif') }}" alt="">
                </div>
                <button type="button" class="close  close-modal btn-icon close z-1 btn-circle   mr-2 mt-2 d-flex justify-content-center align-items-center" data-dismiss="modal" aria-label="Close"  >
                    <span aria-hidden="true" class="fs-24 fw-700" style="margin-left: 2px;">&times;</span>
                </button>
                <div id="addToCart-modal-body">

                </div>
            </div>
        </div>
    </div>
    @yield('modal')
    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js') }}"></script>
    @if (get_setting('facebook_chat') == 1)
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({
                  xfbml            : true,
                  version          : 'v3.3'
                });
              };
              (function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <div id="fb-root"></div>
        <div class="fb-customerchat"
          attribution=setup_tool
          page_id="{{ env('FACEBOOK_PAGE_ID') }}">
        </div>
    @endif
    <script>
        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach
    </script>
    <script>
        $(document).ready(function() {
            $('.category-nav-element').each(function(i, el) {
                $(el).on('mouseover', function(){
                    if(!$(el).find('.sub-cat-menu').hasClass('loaded')){
                        $.post('{{ route('category.elements') }}', {_token: AIZ.data.csrf, id:$(el).data('id')}, function(data){
                            $(el).find('.sub-cat-menu').addClass('loaded').html(data);
                        });
                    }
                });
            });
            if ($('.lang-item').length > 0) {
                $('.lang-item').each(function() {
                    $(this).on('click', function(e){
                        e.preventDefault();
                        var $this = $(this);
                        var locale = $this.data('flag');
                        $.post('{{ route('language.change') }}',{_token: AIZ.data.csrf, locale:locale}, function(data){
                            location.reload();
                        });

                    });
                });
            }

            if ($('#currency-change').length > 0) {
                $('#currency-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e){
                        e.preventDefault();
                        var $this = $(this);
                        var currency_code = $this.data('currency');
                        $.post('{{ route('currency.change') }}',{_token: AIZ.data.csrf, currency_code:currency_code}, function(data){
                            location.reload();
                        });

                    });
                });
            }
        });
        $('#search').on('keyup', function(){
            search();
        });
        $('#search').on('focus', function(){
            search();
        });
        function search(){
            var searchKey = $('#search').val();
            if(searchKey.length > 0){
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('search.ajax') }}', { _token: AIZ.data.csrf, search:searchKey}, function(data){
                    if(data == '0'){
                        // $('.typed-search-box').addClass('d-none');
                        $('#search-content').html(null);
                        $('.typed-search-box .search-nothing').removeClass('d-none').html('{{ translate('Sorry, nothing found for') }} <strong>"'+searchKey+'"</strong>');
                        $('.search-preloader').addClass('d-none');

                    }
                    else{
                        $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                        $('#search-content').html(data);
                        $('.search-preloader').addClass('d-none');
                    }
                });
            }
            else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }
        $(".aiz-user-top-menu").on("mouseover", function (event) {
            $(".hover-user-top-menu").addClass('active');
        })
        .on("mouseout", function (event) {
            $(".hover-user-top-menu").removeClass('active');
        });
        $(document).on("click", function(event){
            var $trigger = $("#category-menu-bar");
            if($trigger !== event.target && !$trigger.has(event.target).length){
                $("#click-category-menu").slideUp("fast");;
                $("#category-menu-bar-icon").removeClass('show');
            }
        });
        function updateNavCart(view, count){
            $('.cart_count_pill').html(count);
            $('#cart_items').html(view);
         }

        function removeFromCart(key){
            $.post('{{ route('cart.removeFromCart') }}', {
                _token  : AIZ.data.csrf,
                id      :  key
            }, function(data){
                updateNavCart(data.nav_cart_view, data.cart_count);
                $('#cart-summary').html(data.cart_view);
                $('#cart_page_cart_summery').html(data.cart_page_cart_summery);
                AIZ.plugins.notify('success', "{{ translate('Item has been removed from cart') }}");
                $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())-1); 
            });
        }
        function addToCompare(id){
            $.post('{{ route('compare.addToCompare') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                $('#compare').html(data);
                (function frame() {
                // launch a few confetti from the left edge
                confetti({
                    particleCount: 2500,
                    angle: 60,
                    spread: 500,
                    origin: { x: 0 }
                    });
                    confetti({
                        particleCount: 2500,
                        angle: 120,
                        spread: 500,
                        origin: { x: 1 }
                    });
                }());
                AIZ.plugins.notify('success', "{{ translate('Item has been added to compare list') }}");
                $('#compare_items_sidenav').html(parseInt($('#compare_items_sidenav').html())+1);
                $('.compare_count_pill').html(parseInt($('.compare_count_pill').html())+1);
                $('#addToCart').modal('hide');
                var $this = $('.aiz-top-menu-sidebar-compare');
                var target = 'aiz-top-menu-sidebar-compare';
                var sameTriggers = 'hide-top-menu-bar';

                if ($(target).hasClass("active")) {
                    $(target).removeClass("active");
                    $(sameTriggers).removeClass("active");
                    $this.removeClass("active");
                    $('body').removeClass("overflow-hidden");
                } else {
                    $(target).addClass("active");
                    $this.addClass("active");
                
                }
            });
        }

        function addToWishList(id){
            @if (Auth::check() && Auth::user()->user_type == 'customer')
                $.post('{{ route('wishlists.store') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                    if(data != 0){
                        $('#wishlist').html(data);
                       $('.wishlist_count_pill').html(parseInt($('.wishlist_count_pill').html())+1);
                        (function frame() { 
                            confetti({
                                particleCount: 2500,
                                angle: 60,
                                spread: 500,
                                origin: { x: 0 }
                            }); 
                            confetti({
                                particleCount: 2500,
                                angle: 120,
                                spread: 500,
                                origin: { x: 1 }
                            });
                          }());
                        AIZ.plugins.notify('success', "{{ translate('Item has been added to wishlist') }}");
                                var $this = $('.aiz-top-menu-sidebar-wishlist');
                                var target = 'aiz-top-menu-sidebar-wishlist';
                                var sameTriggers = 'hide-top-menu-bar';
                                $('#addToCart').modal('hide');
                                if ($(target).hasClass("active")) {
                                    $(target).removeClass("active");
                                    $(sameTriggers).removeClass("active");
                                    $this.removeClass("active");
                                    $('body').removeClass("overflow-hidden");
                                } else {
                                    $(target).addClass("active");
                                    $this.addClass("active");
                                
                                }
                      }
                    else{
                        AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
                    }
                });
            @elseif(Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the WishList.') }}");
            @else
                AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
            @endif
        }

        function showAddToCartModal(id) {
            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }
            $('#addToCart-modal-body').html(null);
            $('#addToCart').modal();
        
            $.post('{{ route('cart.showCartModal') }}', {_token: AIZ.data.csrf, id: id}, function(data) {
                !function(f, b, e, v, n, t, s) {
                    if (f.fbq) return;
                    n = f.fbq = function() {
                        n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments);
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
                    s.parentNode.insertBefore(t, s);
                }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
                
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                
                function generateString(length) {
                    let result = '';
                    const charactersLength = characters.length;
                    for (let i = 0; i < length; i++) {
                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }
                    return result;
                }
                
                var currentDay = new Date().getDate();
                var currentMonth = new Date().getMonth() + 1;
                var product = data.product;
                var category = data.product.category;
                var brand = data.product.brand;
                var price = data.product.unit_price;
                var timeZone = 'Africa/Cairo';
                var formattedTime = new Date().toLocaleTimeString('en-US', {
                    timeZone,
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                
                var eventData = {
                    content_ids: [data.product.id],
                    content_name: data.product.name,
                    content_type: 'product',
                    value: data.product.unit_price,
                    currency: 'USD',
                    content_category: data.product.category.name,
                    content_brand: data.product.category.brand,
                    content_page_title: document.title,
                    content_page_url: window.location.href,
                    event_time: formattedTime,
                    event_id: generateString(30)
                };
                
                fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}');
                fbq('track', 'ViewContent', eventData);
                
                (function(e, t, n) {
                    if (e.snaptr) return;
                    var a = e.snaptr = function() {
                        a.handleRequest ? a.handleRequest.apply(a, arguments) : a.queue.push(arguments);
                    };
                    a.queue = [];
                    var s = 'script';
                    var r = t.createElement(s);
                    r.async = !0;
                    r.src = n;
                    var u = t.getElementsByTagName(s)[0];
                    u.parentNode.insertBefore(r, u);
                })(window, document, 'https://sc-static.net/scevent.min.js');
                
                var currentDay = new Date().getDate();
                var currentMonth = new Date().getMonth() + 1;
                var product = data.product;
                var category = data.product.category;
                var brand = data.product.brand;
                var price = data.product.unit_price;
                var timeZone = 'Africa/Cairo';
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
                
                snaptr('init', '{{ env('SNAPCHAT_PIXEL_ID') }}', {});
                snaptr('track', 'VIEW_CONTENT', {
                    'price': product.unit_price,
                    'currency': 'SAR',
                    'item_ids': [product.id],
                    'item_category': category,
                    'brands': [brand],
                    'client_deduplication_id': generateString(30),
                    'customer_status': customerStatus,
                    'number_items': 1,
                    'description': product.description,
                    'success': 1,
                });

        
                $('.c-preloader').hide();
                $('#addToCart-modal-body').html(data.modal_view);
                AIZ.plugins.slickCarousel();
                AIZ.plugins.zoom();
                AIZ.extra.plusMinus();
                getVariantPrice();
            }).fail(function(jqXHR, textStatus, errorThrown) {
                // Handle error here
            });
        }


        $('#option-choice-form input').on('change', function(){
            getVariantPrice();
        });

        function getVariantPrice(){
            if($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()){
                $.ajax({
                    type:"POST",
                    url: '{{ route('products.variant_price') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                         $('.attribute-megabox').each(function() {
                            $(this).removeClass('disabled-choice');
                        });
                        if (data.is_back_order === 0) {
                            data.stocks.forEach(stock => {
                                let foundMatch = data.attribute_values.some(attribute_value => {
                                     return String(attribute_value.id) === String(stock.attribute_id);
                                });
                                
                                 if (foundMatch) { 
                                    if (stock.qty === 0) {
                                         $('#choice_attribute_id-' + stock.attribute_id).addClass('disabled-choice');
                                    } else {
                                         $('#choice_attribute_id-' + stock.attribute_id).removeClass('disabled-choice');
                                    }
                                }
                            });
                        } 
                        if(data.image){
                            $('.product-gallery-thumb .carousel-box').each(function (i) {
                             if($(this).data('variation') && data.image == $(this).data('variation')){
                                $('.product-gallery-thumb').slick('slickGoTo', i);
                            }
                            })
                           $('.product-details-image-gallery').each(function (i, element) {
                                if ($(element).hasClass('aiz-carousel-' + data.stock_id)) {
                                    if (!$(element).is(':visible')) {
                                        $(element).show();
                                        $('.aiz-carousel-gallery-' + data.stock_id).slick('refresh');
                                        $('.aiz-carousel-gallery-thumb-' + data.stock_id).slick('refresh');
                                    }
                                } else {
                                    $(element).hide();
                                }
                            });
                        }else{
                             $('.main-aiz-carousel').show();
                            $('.main-aiz-carousel-gallery-' + data.stock_id).slick('refresh');
                            $('.main-aiz-carousel-gallery-thumb-' + data.stock_id).slick('refresh');
                        }
                        $('#option-choice-form #chosen_price_div').removeClass('d-none');
                        $('#option-choice-form #chosen_price_div #chosen_price').html(data.price);
                        $('#tamara').html(data.tamara_view);
                        window.TamaraWidgetV2.refresh();
                        $('.suits').html(data.suits);
                        $('#available-quantity').html(data.quantity);
                        $('.input-number').prop('max', data.max_limit);
                        $('#selected_product_sku').html(data.sku);
                        $('#selected_product_discount_value').html(data.discount_value);

                        if(parseInt(data.in_stock) == 0 && data.back_order==0 && data.digital  == 0){
                           $('.buy-now').addClass('d-none');
                           $('.add-to-cart-qty').addClass('d-none');
                           $('.add-to-cart-qty').removeClass('d-flex');
                           $('.add-to-cart-fixed-bottom').addClass('d-none');
                           $('.add-to-cart-fixed-bottom').removeClass('d-flex');
                           $('.add-to-cart').addClass('d-none');
                           $('.out-of-stock').removeClass('d-none');
                        }
                        else{
                           $('.buy-now').removeClass('d-none');
                           $('.add-to-cart').removeClass('d-none');
                           $('.out-of-stock').addClass('d-none');
                           $('.add-to-cart-qty').removeClass('d-none');
                           $('.add-to-cart-qty').addClass('d-flex');
                           $('.add-to-cart-fixed-bottom').removeClass('d-none');
                           $('.add-to-cart-fixed-bottom').addClass('d-flex');
                        }
                        AIZ.extra.plusMinus();
                    }
                });
            }
        }

        function checkAddToCartValidity(){
            var names = {};
            $('#option-choice-form input:radio').each(function() {
                names[$(this).attr('name')] = true;
            });
            var count = 0;
            $.each(names, function() {
                count++;
            });
            if($('#option-choice-form input:radio:checked').length == count){
                return true;
            }
            return false;
        }

        function addToCart(){
            $('.add_to_cart_loader').removeClass('d-none')
             @if (Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
                return false;
            @endif
            if(checkAddToCartValidity()) {
                
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                        var timeZone = 'Africa/Cairo';
                         var formattedTime = new Date().toLocaleTimeString('en-US', { timeZone, hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
                         if(data.data.pixel){
                            fbq('track', 'AddToCart', {
                                'page_title' : document.title,
                                'event_time' :formattedTime ,
                                'product_id':data.product_id ,
                                'price': data.price,
                                'event_day': new Date().getDate(),
                                'event_month': new Date().getMonth() + 1,
                                'page_title' : document.title ,
                                'page_url' : window.location.href,
                                'content_type': 'product',
                                'product_name':data.product_name,
                                'quantity':data.quantity,
                            },{
                                event_id:data.data.pixel_event_id
                            });
                        }
                        snaptr('track', 'ADD_CART', {
                            'price': data.data.price,
                            'currency': 'SAR',
                            'client_deduplication_id': data.data.pixel_event_id,
                            'item_ids': data.data.product_id,
                        })
                        
                        

     ttq.track('AddToCart', {
                                "contents": [
                                    {
                                        "content_id": data.data.product_id, 
                                        "content_type": "product", 
                                        "content_name": data.data.product_name,
                                        "quantity": data.data.quantity,
                                        "price": data.data.price+data.data.tax,
                                    }
                                ],
                                "value": data.data.price +data.data.tax, 
                                "currency": "SAR", 
                                "status": "Submitted" ,
                                "event_id" : data.data.pixel_event_id
                            });
                       $('#addToCart-modal-body').html(null);
                       $('#modal-size').removeClass('modal-lg'); 
                       $('#addToCart-modal-body').html(data.modal_view);
                       AIZ.extra.plusMinus();
                       AIZ.plugins.slickCarousel();
                       updateNavCart(data.nav_cart_view, data.cart_count);
                       if(data.status != 0){
                            $('#addToCart').modal('hide');
                            (function frame() {
                                    confetti({
                                        particleCount: 2500,
                                        angle: 60,
                                        spread: 500,
                                        origin: { x: 0 }
                                    });
                                    confetti({
                                        particleCount: 2500,
                                        angle: 120,
                                        spread: 500,
                                        origin: { x: 1 }
                                    });
                                }());
                            $('.add_to_cart_loader').addClass('d-none')
                            var $this = $('.aiz-top-menu-sidebar-cart');
                            var target = 'aiz-top-menu-sidebar-cart';
                            var sameTriggers = 'hide-top-menu-bar';
    
                            if ($(target).hasClass("active")) {
                                $(target).removeClass("active");
                                $(sameTriggers).removeClass("active");
                                $this.removeClass("active");
                                $('body').removeClass("overflow-hidden");
                            } else {
                                $(target).addClass("active");
                                $this.addClass("active");
                         
                            }
                        }else{
                            $('.add_to_cart_loader').addClass('d-none')
                             AIZ.plugins.notify('error', data.msg);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('.add_to_cart_loader').addClass('d-none')
                        AIZ.plugins.notify('error', "{{ translate('An error occurred while adding to the cart.') }}");
                    }
                });
            }
            else{
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }
        function buyNow(){
            $('.quick_buy_loader').removeClass('d-none')
            @if (Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
                return false;
            @endif

            if(checkAddToCartValidity()) {

                $('#addToCart-modal-body').html(null); 
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    success: function(data){
                        if(data.status == 1){ 
                            var timeZone = 'Africa/Cairo';
                             var formattedTime = new Date().toLocaleTimeString('en-US', { timeZone, hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
                                     if(data.data.pixel){
                                    fbq('track', 'AddToCart', {
                                        'page_title' : document.title,
                                        'event_time' :formattedTime ,
                                        'product_id':data.product_id ,
                                        'price': data.price,
                                        'event_day': new Date().getDate(),
                                        'event_month': new Date().getMonth() + 1,
                                        'page_title' : document.title ,
                                        'page_url' : window.location.href,
                                        'content_type': 'product',
                                        'product_name':data.product_name,
                                        'quantity':data.quantity,
                                    },{
                                        event_id:data.data.pixel_event_id
                                    });
                                }
                                snaptr('track', 'ADD_CART', {
                                    'price': data.data.price,
                                    'currency': 'SAR',
                                    'client_deduplication_id': data.data.pixel_event_id,
                                    'item_ids': data.data.product_id,
                                })
                            $('.quick_buy_loader').addClass('d-none')
                            updateNavCart(data.nav_cart_view, data.cart_count);
                            window.location.replace("{{ route('checkout.shipping_info') }}");
                        }
                        else{
                            $('.quick_buy_loader').removeClass('d-none')
                            AIZ.plugins.notify('error', data.msg);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('.quick_buy_loader').removeClass('d-none')
                        AIZ.plugins.notify('error', "{{ translate('An error occurred while adding to the cart.') }}");
                    }
               });
            }
            else{
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }

        function bid_single_modal(bid_product_id, min_bid_amount){
            @if (Auth::check() && (isCustomer() || isSeller()))
                var min_bid_amount_text = "({{ translate('Min Bid Amount: ') }}"+min_bid_amount+")";
                $('#min_bid_amount').text(min_bid_amount_text);
                $('#bid_product_id').val(bid_product_id);
                $('#bid_amount').attr('min', min_bid_amount);
                $('#bid_for_product').modal('show');
            @elseif (Auth::check() && isAdmin())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers & Sellers can Bid.') }}');
            @else
                $('#login_modal').modal('show'); @endif
        }

        function clickToSlide(btn,id){
            $('#'+id+' .aiz-carousel').find('.'+btn).trigger('click');
            $('#'+id+' .slide-arrow').removeClass('link-disable');
            var arrow = btn=='slick-prev' ? 'arrow-prev' : 'arrow-next';
            if ($('#'+id+' .aiz-carousel').find('.'+btn).hasClass('slick-disabled')) {
                $('#'+id).find('.'+arrow).addClass('link-disable');
            }
        }
        function checkUserAuthAndVerified(event,redirect) {
            event.preventDefault();
            $.ajax({
                url: '{{ route('checkout.check_auth_verify') }}',
                type: 'GET',
                success: function(response) {
                    if (response === 'not_auth') {
                        AIZ.plugins.notify('success',
                        '{{ translate('Please Login With Your Phone Number') }}');
                        $('#loginModal').modal('show');
                        $('#otp_redirect_input').val(redirect)
                    } else if (response === 'not_verified') {
                        AIZ.plugins.notify('success', '{{ translate('Please Verify Your Phone Number') }}');
                        $('.modal').modal('hide');
                        $('#OTPModal').modal();
                        $('#otp_redirect_input').val(redirect)
                    } else {
                        var
                        checkoutShippingInfoUrl = "{{ route('cart') }}";
                        window.location.href = checkoutShippingInfoUrl;
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function goToView(params) {
            document.getElementById(params).scrollIntoView({behavior: "smooth",
            block: "center" }); } function copyCouponCode(code){ navigator.clipboard.writeText(code);
                (function frame() {
                    // launch a few confetti from the left edge
                    confetti({
                        particleCount: 2500,
                        angle: 60,
                        spread: 500,
                        origin: { x: 0 }
                    });
                    // and launch a few from the right edge
                    confetti({
                        particleCount: 2500,
                        angle: 120,
                        spread: 500,
                        origin: { x: 1 }
                    });
                }());
            AIZ.plugins.notify('success', "{{ translate('Coupon Code Copied') }}" ); } $(document).ready(function(){
            $('.cart-animate').animate({margin : 0}, "slow" ); $({deg: 0}).animate({deg: 360}, { duration: 2000, step:
            function(now) { $('.cart-rotate').css({ transform: 'rotate(' + now + 'deg)' }); } }); setTimeout(function(){
            $('.cart-ok').css({ fill: '#d43533' }); }, 2000); }); </script>

        <script type="text/javascript">
            // Country Code
            var isPhoneShown = true,
                countryData = window.intlTelInputGlobals.getCountryData(),
                input = document.querySelector("#phone-code");

            for (var i = 0; i < countryData.length; i++) {
                var country = countryData[i];
                if (country.iso2 == 'bd') {
                    country.dialCode = '88';
                }
            }

            var iti = intlTelInput(input, {
                separateDialCode: true,
                utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
                onlyCountries: @php
                    echo json_encode(
                        \App\Models\Country::where('status', 1)
                            ->pluck('code')
                            ->toArray(),
                    );
                @endphp,
                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                    if (selectedCountryData.iso2 == 'bd') {
                        return "01xxxxxxxxx";
                    }
                    return selectedCountryPlaceholder;
                }
            });
            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);
            input.addEventListener("countrychange", function(e) {
                var country = iti.getSelectedCountryData();
                $('input[name=country_code]').val(country.dialCode);
                $('.iti__selected-dial-code').attr('dir', 'ltr');
            });
            $('.iti__selected-dial-code').attr('dir', 'ltr');
            function toggleEmailPhone(el) {
                if (isPhoneShown) {
                    $('.phone-form-group').addClass('d-none');
                    $('.email-form-group').removeClass('d-none');
                    $('input[name=phone]').val(null);
                    isPhoneShown = false;
                    $(el).html('<p style="color: #000000 !important;margin:0 !important" class="fs-13">{{ translate('Use Phone Instead') }}</p>');
                } else {
                    $('.phone-form-group').removeClass('d-none');
                    $('.email-form-group').addClass('d-none');
                    $('input[name=email]').val(null);
                    isPhoneShown = true;
                    $(el).html('<p style="color: #000000 !important;margin:0 !important" class="fs-13 ">{{ translate('Use Email Instead') }}</p>');
                }
            }
        </script>
        <script>
            var acc = document.getElementsByClassName("aiz-accordion-heading");
            var i;
            for (i = 0; i < acc.length; i++) {
                acc[i].addEventListener("click", function() {
                    this.classList.toggle("active");
                    var panel = this.nextElementSibling;
                    if (panel.style.maxHeight) {
                        panel.style.maxHeight = null;
                    } else {
                        panel.style.maxHeight = panel.scrollHeight + "px";
                    }
                });
            }
        </script>
        <script src="{{ static_asset('assets/js/checkout.js') }}"></script>
          {{-- fontawesome  --}}
    <script src="{{ static_asset('assets/js/fontawesome/all.min.js') }}"></script>
    <script src="{{ static_asset('assets/js/fontawesome/brands.min.js') }}"></script>
    <script src="{{ static_asset('assets/js/fontawesome/fontawesome.min.js') }}"></script>
    <script src="{{ static_asset('assets/js/fontawesome/regular.min.js') }}"></script>
    <script src="{{ static_asset('assets/js/fontawesome/solid.min.js') }}"></script>
    <script src="{{ static_asset('assets/js/fontawesome/v4-shims.min.js') }}"></script>
        <script src="{{ static_asset('assets/js/eocjs-newsticker.js') }}"></script>
        <script src="{{ static_asset('assets/js/hc-offcanvas-nav.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
        <script src="{{ static_asset('assets/js/fancybox.js') }}"></script>
        <script src="{{ static_asset('assets/js/jquery.steps.min.js') }}"></script>
        <script src="{{ static_asset('assets/js/jquery.readall.min.js') }}"></script>
        <script src="{{ static_asset('assets/js/box-otp.js') }}"></script>
        <script src="{{ static_asset('assets/js/custom-scripts.js') }}"></script>
        @yield('script')

        @php
            echo get_setting('footer_script');
        @endphp


        <script>
                 $('.cart-option-choice-form input').on('change', function () {
                    var formId = $(this).data('formid');
                     getCardVariantPrice(formId);
                });
                 function quantityChanged (formId){
                    getCardVariantPrice(formId);
                 }
function getCardVariantPrice(formId) {
    var formSelector = '#' + formId;
    if ($(formSelector + ' input[name=quantity]').val() > 0 && checkCardAddToCartValidity(
        formSelector)) {
        $.ajax({
            type: "POST",
            url: '{{ route('products.variant_price') }}',
            data: $(formSelector).serializeArray(),
            success: function (data) {
                var imgElement = document.querySelector('img[data-imgaFormVariation="'+formId+'"]');

                    if (imgElement) {
                        imgElement.src = data.img;
                    }
                $(formSelector + ' .chosen_price_div').removeClass('d-none');
                $(formSelector + ' .chosen_price_div .product-price .chosen_price').html(data.price);
                $(formSelector + ' .available-quantity').html(data.quantity);
                if (data.quantity > 0) {
                    $('.check_stock_out_of_stock_'+formId).addClass('d-none');
                    $('.check_stock_out_of_stock_'+formId).removeClass('d-flex');
                    $('.check_stock_qty_add_'+formId).removeClass('d-none');
                    $('.check_stock_qty_buy_'+formId).removeClass('d-none');
                    $('.check_stock_qty_add_'+formId).addClass('d-flex');
                    $('.check_stock_qty_buy_'+formId).addClass('d-flex');
                }else{
                    $('.check_stock_out_of_stock_'+formId).removeClass('d-none');
                    $('.check_stock_out_of_stock_'+formId).addClass('d-flex');
                    $('.check_stock_qty_add_'+formId).addClass('d-none');
                    $('.check_stock_qty_buy_'+formId).addClass('d-none');
                    $('.check_stock_qty_add_'+formId).removeClass('d-flex');
                    $('.check_stock_qty_buy_'+formId).removeClass('d-flex');
                }
                $(formSelector + ' .input-number').prop('max', data.max_limit);
                if (parseInt(data.in_stock) == 0 && data.digital == 0) {
                    $(formSelector + ' .buy-now').addClass('d-none');
                    $(formSelector + ' .add-to-cart').addClass('d-none');
                    $(formSelector + ' .out-of-stock').removeClass('d-none');
                } else {
                    $(formSelector + ' .buy-now').removeClass('d-none');
                    $(formSelector + ' .add-to-cart').removeClass('d-none');
                    $(formSelector + ' .out-of-stock').addClass('d-none');
                }
                AIZ.extra.plusMinus();
            }
 });
 }
}
function checkCardAddToCartValidity(formSelector) {
    var names = {};
    $(formSelector + ' input:radio').each(function () {
        names[$(this).attr('name')] = true;
    });

    var count = 0;
    $.each(names, function () {
        count++;
    });

    if ($(formSelector + ' input:radio:checked').length == count) {
        return true;
    }

    return false;
}
function changeQuantityValue(button) {
    var changeType = button.getAttribute("data-changeType");
    var formId = button.getAttribute("data-FormId");
    var formSelector = '#' + formId;
    if (changeType === 'plus') {
        var qty = parseInt($(formSelector + ' .card-input-number').val());
        var max = parseInt($(formSelector + ' .card-input-number').attr('max'));
        var newQty =  qty + 1 ;
        if (qty < max   ) {
            $(formSelector + ' .card-input-number').val(newQty)
            quantityChanged(formId)
        } else{
            AIZ.plugins.notify('warning', "{{ translate('You have exceeded the available stock of this product.') }}" );
        }
    } else if (changeType === 'minus') {
        var qty = parseInt($(formSelector + ' .card-input-number').val());
        var min = parseInt($(formSelector + ' .card-input-number').attr('min'));
        var newQty =  qty - 1 ;
        if (qty > min   ) {
            $(formSelector + ' .card-input-number').val(newQty)
            quantityChanged(formId)

        } else{
            AIZ.plugins.notify('warning', "{{ translate('You cannot ask for a quantity less this for this product.') }}" );
        }
    }
}
    function removeItemFromWishlist(id) {
        $.post('{{ route('wishlists.remove') }}', {
            _token: '{{ csrf_token() }}',
            id: id
        }, function (data) {
            // $('#wishlist').html(data);
            $('.wishlist_count_pill').html(parseInt($('.wishlist_count_pill').html()) - 1);
            $('#wishlist_item_' + id).hide();
            AIZ.plugins.notify('success', '{{ translate('Item has been removed from wishlist') }}');
        });
    }

 function addToCartFromCard(uniqueID) {

    var formSelector = '#' + uniqueID;
    @if (Auth::check() && Auth::user()->user_type != 'customer')
    AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
    return false; @endif

    if (checkAddToCartValidity()) {
        //  $('#addToCart').modal();
        $.ajax({
            type: "POST",
            url: '{{ route('cart.addToCart') }}' ,
            data: $(formSelector).serializeArray(),
            success: function (data) {
            $('.c-preloader').hide();
            $('#addToCart-modal-body').html(null);
             $('#modal-size').removeClass('modal-lg');
            $('#addToCart-modal-body').html(data.modal_view);
            AIZ.extra.plusMinus(); AIZ.plugins.slickCarousel();
            updateNavCart(data.nav_cart_view, data.cart_count);
            $('.dropdown-menu.card-dropdown').addClass('show') } });
            } else {
            AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}" ); }

           }


function buyNowShop(){
            @if (Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to buy this product.') }}");
                return false;
            @endif

            if(checkAddToCartValidity()) {
                $('#buy_now_token').val('{{ csrf_token() }}')
                var data = $('#option-choice-form').serializeArray();
                $('#addToCart-modal-body').html(null);
                $('#addToCart').modal();
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: data,
                    success: function(data){
                        if(data.status == 1){
                             var timeZone = 'Africa/Cairo';
                             var formattedTime = new Date().toLocaleTimeString('en-US', { timeZone, hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
                             if(data.data.pixel){
                                fbq('track', 'AddToCart', { 
                                    'page_title' : document.title,
                                    'event_time' :formattedTime ,
                                    'product_id':data.product_id , 
                                    'price': data.price,
                                    'event_day': new Date().getDate(),
                                    'event_month': new Date().getMonth() + 1,
                                    'page_title' : document.title ,
                                    'page_url' : window.location.href,
                                    'content_type': 'product',
                                    'product_name':data.product_name,
                                    'quantity':data.quantity,
                                },{
                                    event_id:data.data.pixel_event_id
                                });
                            }
                            updateNavCart(data.nav_cart_view, data.cart_count);
                            window.location.replace("{{ route('checkout.shipping_info') }}");
                        }
                        else{
                            $('#addToCart-modal-body').html(null);
                            $('.c-preloader').hide();
                            $('#modal-size').removeClass('modal-lg');
                            $('#addToCart-modal-body').html(data.modal_view);
                        }
                    }
               });
            }
            else{
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
       }

}
           function buyNowFromCard(uniqueID){
            @if (Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
                return false; @endif
            var formSelector = '#' + uniqueID;

            if(checkAddToCartValidity()) {
                $.ajax({
                    type:"POST",
            url: '{{ route('cart.addToCart') }}' , data: $(formSelector).serializeArray(), success: function(data){
            if(data.status==1){ updateNavCart(data.nav_cart_view, data.cart_count);
            window.location.replace("{{ route('checkout.shipping_info') }}"); } else{ $('.c-preloader').hide(); } } }); } else{
            AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}" ); } }



            @if (url()->current() == url('/') || url()->current() == url('/home'))

            var header = document.querySelector('.header-din-bg');
            var elements = document.querySelectorAll('.dyna-color');
            var white_logo = document.querySelector('.logo-white-header');
            var black_logo = document.querySelector('.logo-black-header');

            function handleScroll() {
                var scrollPosition = window.scrollY;
                var scrollThreshold = 10;

                if (scrollPosition > scrollThreshold) {
                    header.style.backgroundColor = 'rgb(255, 255, 255)';

                    white_logo.classList.add('d-none');
                    black_logo.classList.remove('d-none');
                    elements.forEach(function (element) {
                        element.classList.add('header-text-color-black');
                        element.classList.remove('header-text-color-white');
                    });
                } else {
                    header.style.backgroundColor = 'transparent';
                    white_logo.classList.remove('d-none');
                    black_logo.classList.add('d-none');
                    elements.forEach(function (element) {
                        element.classList.add('header-text-color-white');
                        element.classList.remove('header-text-color-black');
                    });
                }
            }

            // Initial state: Show white logo by default
            white_logo.classList.remove('d-none');
            black_logo.classList.add('d-none');

            // Add the scroll event listener
            window.addEventListener('scroll', handleScroll); @endif
            $(document).ready(function() {
                $(".HeroVideo").each(function()
            { this.play(); }); }); </script>
        </body>

        </html>
