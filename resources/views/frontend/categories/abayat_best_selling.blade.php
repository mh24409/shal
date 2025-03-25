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
                        <a class="h5 fs-15 fw-700 text-dark mb-0 text-capitalize">{{ translate('abayat best selling') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-6 category-img d-flex align-items-end justify-content-center position-relative">
                <img class="w-100" src="{{ uploaded_asset(get_setting('abayat_category_image')) }}" alt="">
                <img width="200%" class="absolute-bottom-left" src="{{ static_asset('assets/img/CategoryPatren.svg') }}"
                    alt="">
            </div>
            <div class="col-md-6 d-flex flex-column align-items-center justify-content-center mt-5">
                <div class="title h5 fs-25 fw-700 text-dark mb-0 text-capitalize mb-3  w-100 ">
                    {!! get_setting('abayat_category__slug') !!}
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6 h5 fs-25 text-dark mb-3 text-capitalize">
                {!! get_setting('abayat_category_quality') !!}
            </div>
            <div class="col-md-6 h5 fs-25 text-dark mb-3 text-capitalize">
                {!! get_setting('abayat_description_to_store') !!}
            </div>
        </div>
        @foreach ($categories as $key => $category)
        @if(count($category->products))
            @if ($key % 2 == 0)
                <section class="row mt-5">
                    <div class="col-md-3 section-from-db p-2 order1">
                        <div style="color: #656464;font-size: 23px !important;"
                            class="title h5 mb-2 fw-700 text-capitalize">{{ translate('Best Selling') }}:
                        </div>
                        <div style="font-size: 26px;color: #656464" class="title h5 fs-25 mb-2 fw-700 text-capitalize">
                            {{ $category->getTranslation('name') }}
                        </div>
                        <div style="font-size: 17px;color: #3e3e3e;width: 89%;">{{strip_tags($category->getTranslation('description'))}}
                            </div>
                        <div class="button"><a class="btn btn-md section-btn w-150px py-1 text-white"
                                href="{{ route('products.category',$category->slug) }}">{{ translate('view all') }}</a>
                        </div>
                    </div>
                    <div class="col-md-9 order2">
                        <div class="aiz-carousel sm-gutters-16 arrow-none"  data-dots='true' data-items="3" data-xl-items="3"
                            data-lg-items="3" data-md-items="2" data-sm-items="2" data-xs-items="2" data-arrows='false'
                            data-infinite='false'>
                            @foreach ($category->products as $key => $product)
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
                <section class="row mt-5">
                    <div class="col-md-9 order2">
                        <div class="aiz-carousel sm-gutters-16 arrow-none"  data-dots='true' data-items="3" data-xl-items="3"
                            data-lg-items="3" data-md-items="2" data-sm-items="2" data-xs-items="2" data-arrows='false'
                            data-infinite='false'>
                            @foreach ($category->products as $key => $product)
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
                    <div class="col-md-3 section-from-db px-3 order1">
                        <div style="color: #656464;font-size: 23px !important;"
                            class="title h5 mb-2 fw-700 text-capitalize">{{ translate('Best Selling') }}:
                        </div>
                        <div style="font-size: 26px;color: #656464" class="title h5 fs-25 mb-2 fw-700 text-capitalize">
                            {{ $category->getTranslation('name') }}
                        </div> 
                        <div style="font-size: 17px;color: #3e3e3e;width: 89%;">{{strip_tags($category->getTranslation('description'))}}
                            </div>
                        <div class="button"><a class="btn btn-md section-btn w-150px py-1 text-white"
                                href="{{ route('products.category',$category->slug) }}">{{ translate('view all') }}</a>
                        </div>
                    </div>
                </section>
            @endif 
        @endif
        @endforeach
        <div>
            {!! get_setting('abayat_category_discussion') !!}
        </div>
        <div class="">
            @include('frontend.home_page.banner1')
        </div>
    </div>
    @include('frontend.partials.tips')
@endsection
