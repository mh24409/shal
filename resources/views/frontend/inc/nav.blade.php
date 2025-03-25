@php
    if (Session::has('locale')) {
        $locale = Session::get('locale', Config::get('app.locale'));
    } else {
        $locale = 'en';
    }
@endphp
<!-- Top Bar Banner -->
@if (get_setting('topbar_banner') != null)
    <div class="position-relative top-banner removable-session z-1035 d-none" data-key="top-banner" data-value="removed">
        <a href="{{ get_setting('topbar_banner_link') }}" class="d-block text-reset">
            <img src="{{ uploaded_asset(get_setting('topbar_banner')) }}" class="d-none d-xl-block img-fit">
            <img src="{{ get_setting('topbar_banner_medium') != null ? uploaded_asset(get_setting('topbar_banner_medium')) : uploaded_asset(get_setting('topbar_banner')) }}"
                class="d-none d-md-block d-xl-none img-fit">
            <img src="{{ get_setting('topbar_banner_small') != null ? uploaded_asset(get_setting('topbar_banner_small')) : uploaded_asset(get_setting('topbar_banner')) }}"
                class="d-md-none img-fit">
        </a>
        <button class="btn text-primary h-100 absolute-top-right set-session" data-key="top-banner" data-value="removed"
            data-toggle="remove-parent" data-parent=".top-banner">
            <i class="la la-close la-2x"></i>
        </button>
    </div>
@endif

