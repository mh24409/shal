@extends('frontend.layouts.app')

@section('content')
    <section style="background-image: url({{ static_asset('assets/img/checkout.jpg') }})"
        class="container-fluid d-flex justify-content-center flex-column align-items-center checkout-header ">
        <div class="overlay"></div>
        <h4 class="text-light" style="z-index: 2;font-family: 'VeryCustomWebFont';">{{ translate('explore') }}</h4>
        <h2 class="text-light font-weight-bold" style="z-index: 2">{{ translate('Categories') }}</h2>
    </section>
    <!-- All Categories -->
    <section class="mb-5 pb-3" style="margin-top:64px">
        <div class="container">
            @foreach ($categories as $key => $category)
                <section class="row">
                    <div class="col-md-3 section-from-db p-2">
                        <div class="category-card position-relative ">
                            <img width="300px" src="{{ uploaded_asset($category->cover_image) }}" alt="">
                            <div class="data absolute-bottom-left ">
                                <div class="p-2">
                                    <div class="name h5 fs-20 fw-700 mb-0 text-capitalize mb-2 ">
                                        {{ $category->getTranslation('name') }}
                                    </div>
                                    <div class="desc mb-2">
                                        {{ $category->meta_description }}
                                    </div>
                                    <div class="button">
                                        <a href="{{ route('products.category', $category->slug) }}"
                                            class="btn btn-md category-shopping-btn">
                                            {{ translate('Shopping Now') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        @php
                            $category_products = [];
                            $category_products = filter_products(\App\Models\Product::where('published', 1)->where('category_id', $category->id))
                                ->latest()
                                ->limit(12)
                                ->get();
                        @endphp
                        <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="3" data-xl-items="3"
                            data-lg-items="3" data-md-items="1" data-sm-items="1" data-xs-items="1" data-arrows='false'
                            data-infinite='false'>
                            @foreach ($category_products as $key => $product)
                                <?php $uniqueID = uniqid('prefix_' . $product->id . '_'); ?>
                                <div class="carousel-box px-3 position-relative has-transition hov-animate-outline ">
                                    @include('frontend.partials.product_box_1', [
                                        'product' => $product,
                                        'uniqueID' => $uniqueID,
                                    ])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endforeach
        </div>
    </section>
@endsection

@section('script')
    <script>
        $('.show-hide-cetegoty').on('click', function() {
            var el = $(this).siblings('ul');
            if (el.hasClass('less')) {
                el.removeClass('less');
                $(this).html('{{ translate('Less') }} <i class="las la-angle-up"></i>');
            } else {
                el.addClass('less');
                $(this).html('{{ translate('More') }} <i class="las la-angle-down"></i>');
            }
        });
    </script>
@endsection
