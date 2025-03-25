@extends('frontend.layouts.app')

@section('content')
    <section style="background-image: url({{ static_asset('assets/img/checkout.jpg') }})"
        class="container-fluid d-flex justify-content-center flex-column align-items-center checkout-header ">
        <div class="overlay"></div>
        <h4 class="text-light" style="z-index: 2;font-family: 'VeryCustomWebFont';">{{ translate('explore') }}</h4>
        <h2 class="text-light font-weight-bold" style="z-index: 2">{{ translate('Flash Deals') }}</h2>
    </section>
    <div class="position-relative">
        <div class="position-absolute" id="particles-js"></div>
        <div class="position-relative container">
            <!-- Breadcrumb -->

            <!-- Banner -->
            @if (get_setting('flash_deal_banner') != null || get_setting('flash_deal_banner_small') != null)
                <div class="mb-3 overflow-hidden hov-scale-img d-none d-md-block">
                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                        data-src="{{ uploaded_asset(get_setting('flash_deal_banner')) }}" alt="{{ env('APP_NAME') }} promo"
                        class="lazyload img-fit h-100 has-transition"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                </div>
                <div class="mb-3 overflow-hidden hov-scale-img d-md-none">
                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                        data-src="{{ get_setting('flash_deal_banner_small') != null ? uploaded_asset(get_setting('flash_deal_banner_small')) : uploaded_asset(get_setting('flash_deal_banner')) }}"
                        alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                </div>
            @endif
            <!-- All flash deals -->
            <section class="mb-4">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 gutters-16">
                    @foreach ($all_flash_deals as $single)
                        <div class="col py-3 h-400px h-xl-475px">
                            <a href="{{ route('flash-deal-details', $single->slug) }}" target="_blank"
                                rel="noopener noreferrer">
                                <div class="h-100 w-100 position-relative hov-scale-img">
                                    <div class="position-absolute overflow-hidden h-100 w-100">
                                        <img src="{{ uploaded_asset($single->banner) }}"
                                            class="img-fit h-100 has-transition"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </div>
                                    <div class="py-5 px-2 px-lg-3 px-xl-5 absolute-top-left w-100">
                                        <div class="bg-white">
                                            <div class="aiz-count-down-circle"
                                                end-date="{{ date('Y/m/d H:i:s', $single->end_date) }}"></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
@endsection

@section('script')
    <script>
        AIZ.plugins.particles();
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
@endsection
