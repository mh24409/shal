@extends('frontend.layouts.app')
@section('content')
    <div class="container mt-5" style="overflow: visible;">
        <div class="row mt-5">
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-start justify-content-lg-start">
                    <li class="breadcrumb-item ">
                        <a class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize "
                            href="{{ route('home') }}">{{ translate('Home') }}</a>
                    </li>
                    <li class="  breadcrumb-item">
                        <a class="h5 fs-14 fw-700 text-dark mb-0 text-capitalize">{{ translate('Common Questions') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row mt-5">
            @if (get_setting('common_questions') != null)
                @foreach (json_decode(get_setting('common_questions'), true) as $key => $value)
                    <div class="col-md-12 mb-4">
                        <div style="width: 80%;" class="question h5 fs-30 fw-700 mb-4 text-capitalize">
                            {{ json_decode(get_setting('common_questions'), true)[$key] }}</div>
                        <div style="width: 80%;" class="answer h5 fs-20 fw-400 mb-0 text-capitalize">
                            {{ json_decode(get_setting('common_answer'), true)[$key] }}</div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
