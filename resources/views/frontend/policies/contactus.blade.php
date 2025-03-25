@extends('frontend.layouts.app')

@section('meta_title'){{ 'contact us' }}@stop

@section('meta_description'){{ 'you can contact us from here' }}@stop

@section('meta_keywords'){{ 'contact' }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ 'contact us' }}">
    <meta itemprop="description" content="{{ 'you can contact us from here' }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ 'contact us' }}">
    <meta name="twitter:description" content="{{ 'you can contact us from here' }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ 'contact us' }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ URL('contactus') }}" />
    <meta property="og:description" content="{{ 'you can contact us from here' }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection

@section('content')
    <section class="pt-4 mb-4">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <h1 class="fw-600 h4">{{ translate('Contact Us') }}</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item opacity-50">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            <a class="text-reset" href="{{ route('contactus') }}">"{{ translate('Contact Us') }}"</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-4">
        <div class="container ">
            <div class="shadow_and_border-raduis p-6 mb-4" style="min-height: 50vh">
                <form>
                    <div class="fw-600 h4 mb-4">{{ translate('You can contact us via the form below') }}</div>
                    <div class="form-group row mb-4">
                        <div class="fw-600 h5 col-from-label mb-2">{{ translate('Name') }}</div>
                        <input type="text" class="form-control w-100" name="name"
                            placeholder="{{ translate('name') }}" required>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="fw-600 h5 col-from-label mb-2">{{ translate('Email') }}</div>
                        <input type="text" class="form-control w-100" name="email"
                            placeholder="{{ translate('email') }}" required>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="fw-600 h5 col-from-label mb-2">{{ translate('topic') }}</div>
                        <input type="text" class="form-control w-100" name="topic"
                            placeholder="{{ translate('topic') }}" required>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="fw-600 h5 col-from-label mb-2">{{ translate('message') }}</div>
                        <input type="text" class="form-control w-100" name="unit"
                            placeholder="{{ translate('topic') }}" required>
                    </div>
                    <div>
                        <button class="btn btn-primary btn-sm main_add_to_cart_button" >{{ translate('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
