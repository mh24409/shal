@extends('frontend.layouts.app')

@section('content')
    <section style="background-image: url({{ static_asset('assets/img/checkout.jpg') }})"
        class="container-fluid d-flex justify-content-center flex-column align-items-center checkout-header ">
        <div class="overlay"></div>
        <h4 class="text-light" style="z-index: 2;font-family: 'VeryCustomWebFont';">{{ translate('explore') }}</h4>
        <h2 class="text-light font-weight-bold" style="z-index: 2">{{ translate('Brands') }}</h2>
    </section>
    <!-- All Brands -->
    <section class="mb-4">
        <div class="container">
            <div class="bg-white px-3 pt-3">
                <div class="row row-cols-xxl-6 row-cols-xl-6 row-cols-lg-4 row-cols-md-4 row-cols-3 gutters-16 " style="margin-top:64px">
                    @foreach ($brands as $brand)
                        <div class="col text-center border-right border-bottom hov-scale-img has-transition hov-shadow-out z-1">
                            <a href="{{ route('products.brand', $brand->slug) }}" class="d-block p-sm-3">
                                <img src="{{ uploaded_asset($brand->logo) }}" class="lazyload h-md-100px mx-auto has-transition p-2 p-sm-4 mw-100"
                                    alt="{{ $brand->getTranslation('name') }}">
                                <p class="text-center text-dark fs-14 fw-700 mt-2">{{ $brand->getTranslation('name') }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