<header class=" @if (get_setting('header_stikcy') == 'on') ability-sticky-top @endif z-1020 header-din-bg d-grid">
    @if (get_setting('top_banner_content') != null)
        <div class=" h-40px d-flex justify-content-center align-items-center order-2"
            style="color:{{ get_setting('news_bar_font_color') }} ; background:{{ get_setting('news_bar_color') }}">
            <div class="topbar-width">
                <div class=" top-bar-carousel aiz-carousel arrow-inactive-transparent arrow-md-none" data-autoplay="true"
                    data-fade='false' data-auto-height='true' data-arrows='true'>
                     @foreach (json_decode(get_setting('top_banner_content'), true) as $key => $value)
                    <div class="text-center fs-20">{{ json_decode(get_setting('top_banner_content'), true)[$key] }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- top line  --}}
    <div class="d-none d-lg-block position-relative logo-bar-area  border-md-nonea z-1025 ">
        <div class="container-fluid nav-padding-on-mobile d-flex align-items-center justify-content-center ">
            <div class="container" style="overflow: visible !important;">
                <div class="d-flex align-items-center justify-content-between p-1 pl-1">
                    {{-- logo and burger button  --}}
                    <div
                        class="d-flex align-items-center justify-content-lg-between md-gap justify-content-center w-100-not-lg  pt-2 pb-2">
                        <!-- Header Logo -->
                        <div class="col-auto d-flex align-items-center justify-content-center p-0">
                            <a class="d-block" class="header-link" href="{{ route('home') }}">
                                @php
                                    $header_logo = get_setting('header_logo');
                                    $white_logo = get_setting('system_logo_white');
                                    $black_logo = get_setting('system_logo_black');
                                @endphp
                                @if (url()->current() == url('/') || url()->current() == url('/home'))
                                    @if ($header_logo != null && $white_logo != null && $black_logo != null)
                                        <img src="{{ uploaded_asset($white_logo) }}" alt="{{ env('APP_NAME') }}"
                                            class="mw-100 h-30px h-md-40px logo-white-header " height="40">
                                        <img src="{{ uploaded_asset($black_logo) }}" alt="{{ env('APP_NAME') }}"
                                            class="mw-100 h-30px h-md-40px logo-black-header" height="40">
                                    @else
                                        <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                                            class="mw-100 h-30px h-md-40px" height="40">
                                    @endif
                                @else
                                    @if ($header_logo != null)
                                        <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                                            class="mw-100 h-30px h-md-40px" height="40">
                                    @else
                                        <img src="{{ static_asset('assets/img/logo.png') }}"
                                            alt="{{ env('APP_NAME') }}" class="mw-100 h-30px h-md-40px "
                                            height="40">
                                    @endif
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="d-none d-lg-block front-header-search ">
                        <a href="e.preventDefault();" type="button"
                            class="button-not-button nav-search-button d-flex align-items-center justify-content-between sm-gap"
                            data-toggle="modal" data-target="#searchModal">
                            <span class="text-capitalize ">{{ translate('what are you searching for ?') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="17px" height="18px" viewBox="0 0 18.942 20" class="md:w-4 xl:w-5 md:h-4 xl:h-5">
                                <path d="M381.768,385.4l3.583,3.576c.186.186.378.366.552.562a.993.993,0,1,1-1.429,1.375c-1.208-1.186-2.422-2.368-3.585-3.6a1.026,1.026,0,0,0-1.473-.246,8.343,8.343,0,1,1-3.671-15.785,8.369,8.369,0,0,1,6.663,13.262C382.229,384.815,382.025,385.063,381.768,385.4Zm-6.152.579a6.342,6.342,0,1,0-6.306-6.355A6.305,6.305,0,0,0,375.615,385.983Z" transform="translate(-367.297 -371.285)" fill="black" fill-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                    {{-- icons  --}}
                    <div class="d-flex align-items-center justify-content-center lg-gap ">


                        <!-- Currency Switcher -->
                        @if (get_setting('show_currency_switcher') == 'on')
                            <li class="list-inline-item dropdown ml-auto ml-lg-0 mr-0" id="currency-change">
                                @php
                                    if (Session::has('currency_code')) {
                                        $currency_code = Session::get('currency_code');
                                    } else {
                                        $currency_code = \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code;
                                    }
                                @endphp
                                <a href="javascript:void(0)" class="dropdown-toggle text-secondary fs-12 py-2"
                                    data-toggle="dropdown" data-display="static">
                                    {{ \App\Models\Currency::where('code', $currency_code)->first()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
                                    @foreach (\App\Models\Currency::where('status', 1)->get() as $key => $currency)
                                        <li>
                                            <a class="dropdown-item @if ($currency_code == $currency->code) active @endif"
                                                href="javascript:void(0)"
                                                data-currency="{{ $currency->code }}">{{ $currency->name }}
                                                ({{ $currency->symbol }})
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif


                        @auth
                            <!-- Notifications -->
                            <ul
                                class="d-none d-lg-block list-inline mb-0 h-100 d-none d-lg-flex justify-content-end align-items-center">
                                <li class="list-inline-item dropdown">
                                    <a class="dropdown-toggle no-arrow dyna-color" data-toggle="dropdown"
                                        href="javascript:void(0);" role="button" aria-haspopup="false"
                                        aria-expanded="false">
                                            <span class="position-relative d-inline-block">
                                                <i class="fa-solid fa-bell"></i>
                                                @if (Auth::check() && count(Auth::user()->unreadNotifications) > 0)
                                                    <span
                                                        class="badge badge-primary badge-inline badge-pill absolute-top-right--10px">{{ count(Auth::user()->unreadNotifications) }}</span>
                                                @endif
                                                 <span   class="h5 fs-14 fw-700  mb-0 text-capitalize ">{{ translate('Notifications') }}</span>
                                            </span>
                                    </a>

                                    @auth
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0 rounded-0">
                                            <div class="p-3 bg-light border-bottom">
                                                <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                            </div>
                                            <div class="px-3 c-scrollbar-light overflow-auto " style="max-height:300px;">
                                                <ul class="list-group list-group-flush">
                                                    @forelse(Auth::user()->unreadNotifications as $notification)
                                                        <li class="list-group-item">
                                                            @if ($notification->type == 'App\Notifications\OrderNotification')
                                                                @if (Auth::user()->user_type == 'customer')
                                                                    <a href="{{ route('purchase_history.details', encrypt($notification->data['order_id'])) }}"
                                                                        class="text-secondary fs-12">
                                                                        <span class="ml-2">
                                                                            {{ translate('Order code: ') }}
                                                                            {{ $notification->data['order_code'] }}
                                                                            {{ translate('has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                                        </span>
                                                                    </a>
                                                                @elseif (Auth::user()->user_type == 'seller')
                                                                    <a href="{{ route('seller.orders.show', encrypt($notification->data['order_id'])) }}"
                                                                        class="text-secondary fs-12">
                                                                        <span class="ml-2">
                                                                            {{ translate('Order code: ') }}
                                                                            {{ $notification->data['order_code'] }}
                                                                            {{ translate('has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                                        </span>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item">
                                                            <div class="py-4 text-center fs-16">
                                                                {{ translate('No notification found') }}
                                                            </div>
                                                        </li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                            <div class="text-center border-top">
                                                <a href="{{ route('all-notifications') }}"
                                                    class="text-secondary fs-12 d-block py-2">
                                                    {{ translate('View All Notifications') }}
                                                </a>
                                            </div>
                                        </div>
                                    @endauth
                                </li>
                            </ul>
                        @endauth
 
                                @auth
                                @else
                                   <div class="d-none d-xl-block">
                                    <!--Login & Registration -->
                                        <span class="d-none d-xl-flex align-items-center nav-user-info ">
                                            <a href="e.preventDefault();" type="button"
                                                class="button-not-button dyna-color " data-toggle="modal"
                                                data-target="#loginModal">
                                                <h4
                                                    class="h5 fs-14 fw-700 mb-0 text-capitalize d-flex sm-gap align-items-center">
                                                    <i class="fas fa-user-alt"></i>{{ translate('login') }} |
                                                    {{ translate('Register') }}
                                                </h4>
                                            </a>
                                        </span>
                                    </div>
                                @endauth
 
                        {{-- order tracking --}}
                        <a href="{{ route('orders.track') }}"
                            class="d-none d-lg-flex jsutify-content-center sm-gap align-items-center px-2  dyna-color"
                            title="{{ translate('Order Tracking') }}">
                            <i class="fas fa-box"></i>
                            <span
                                class="h5 fs-14 fw-700  mb-0 text-capitalize ">{{ translate('manage Orders') }}</span>
                        </a>

                        <!--cart -->
                        <div class="nav-cart-box dropdown h-100 d-none d-lg-block position-relative">
                            <button type="button"
                                class="button-not-button  hide-top-menu-bar dyna-color d-flex position-relative sm-gap align-items-center px-2"
                                data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar-cart">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart"
                                    width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="{{Route::currentRouteName() != 'home' ? '#000000' : '#ffffff'}}" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                     <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 17h-11v-14h-2" />
                                    <path d="M6 5l14 1l-1 7h-13" />
                                </svg>
                                <span class="h5 fs-14 fw-700  mb-0 text-capitalize ">{{ translate('Cart') }}</span>
                            </button>
                            <span
                                class="badge badge-primary badge-inline badge-pill absolute-top-right--10px cart_count_pill ">
                                @php
                                    $cartCount = 0;
                                    if (auth()->user() != null) {
                                        $user_id = Auth::user()->id;
                                        $cartCount = \App\Models\Cart::where('user_id', $user_id)->count();
                                    } else {
                                        $temp_user_id = Session()->get('temp_user_id');
                                        if ($temp_user_id) {
                                            $cartCount = \App\Models\Cart::where('temp_user_id', $temp_user_id)->count();
                                        }
                                    }
                                @endphp
                                {{ $cartCount }}
                            </span>

                        </div> 
                                @auth
                                <div class="d-none d-xl-block">
                                    <span class="d-none d-xl-flex align-items-center nav-user-info " id="nav-user-info">
                                        <h4 class="h5 fs-14 fw-700 text-dark ml-2 mb-0">
                                            <a href="{{ route('dashboard') }}">{{ Auth::user()->name }}</a>
                                        </h4>
                                    </span>
                                </div>
                                @endauth

                        <!-- Language switcher -->
                        @if (get_setting('show_language_switcher') == 'on')
                            @php
                                if (Session::has('locale')) {
                                    $locale = Session::get('locale', Config::get('app.locale'));
                                } else {
                                    $locale = 'en';
                                }
                            @endphp
                            <!-- Button trigger modal -->
                            <button type="button" class="button-not-button" data-toggle="modal"
                                data-target="#LanguageModal">
                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ static_asset('assets/img/flags/' . \App\Models\Language::where('code', $locale)->first()->code . '.png') }}"
                                    class="mr-1 lazyload"
                                    alt="{{ \App\Models\Language::where('code', $locale)->first()->name }}"
                                    height="11">
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Loged in user Menus -->
        <div class="hover-user-top-menu position-absolute top-100 left-0 right-0 z-3">
            <div class="container">
                <div class="position-static float-right">
                    <div class="aiz-user-top-menu header-din-bg rounded-0 border-top shadow-sm bg-white"
                        style="width:220px;">
                        <ul class="list-unstyled no-scrollbar mb-0 text-left">
                            @if (isAdmin())
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @else
                                <li class="user-top-nav-element border border-top-0 d-none" data-id="1">
                                    <a href="{{ route('dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @endif

                            @if (isCustomer())
                                <li class="user-top-nav-element border border-top-0 d-none" data-id="1">
                                    <a href="{{ route('purchase_history.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <g id="Group_25261" data-name="Group 25261"
                                                transform="translate(-27.466 -542.963)">
                                                <path id="Path_2953" data-name="Path 2953"
                                                    d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1"
                                                    transform="translate(22.966 537)" fill="#b5b5bf" />
                                                <path id="Path_2954" data-name="Path 2954"
                                                    d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z"
                                                    transform="translate(27.466 536)" fill="#b5b5bf" />
                                                <path id="Path_2955" data-name="Path 2955"
                                                    d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 532)" fill="#b5b5bf" />
                                                <path id="Path_2956" data-name="Path 2956"
                                                    d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 529)" fill="#b5b5bf" />
                                                <path id="Path_2957" data-name="Path 2957"
                                                    d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 526)" fill="#b5b5bf" />
                                                <path id="Path_2958" data-name="Path 2958"
                                                    d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 531.5)" fill="#b5b5bf" />
                                                <path id="Path_2959" data-name="Path 2959"
                                                    d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 528.5)" fill="#b5b5bf" />
                                                <path id="Path_2960" data-name="Path 2960"
                                                    d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 525.5)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Purchase History') }}</span>
                                    </a>
                                </li>
                                <li class="user-top-nav-element border border-top-0 d-none" data-id="1">
                                    <a href="{{ route('digital_purchase_history.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                            viewBox="0 0 16.001 16">
                                            <g id="Group_25262" data-name="Group 25262"
                                                transform="translate(-1388.154 -562.604)">
                                                <path id="Path_2963" data-name="Path 2963"
                                                    d="M77.864,98.69V92.1a.5.5,0,1,0-1,0V98.69l-1.437-1.437a.5.5,0,0,0-.707.707l1.851,1.852a1,1,0,0,0,.707.293h.172a1,1,0,0,0,.707-.293l1.851-1.852a.5.5,0,0,0-.7-.713Z"
                                                    transform="translate(1318.79 478.5)" fill="#b5b5bf" />
                                                <path id="Path_2964" data-name="Path 2964"
                                                    d="M67.155,88.6a3,3,0,0,1-.474-5.963q-.009-.089-.015-.179a5.5,5.5,0,0,1,10.977-.718,3.5,3.5,0,0,1-.989,6.859h-1.5a.5.5,0,0,1,0-1l1.5,0a2.5,2.5,0,0,0,.417-4.967.5.5,0,0,1-.417-.5,4.5,4.5,0,1,0-8.908.866.512.512,0,0,1,.009.121.5.5,0,0,1-.52.479,2,2,0,1,0-.162,4l.081,0h2a.5.5,0,0,1,0,1Z"
                                                    transform="translate(1324 486)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Downloads') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="user-top-nav-element border border-top-0" data-id="1">
                                <a href="{{ route('logout') }}"
                                    class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999"
                                        viewBox="0 0 16 15.999">
                                        <g id="Group_25503" data-name="Group 25503"
                                            transform="translate(-24.002 -377)">
                                            <g id="Group_25265" data-name="Group 25265"
                                                transform="translate(-216.534 -160)">
                                                <path id="Subtraction_192" data-name="Subtraction 192"
                                                    d="M12052.535,2920a8,8,0,0,1-4.569-14.567l.721.72a7,7,0,1,0,7.7,0l.721-.72a8,8,0,0,1-4.567,14.567Z"
                                                    transform="translate(-11803.999 -2367)" fill="#d43533" />
                                            </g>
                                            <rect id="Rectangle_19022" data-name="Rectangle 19022" width="1"
                                                height="8" rx="0.5" transform="translate(31.5 377)"
                                                fill="#d43533" />
                                        </g>
                                    </svg>
                                    <span
                                        class="user-top-menu-name text-primary has-transition ml-3">{{ translate('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- mobile-header-nav --}}
    <div class="d-flex d-lg-none align-items-center justify-content-between mobile-header-nav bg-white order-1">
        <div class="container">
            <div class="d-flex justify-content-between align-ietms-center">
                <div style="width:50px" class="menu d-flex justify-content-cener align-items-center">
                    <button type="button" class=" btn active p-0" data-toggle="class-toggle"
                        data-target=".aiz-top-menu-sidebar">
                        <div style="    display: inline-block;
                        position: relative;"
                            class="hamburger-box">
                            <div class="hamburger-inner"></div>
                            <div class="hamburger-inner"></div>
                            <div class="hamburger-inner"></div>
                        </div>
                    </button>
                </div>
                <div  class="logo">
                    <a class="d-block" class="header-link" href="{{ route('home') }}">
                        @php
                            $header_logo = get_setting('header_logo');
                        @endphp
                        @if ($header_logo != null)
                            <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-40px h-md-50px " height="50">
                        @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-40px h-md-50px " height="50">
                        @endif
                    </a>
                </div>
                 <button style="width:50px" type="button"
                class="button-not-button position-relative  hide-top-menu-bar h5 fs-20 fw-700  mb-0 text-capitalize  d-flex flex-column align-items-center justify-content-center sm-gap "
                data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar-cart">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 256 256" style="enable-background:new 0 0 256 256;" xml:space="preserve" width="50px" height="50px">
                    <style type="text/css">
                        .st0{fill:#FFFFFF !important;}
                        .st1{fill:#EAEAEA !important;}
                        .st2{fill:#5B5B5B !important;}
                    </style>
                    <g>
                        <g>
                            <ellipse transform="matrix(0.7071 -0.7071 0.7071 0.7071 -53.0193 127.9999)" class="st0" cx="128" cy="128" rx="123.39" ry="123.39"></ellipse>
                            <g>
                                <path class="st1" d="M128,5.1c32.83,0,63.69,12.78,86.9,36c23.21,23.21,36,54.07,36,86.9s-12.78,63.69-36,86.9
                                    c-23.21,23.21-54.07,36-86.9,36s-63.69-12.78-86.9-36c-23.21-23.21-36-54.07-36-86.9s12.78-63.69,36-86.9
                                    C64.31,17.89,95.17,5.1,128,5.1 M128,4.1C59.57,4.1,4.1,59.57,4.1,128S59.57,251.9,128,251.9S251.9,196.43,251.9,128
                                    S196.43,4.1,128,4.1L128,4.1z"></path>
                            </g>
                        </g>
                        <g>
                            <g>
                                <path class="st2" d="M155.62,149.68H85.25c-4.63,0-8.39-3.75-8.39-8.38c0-4.63,3.76-8.38,8.39-8.38h59.25l21.23-50.28h25.67
                                    c4.63,0,8.38,3.75,8.38,8.38c0,4.63-3.75,8.38-8.38,8.38h-14.56L155.62,149.68z"></path>
                            </g>
                            <g>
                                <path class="st2" d="M105.35,168.91c0,7.41-6,13.41-13.4,13.41c-7.41,0-13.41-6-13.41-13.41s5.99-13.41,13.41-13.41
                                    C99.35,155.5,105.35,161.51,105.35,168.91z"></path>
                            </g>
                            <g>
                                <path class="st2" d="M155.64,168.91c0,7.41-6,13.41-13.41,13.41c-7.41,0-13.41-6-13.41-13.41s6-13.41,13.41-13.41
                                    C149.64,155.5,155.64,161.51,155.64,168.91z"></path>
                            </g>
                            <polygon class="st2" points="134.14,121.38 81.33,121.38 65.18,82.63 151.33,82.63"></polygon>
                        </g>
                    </g>
                </svg>
                <span class="badge badge-third badge-inline badge-pill absolute-top-left cart_count_pill ">
                @php
                    $cartCount = 0;
                    if (auth()->user() != null) {
                        $user_id = Auth::user()->id;
                        $cartCount = \App\Models\Cart::where('user_id', $user_id)->count();
                    } else {
                        $temp_user_id = Session()->get('temp_user_id');
                        if ($temp_user_id) {
                            $cartCount = \App\Models\Cart::where('temp_user_id', $temp_user_id)->count();
                        }
                    }
                @endphp
                {{ $cartCount }}
            </span>

            </button>


            </div>
        </div>

    </div>
    {{-- bottom line  --}}
    <div class="position-relative header-din-bg h-50px d-none d-lg-block">

        <div class="container h-100 px-4" style="overflow:visible">
            <div class="d-flex h-100 justify-content-between">
                <!-- Header Menus -->
                <div class="w-100 ">
                    {{-- links  --}}
                    <div
                        class="d-none d-lg-flex align-items-center justify-content-center justify-content-xl-start h-100">
                        <ul class="list-inline mb-0 pl-0 hor-swipe c-scrollbar-light d-flex lg-gap" style="overflow:visible">

                             <li class="list-inline-item m-0" >
                                 <nav class="mega navbar navbar-expand-xl mega-navbar-light p-0 ">
                                     <div class="collapse navbar-collapse">
                                    <!-- display on xl only -->
                                    <div class="navbar-nav d-none d-xl-flex">
                                         <li class="">
                                            <a href="{{ route('home') }}"
                                                class=" link-hover-hover nav-link dropdown-hover-button nav-link-custom h5 fs-17 fw-700 mb-0 text-capitalize dyna-color  d-inline-block hover-this-link header_menu_links
                                                    @if (url()->current() == 'home') active @endif">
                                                {{ translate('home') }}
                                            </a>
                                        </li >
                                        @php
                                            $parentCategories = \App\Models\Category::where('parent_id', 0)
                                                ->where('featured', 1)
                                                ->with('childrenCategories')
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                        @endphp
                                        @foreach ($parentCategories as $key => $parent)
                                        <div class="nav-item dropdown-hover parent mx-3 p-0">
                                            <li class="list-inline-item" >
                                            <a class=" link-hover-hover nav-link dropdown-hover-button nav-link-custom h5 fs-17 fw-700 mb-0 text-capitalize dyna-color  d-inline-block hover-this-link header_menu_links"
                                                href="{{ route('category.details', $parent->slug) }}">{{ $parent->getTranslation('name') }}</a>
                                            </li>
                                            @if(count($parent->childrenCategories)>0)
                                                <div class="dropdown-hover-content">
                                                    <ul class="row m-0 p-0">
                                                        @foreach ($parent->childrenCategories as $child)
                                                            <li class="col-4 p-2">
                                                                <a class="text-nowrap sub-child-category  dropdown-hover-button nav-link-custom h5 fs-17 fw-700 mb-0 text-capitaliz"
                                                                  href="{{ route('products.category', $child->slug) }}">{{ $child->getTranslation('name') }}
                                                             </a>
                                                            </li>
                                                           @foreach ($child->categories as $subChild)
                                                            <li class="col-4 p-2">
                                                              <a class="text-nowrap sub-child-category  dropdown-hover-button nav-link-custom h5 fs-15 fw-400 mb-0 text-capitaliz"
                                                                href="{{ route('products.category', $subChild->slug) }}">{{ $subChild->getTranslation('name') }}
                                                                </a>
                                                            </li>
                                                            @endforeach

                                                        @endforeach
                                                   </ul>
                                                </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                  </div>
                             </nav>
                             </li>
                            <li class="d-none">
                                <a href="{{ route('blog') }}"
                                    class="h5 fs-19 fw-700 mb-0 text-capitalize dyna-color  d-inline-block hover-this-link header_menu_links
                                        @if (url()->current() == 'blog') active @endif">
                                    {{ translate('Blogs') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="offers">
                    <a class="nav-small-banner d-flex justify-content-center align-items-center"
                        style="background: {{ get_setting('custom_banner_bg') }} ; color:{{ get_setting('custom_banner_text_color') }}"
                        href="{{ translate(get_setting('custom_banner_link')) }}">{{ translate(get_setting('custom_banner_label')) }}</a>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Top Menu Sidebar -->
<div
    class="aiz-top-menu-sidebar collapse-sidebar-wrap sidebar-xl {{ $locale == 'en' ? 'sidebar-left' : 'sidebar-right' }} d-lg-none z-1035">
    <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar"
        data-same=".hide-top-menu-bar"></div>
    <div
        class="collapse-sidebar c-scrollbar-light text-left d-flex justify-content-between align-items-start flex-column">
        <div class="w-100 p-2">
            <div class="d-flex justify-content-between align-items-center p-3">
                <div></div>
                <button type="button" class=" button-not-button  hide-top-menu-bar" data-toggle="class-toggle"
                    data-target=".aiz-top-menu-sidebar">
                    <i class="fa-solid fs-14 fa-xmark"></i>
                </button>

            </div>
            <ul class="m-0 px-3">
                <li class="mr-0 border-bottom-link">
                    <a href="{{route('home')}}"
                        class="fs-14 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                     @if (url()->current() == 'home') active @endif">
                        {{ translate('Home') }}
                    </a>
                </li>
                @php
                    $parentCategories = \App\Models\Category::where('parent_id', 0)
                        ->where('featured', 1)
                        ->with('childrenCategories')
                        ->orderBy('created_at','asc')
                        ->get();
                @endphp
                @foreach ($parentCategories as $parent)
                    <li class="d-flex justify-content-between align-items-center mr-0 border-bottom-link">
                        <a href="{{ route('products.category', $parent->slug) }}"
                            class="fs-14 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links">{{ $parent->getTranslation('name') }}</a>
                        <a href="#" class="toggle-sidebar" data-value="cat-{{ $parent->id }}">
                            @if ($locale == 'en')
                                <i class="fa-solid fa-chevron-right"></i>
                            @else
                                <i class="fa-solid fa-chevron-left"></i>
                            @endif
                        </a>
                    </li>
                    <div class="cat-{{ $parent->id }} mobile-buttons-sidebar">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="#" class="toggle-sidebar" data-value="cat-{{ $parent->id }}">
                                    @if ($locale == 'en')
                                        <i class="fa-solid fa-chevron-left"></i>
                                    @else
                                        <i class="fa-solid fa-chevron-right"></i>
                                    @endif
                                </a>
                            </div>
                            <div class="col-12">
                                <ul style="gap: 20px;">
                                    @foreach ($parent->childrenCategories as $child)
                                        <li class="d-flex justify-content-between align-items-center mr-0 "
                                            style="gap: 18px;">
                                            <a class="fs-14 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links"
                                                href="{{ route('products.category', $child->slug) }}">{{ $child->getTranslation('name') }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
                @foreach ($parentCategories as $parent)
                    <div class="cat-{{ $parent->id }} mobile-buttons-sidebar">
                        <div class="row mb-2">
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <li class="d-flex justify-content-between align-items-center mr-0 "
                                    style="gap: 18px;">
                                    <a href="{{ route('products.category', $parent->slug) }}"
                                        class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize">{{ $parent->getTranslation('name') }}</a>
                                </li>
                                <a href="#" class="toggle-sidebar" data-value="cat-{{ $parent->id }}">
                                    @if ($locale == 'en')
                                        <i class="fa-solid fa-chevron-left"></i>
                                    @else
                                        <i class="fa-solid fa-chevron-right"></i>
                                    @endif
                                </a>
                            </div>
                        </div>
                        @foreach ($parent->childrenCategories as $child)
                            <li class="mx-4 d-flex justify-content-between align-items-center mr-0 border-bottom-link"
                                style="gap: 18px;">
                                <a class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize"
                                    href="{{ route('products.category', $child->slug) }}">{{ $child->getTranslation('name') }}</a>
                                <a href="#" class="toggle-sidebar" data-value="sub-cat-{{ $child->id }}">

                                    @if ($locale == 'en')
                                        <i class="fa-solid fa-chevron-right"></i>
                                    @else
                                        <i class="fa-solid fa-chevron-left"></i>
                                    @endif
                                </a>
                            </li>
                            <div class="sub-cat-{{ $child->id }} mobile-buttons-sidebar">
                                <div class="col-12 d-flex justify-content-between align-items-center mb-4">
                                    <li class="d-flex justify-content-between align-items-center mr-0 "
                                        style="gap: 18px;">
                                        <a class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize"
                                            href="{{ route('products.category', $child->slug) }}">{{ $child->getTranslation('name') }}</a>
                                    </li>
                                    <a href="#" class="toggle-sidebar"
                                        data-value="sub-cat-{{ $child->id }}">
                                        @if ($locale == 'en')
                                            <i class="fa-solid fa-chevron-left"></i>
                                        @else
                                            <i class="fa-solid fa-chevron-right"></i>
                                        @endif
                                    </a>
                                </div>
                                @foreach ($child->categories as $subChild)
                                    <li
                                        style=" d-flex justify-content-between align-items-center mr-0 border-bottom-link">
                                        <a class=" mx-4 h5 fs-14 fw-700 text-dark mb-0 text-capitalize"
                                            href="{{ route('products.category', $subChild->slug) }}">{{ $subChild->getTranslation('name') }}</a>
                                    </li>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach


            </ul>

            <ul class="m-0 px-3">
                <li class="mr-0 border-bottom-link">
                    <a href="{{route('common_questions')}}" target="_blank"
                        class="fs-14 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links"
                        title="{{ translate('Common questions') }}">
                        <span class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize ">{{ translate('Common questions') }}</span>
                    </a>
                </li>
                <li class="mr-0 border-bottom-link">
                    <a href="{{ route('returnpolicy') }}"
                        class="fs-14 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links"
                        title="{{ translate('return policy') }}">
                        <span class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize ">{{ translate('return policy') }}</span>

                    </a>
                </li>
                <li class="mr-0 border-bottom-link">
                    <a href="{{route('contactus')}}"
                        class="fs-14 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links"
                        title="{{ translate('contact us') }}">
                        <span class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize ">{{ translate('contact us') }}</span>
                    </a>
                </li>
                <li class="mr-0 border-bottom-link">
                    <a href=""
                        class="fs-14 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links"
                        title="{{ translate('payment methods') }}">
                        <span
                            class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize ">{{ translate('payment methods') }}</span>

                    </a>
                </li>



                <li class="mr-0 border-bottom-link">
                    <a href="/about-us"
                        class="fs-14 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links"
                        title="{{ translate('about us') }}">
                        <span
                            class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize ">{{ translate('about us') }}</span>

                    </a>
                </li>
                <li class="mr-0 border-bottom-link">
                    <a href="{{ get_setting('site_location') }}"
                        class="fs-14 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links"
                        title="{{ translate('website map') }}">
                        <span
                            class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize ">{{ translate('website map') }}</span>
                    </a>
                </li>
                  <li class="mr-0 border-bottom-link">
                    <a href="https://wa.me/{{ get_setting('whatsapp_number') }}" target="_blank"
                        class="fs-14 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links"
                        title="{{ translate('Customer Support') }}">
                        <span
                            class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize ">{{ translate('Customer Support') }}</span>
                    </a>
                </li>
                 
                @auth

                @else
                    <li class="mr-0 border-bottom-link">
                        <a href="{{route('user.registration')}}" type="button" class="button-not-button mt-2" >
                            <h4 class="fs-13 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links">
                                {{ translate('Create New Account') }}</h4>
                        </a>
                    </li>
                    <li class="mr-0 border-bottom-link">
                        <a href="e.preventDefault();" data-toggle="modal"
                        data-target="#loginModal" type="button" class="button-not-button mt-2" >
                            <h4 class="fs-13 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links">
                                {{ translate('Login') }}</h4>
                        </a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div id="order-details-modal-body">

            </div>
        </div>
    </div>
</div>
<!-- search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searcheModalLabel"
    aria-hidden="true">
    <div class="modal-dialog search-modal-dialog" role="document">
        <div class="modal-content" style="margin: auto;background-color: transparent;">
            <div class="modal-body search-modal-body">
                <div class="position-relative flex-grow-1 px-3 px-lg-0">
                    <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                        <div class="d-flex position-relative align-items-center">
                            <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                <button class="btn px-2" type="button"><i
                                        class="la la-2x la-long-arrow-left"></i></button>
                            </div>
                            <div class="search-input-box">
                                <input type="text"
                                    class="border border-soft-light form-control fs-14 hov-animate-outline"
                                    id="search" name="keyword"
                                    @isset($query)
                                   value="{{ $query }}"
                               @endisset
                                    placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">

                                <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                                    width="20.001" height="20" viewBox="0 0 20.001 20">
                                    <path id="Path_3090" data-name="Path 3090"
                                        d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                        transform="translate(-1.854 -1.854)" fill="#b5b5bf" />
                                    <path id="Path_3091" data-name="Path 3091"
                                        d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                        transform="translate(-5.2 -5.2)" fill="#b5b5bf" />
                                </svg>
                            </div>
                        </div>
                    </form>
                    <div class="typed-search-box stop-propagation document-click-d-none d-none header-din-bg rounded shadow-lg position-absolute left-0 top-100 w-100"
                        style="min-height: 200px">
                        <div class="search-preloader absolute-top-center">
                            <div class="dot-loader">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                        <div class="search-nothing d-none p-3 text-center fs-16">

                        </div>
                        <div id="search-content" class="text-left">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    @include('frontend.partials.userLogin_modal')
    @include('frontend.partials.register-modal') 
@section('script')
    <script type="text/javascript">
        function show_order_details(order_id) {
            $('#order-details-modal-body').html(null);

            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('orders.details') }}', {
                _token: AIZ.data.csrf,
                order_id: order_id
            }, function(data) {
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }
    </script>
@endsection
<div id="compare">
    @include('frontend.partials.compare')
</div>
<div id="wishlist">
    @include('frontend.partials.wishlist_sidebar')
</div>
<div id="cart_items">
    @include('frontend.partials.cart_sidebar')
</div>


<!-- Modal -->
<div class="modal fade" id="LanguageModal" tabindex="-1" role="dialog" aria-labelledby="LanguageModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="LanguageModalTitle">{{ translate('Change Language') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php
                    if (Session::has('locale')) {
                        $locale = Session::get('locale', Config::get('app.locale'));
                    } else {
                        $locale = 'en';
                    }
                @endphp
                <div class="d-flex flex-column md-gap">
                    @foreach (\App\Models\Language::where('status', 1)->get() as $key => $language)
                        <a href="javascript:void(0)" data-flag="{{ $language->code }}"
                            class="lang-item @if ($locale == $language) active @endif">
                            <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                class="mr-1 lazyload" alt="{{ $language->name }}" height="11">
                            <span class="language font-weight-bold"
                                style="text-transform: capitalize; ">{{ $language->name }}</span>
                            <span class="language font-weight-bold " style="text-transform: Uppercase; "> -
                                {{ $language->app_lang_code }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="fs-14 place-order-button border-0 w-150px"
                    data-dismiss="modal">{{ translate('Close') }}</button>
            </div>
        </div>
    </div>
</div>
