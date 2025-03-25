@extends('frontend.layouts.app')
@section('content')
    <section class="py-5">
        <div class="container" >
            <div class="breadcrumb p-1 d-flex align-items-center sm-gap" style=" background-color:transparent">
                <a href=" {{route('home')}} " class="opacity-60" > {{translate('Home')}} </a>
                <span> <i class="fa-solid opacity-60 fs-10 fa-chevron-left"></i> </span>
                <a href="" class="opacity-60"> {{translate('shopping cart')}} </a>
                <span> <i class="fa-solid fs-10 opacity-60 fa-chevron-left"></i> </span>
                <a href="" class="fw-800"> {{translate('Account info')}} </a>
            </div>
            <div class="d-flex align-items-start">
                <div class="aiz-user-panel">
                
                    @yield('panel_content')
                </div>
            </div>
        </div>
    </section>
    @include('frontend.inc.user_side_nav')
@endsection
