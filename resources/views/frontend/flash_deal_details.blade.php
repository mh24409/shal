@extends('frontend.layouts.app')

@section('content')
    <section class="mb-5 mt-3">
        <div class="container">
            <!-- Top Section -->
            <!--<div class="pt-2 pt-lg-4 mb-2 mb-lg-4">-->
                <!-- Title -->
            <!--    <h1 class="h5 fs-30 fw-700 text-dark mb-0 text-capitalize">{!! $flash_deal->getTranslation('small_slug') !!}</h1>-->
            <!--    <p class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize">{{ translate('Limited quantity and period') }}*</p>-->
            <!--</div>-->
            @if (get_setting('flash_deal_banner')  != null) 
                <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}" class="banner1 d-none d-lg-block"  style="background-color:#F6F2F2">
                    <div class="container"> 
                        <img width="100%"
                            src="{{ uploaded_asset(get_setting('flash_deal_banner')) }}"
                            alt=""> 
                    </div>
                </a>
                <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}" class="banner1 d-lg-none d-md-block"  style="background-color:#F6F2F2">
                    <div class="container"> 
                        <img width="100%"
                            src="{{ uploaded_asset(get_setting('flash_deal_banner_small')) }}"
                            alt=""> 
                    </div>
                </a> 
            @endif
            <div class="row gutters-16">
                <!-- Flash Deals Baner & Countdown -->
                <div class="col-xxl-7 col-lg-7">
                    <div class="z-3 sticky-top-flash-deal py-3 py-lg-0 h-400px h-md-570px h-lg-400px h-xl-475px">
                        <div class="h-100 w-100 w-xl-auto"
                            style="background-image: url('{{ uploaded_asset($flash_deal->banner) }}'); background-size: cover; background-position: center center;">
                            <div class="py-5 px-2 px-lg-3 px-xl-5 d-none">
                                <div class="bg-white">
                                    <div class="aiz-count-down-circle"
                                        end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Flash Deals Products -->
                <div class="col-xxl-5 col-lg-5 px-4">
                    <div class="row">
                        <img src="" alt="">
                    </div>
                    <div class="h5 fs-25 fw-700 text-dark mb-0 text-capitalize">

                        {!! $flash_deal->getTranslation('description') !!}
                    </div>

                </div>
                <div class="col-12 mt-5">
                    <div class="section-title mb-5">
                        <div class="content">
                            <div class="h5 fs-325 fw-700 text-dark mb-0 text-capitalize">
                                {{ translate('SHOP BY CATEGORIES') }}
                            </div>
                        </div>
                    </div>
                    <div class="aiz-carousel gutters-16 mx-2 categories-owl-carousel slides-margin" data-items="4" data-xl-items="4" data-lg-items="4"
                        data-md-items="2" data-sm-items="1" data-xs-items="1" data-arrows='true' data-dots='true' data-infinite='false'>
                        @if (get_setting('home_categories') != null)
                            @foreach (json_decode(get_setting('home_categories'), true) as $key => $value)
                                @php
                                    $category = \App\Models\Category::find($value);
                                @endphp
                                <a href="{{ route('products.category', $category->slug) }}"
                                    class="item d-flex justify-content-center align-items-center flex-column md-gap category-flash-card">
                                    <div class="cat-img posistion-relative"> 
                                        <div class="category-name-card" style="z-index:3" >
                                            {{ $category->getTranslation('name') }}
                                        </div>
                                        <img style="z-index:1" class="rounded-50 h-100 w-100" src="{{ uploaded_asset($category->icon) }}"
                                            alt="">
                                    </div> 
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-12 mt-5">
                    @if ($flash_deal->status == 1 && strtotime(date('Y-m-d H:i:s')) <= $flash_deal->end_date)
                        <div class="px-3 z-5">
                            <div
                                class="row row-cols-xxl-4 row-cols-xl-4 row-cols-md-2 row-cols-sm-1 row-cols-1 gutters-16 ">
                                @foreach ($flash_deal->flash_deal_products->take(20) as $key => $flash_deal_product)
                                    @php
                                        $product = \App\Models\Product::find($flash_deal_product->product_id);
                                    @endphp
                                    <?php $uniqueID = uniqid('prefix_' . $product->id . '_'); ?>
                                    @if ($product != null && $product->published != 0)
                                        @php
                                            $product_url = route('product', $product->slug);
                                            if ($product->auction_product == 1) {
                                                $product_url = route('auction-product', $product->slug);
                                            }
                                        @endphp
                                        <div class="col  mb-5 z-1">
                                            @include('frontend.partials.product_box_1', [
                                                'product' => $product,
                                                'uniqueID' => $uniqueID,
                                            ])
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center text-{{ $flash_deal->text_color }}">
                            <h1 class="h3 my-4">{{ $flash_deal->title }}</h1>
                            <p class="h4">{{ translate('This offer has been expired.') }}</p>
                        </div>
                    @endif
                </div>

                <div class="col-12 mt-5">
                    @include('frontend.home_page.banner2')
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
@endsection
