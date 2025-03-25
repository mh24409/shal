@extends('frontend.layouts.app')

@section('content')
    <section class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-5 mx-auto">
                    <div class="card py-2">
                        <div class="text-center pt-5">
                            <h1 class="h2 fs-14 fw-600">
                                {{ translate('Phone Verification') }}
                            </h1>
                            <p class="fs-12"> {{translate('Verification code has been sent. Please wait a few minutes.')}} </p>
                            <a href="{{ route('verification.phone.resend') }}"
                                class="btn btn-link">{{ translate('Resend Code') }}</a>
                        </div>
                        <div class="px-5 py-lg-2">
                            <div class="row align-items-center">
                                <div class="col-12 col-lg">
                                    <form id="otp-form" class="form-default" role="form"
                                        action="{{ route('verification.submit') }}" method="POST">
                                        @csrf
                                        <input type="text" class="form-control" hidden id="verification_code"
                                            name="verification_code">
                                        <div class="input-field mb-2">
                                            <input class="otp-input-number" type="number" />
                                            <input class="otp-input-number" type="number" disabled />
                                            <input class="otp-input-number" type="number" disabled />
                                            <input class="otp-input-number" type="number" disabled />
                                        </div>
                                        <button type="submit"
                                            class="btn btn-block fw-700 fs-10 mt-3 dark-button-style">{{ translate('Verify') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
