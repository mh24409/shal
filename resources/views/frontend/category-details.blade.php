@extends('frontend.layouts.app')
@section('content')
    <div class="container" style="overflow: visible;">
        <div class="row mt-5">
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-start justify-content-lg-start">
                    <li class="breadcrumb-item ">
                        <a class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize "
                            href="{{ route('home') }}">{{ translate('Home') }}</a>
                    </li>
                    <li class="  breadcrumb-item">
                        <a class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize"
                            href="{{ route('products.category', $category->slug) }}">
                            {{ $category->getTranslation('name') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-6 category-img d-flex align-items-center justify-content-center mt-5 position-relative">
                <img class="w-100" src="{{ uploaded_asset($category->banner) }}" alt="">
                <img width="200%" class="absolute-bottom-left" src="{{ static_asset('assets/img/CategoryPatren.svg') }}" alt="">
            </div>
            <div class="col-md-6 d-flex flex-column align-items-center justify-content-center mt-5">
                <div class="title h5 fs-25 fw-700 text-dark mb-0 text-capitalize mb-3  w-100 ">
                     {!! $category->getTranslation('long_slug') !!}
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6 h5 fs-25 text-dark mb-3 text-capitalize">
                {!! $category->getTranslation('quality') !!}
            </div>
            <div class="col-md-6 h5 fs-25 text-dark mb-3 text-capitalize">
                {!! $category->getTranslation('description_to_store') !!}
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 col-md-6 h5 fw-700 fs-30 text-dark mb-0 text-capitalize">
                {{ translate('what is best products in') . ' ' . $category->getTranslation('name') }}
            </div>
        </div>
        <section class="row mt-5">
            <div class="col-md-3 section-from-db p-2 order1">
                <div class="title h5 fs-25 mb-2 fw-700 text-capitalize">{{ $category->getTranslation('name') }}</div>
                <div class="text h5 fs-20  mb-2 text-capitalize">{{ $category->getTranslation('decription') }}</div>
            </div>
            <div class="col-md-9 order2">
                @php
                    $products = \App\Models\Product::where('category_id', $category->id)->get();
                @endphp
                <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="3" data-xl-items="3" data-lg-items="3"
                    data-md-items="2" data-sm-items="2" data-xs-items="1" data-arrows='false' data-infinite='false'>
                    @foreach ($products as $key => $product)
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

        @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $sub_category_id)
            @php
                $sub_category = \App\Models\Category::find($sub_category_id);
            @endphp
            @if ($key % 2 == 0)
                <section class="row mt-5">
                    <div class="col-md-9 order2">
                        @php
                            $products = \App\Models\Product::where('category_id', $sub_category->id)->get();
                        @endphp
                        <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="3" data-xl-items="3"
                            data-lg-items="3" data-md-items="2" data-sm-items="2" data-xs-items="1" data-arrows='false'
                            data-infinite='false'>
                            @foreach ($products as $key => $product)
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
                    <div class="col-md-3 section-from-db p-2 order1">
                        <div class="title h5 fs-25 mb-2 fw-700 text-capitalize">{{ $sub_category->getTranslation('name') }}
                        </div>
                        <div class="text h5 fs-20  mb-2 text-capitalize">{{ $sub_category->getTranslation('decription') }}
                        </div>
                    </div>
                </section>
            @else
                <section class="row mt-5">
                    <div class="col-md-3 section-from-db p-2 order1">
                        <div class="title h5 fs-25 mb-2 fw-700 text-capitalize">{{ $sub_category->getTranslation('name') }}
                        </div>
                        <div class="text h5 fs-20  mb-2 text-capitalize">{{ $sub_category->getTranslation('decription') }}
                        </div>
                    </div>
                    <div class="col-md-9 order2">
                        @php
                            $products = \App\Models\Product::where('category_id', $sub_category->id)->get();
                        @endphp
                        <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="3" data-xl-items="3"
                            data-lg-items="3" data-md-items="2" data-sm-items="2" data-xs-items="1" data-arrows='false'
                            data-infinite='false'>
                            @foreach ($products as $key => $product)
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
            @endif

            @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($sub_category_id) as $key => $sub_sub_category_id)
                @php
                    $sub_sub_category = \App\Models\Category::find($sub_sub_category_id);
                @endphp
                @if ($key % 2 == 0)
                    <section class="row">
                        <div class="col-md-3 section-from-db p-2 order1">
                            <div class="title h5 fs-25 mb-2 fw-700 text-capitalize">
                                {{ $sub_sub_category->getTranslation('name') }}
                            </div>
                            <div class="text h5 fs-20  mb-2 text-capitalize">
                                {{ $sub_sub_category->getTranslation('decription') }}
                            </div>
                        </div>
                        <div class="col-md-9 order2">
                            @php
                                $products = \App\Models\Product::where('category_id', $sub_sub_category->id)->get();
                            @endphp
                            <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="3" data-xl-items="3"
                                data-lg-items="3" data-md-items="2" data-sm-items="2" data-xs-items="1" data-arrows='false'
                                data-infinite='false'>
                                @foreach ($products as $key => $product)
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
                @else
                    <section class="row">
                        <div class="col-md-9 order2">
                            @php
                                $products = \App\Models\Product::where('category_id', $sub_sub_category->id)->get();
                            @endphp
                            <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="3" data-xl-items="3"
                                data-lg-items="3" data-md-items="2" data-sm-items="2" data-xs-items="1" data-arrows='false'
                                data-infinite='false'>
                                @foreach ($products as $key => $product)
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
                        <div class="col-md-3 section-from-db p-2 order1">
                            <div class="title h5 fs-25 mb-2 fw-700 text-capitalize">
                                {{ $sub_sub_category->getTranslation('name') }}
                            </div>
                            <div class="text h5 fs-20  mb-2 text-capitalize">
                                {{ $sub_sub_category->getTranslation('decription') }}
                            </div>
                        </div>

                    </section>
                @endif
            @endforeach
        @endforeach
        <div> 
            {!! $category->getTranslation('discussion') !!}
        </div>
    </div>
    @include('frontend.partials.tips') 
@endsection
