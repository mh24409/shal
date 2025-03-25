@extends('frontend.layouts.app')

@section('content')
    @if (get_setting('login_banner') != null && get_setting('login_banner_small') != null)
        <div class="container p-0 position-relative  d-flex justify-content-around align-items-center">
            <img class="w-100 d-none d-lg-block" src="{{ uploaded_asset(get_setting('login_banner')) }}" alt="">
            <img class="w-100 d-bloack d-lg-none" src="{{ uploaded_asset(get_setting('login_banner_small')) }}" alt="">
        </div>
    @endif

    <div class="container">
        <div class="row justify-content-center">
            <!-- Left Side -->
            <div class="col-lg-5 col-md-7 p-4 p-lg-5">
                <!-- Titles -->
                <div class="text-center">
                    <h1 class="fs-30 fw-700 text-primary">{{ translate('Login / Register') }}
                    </h1>
                </div>
                <div class="pt-1 pt-lg-1">
                    <div class="">
                        <div id="loginFormDisapled" class="">
                            <div class="text-center">
                                <h1 class="fs-20 fs-md-24 fw-700 text-primary">{{ translate('Welcome Back !') }}
                                </h1>
                            </div>
                            <form class="form-default loginRegisterForm" role="form" action="{{ route('login') }}"
                                method="POST">

                                @csrf
                                <!-- Email or Phone -->
                                @if (addon_is_activated('otp_system') && env('DEMO_MODE') != 'On')
                                    <div class="form-group phone-form-group mb-1">
                                        <label for="phone"
                                            class="fs-10 fw-700 text-soft-dark">{{ translate('Phone') }}</label>
                                        <input type="tel" id="phone-code"
                                            class="fs-10 form-control{{ $errors->has('phone') ? ' is-invalid' : '' }} rounded-0"
                                            value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                                    </div>

                                    <input type="hidden" name="country_code" value="">

                                    <div class="form-group email-form-group mb-1 d-none">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="email"
                                                    class="fs-10 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                                            </div>
                                            <div class="col-6 d-flex justify-content-end">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" name="remember"
                                                        {{ old('remember') ? 'checked' : '' }}>
                                                    <span
                                                        class="has-transition fs-10 fw-400 text-gray-dark hov-text-primary">{{ translate('Remember Me') }}</span>
                                                    <span style="margin: 11px 4px;width: 15px;height: 15px;"
                                                        class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <input type="email"
                                            class="form-control fs-10 rounded-0 {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            value="{{ old('email') }}"
                                            placeholder="{{ translate('johndoe@example.com') }}" name="email"
                                            id="email" autocomplete="off">
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group text-right">
                                        <button class="btn fs-10 btn-link p-0 text-primary" type="button"
                                            onclick="toggleEmailPhone(this)"><i>*{{ translate('Use Email Instead') }}</i></button>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="email"
                                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                                            </div>
                                            <div class="col-6 d-flex justify-content-end">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" name="remember"
                                                        {{ old('remember') ? 'checked' : '' }}>
                                                    <span
                                                        class="has-transition fs-12 fw-400 text-gray-dark hov-text-primary">{{ translate('Remember Me') }}</span>
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <input type="email"
                                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} rounded-0"
                                            value="{{ old('email') }}"
                                            placeholder="{{ translate('johndoe@example.com') }}" name="email"
                                            id="email" autocomplete="off">
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                @endif


                                <!-- Submit Button -->
                                <div class="mb-4 mt-4">
                                    <button type="submit"
                                        class="btn btn-block fw-700 fs-14 dark-button-style ">{{ translate('Login') }}</button>
                                </div>
                            </form>
                            <!-- Register Now -->
                            <div class="text-center">
                                <p class="fs-12 text-gray mb-0">{{ translate('Dont have an account?') }}</p>
                                <button href="#" onclick="DisplayRegisterLoginForm()"
                                    class="button-not-button">{{ translate('Register Now') }}</button>
                            </div>
                        </div>
                        <div id="registerFormDisapled" class="d-none">
                            <div class="text-center">
                                <h1 class="fs-20 fs-md-24 fw-700 text-primary">{{ translate('Great to See You Here!') }}
                                </h1>
                            </div>
                            <form id="reg-form" class="form-default loginRegisterForm" role="form"
                                action="{{ route('register') }}" method="POST">
                                @csrf

                                <!-- Name -->
                                <div class="form-group">
                                    <label for="name"
                                        class="fs-10 fw-700 text-soft-dark">{{ translate('Full Name') }}</label>
                                    <input type="text"
                                        class="form-control fs-10 rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                        value="{{ old('name') }}" placeholder="{{ translate('Full Name') }}"
                                        name="name">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback fs-10" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <!-- Email or Phone -->
                                @if (addon_is_activated('otp_system'))
                                    <div class="form-group phone-form-group mb-1">
                                        <label for="phone"
                                            class="fs-10 fw-700 text-soft-dark">{{ translate('Phone') }}</label>
                                        <input type="tel" id="phone-code"
                                            class="form-control rounded-0{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                            value="{{ old('phone') }}" placeholder="" name="phone"
                                            autocomplete="off">
                                    </div>

                                    <input type="hidden" name="country_code" value="">

                                    <div class="form-group email-form-group mb-1 d-none">
                                        <label for="email"
                                            class="fs-10 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                                        <input type="email"
                                            class="form-control rounded-0 {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            value="{{ old('email') }}" placeholder="{{ translate('Email') }}"
                                            name="email" autocomplete="off">
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback fs-10" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group text-right">
                                        <button class="btn btn-link fs-10 p-0 text-primary" type="button"
                                            onclick="toggleEmailPhone(this)"><i>*{{ translate('Use Email Instead') }}</i></button>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="email"
                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                                        <input type="email"
                                            class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            value="{{ old('email') }}" placeholder="{{ translate('Email') }}"
                                            name="email">
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                @endif




                                <!-- Recaptcha -->
                                @if (get_setting('google_recaptcha') == 1)
                                    <div class="form-group">
                                        <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}">
                                        </div>
                                    </div>
                                @endif

                                <!-- Terms and Conditions -->
                                <div class="mb-3">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="checkbox_example_1" required>
                                        <span class="">{{ translate('By signing up you agree to our ') }}
                                            <a href="{{ route('terms') }}"
                                                class="fw-500">{{ translate('terms and conditions.') }}</a></span>
                                        <span class="aiz-square-check"
                                            style="margin: 11px 4px;width: 15px;height: 15px;"></span>
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <div class="mb-4 mt-4">
                                    <button type="submit"
                                        class="btn btn-block fw-700 fs-14 dark-button-style ">{{ translate('Create Account') }}</button>
                                </div>
                            </form>
                            <!-- Register Now -->
                            <div class="text-center">
                                <p class="fs-12 text-gray mb-0">{{ translate('Already have an account?') }}</p>
                                <button onclick="DisplayRegisterLoginForm()"
                                    class="button-not-button">{{ translate('Login Now') }}</button>
                            </div>
                        </div>
                        <!-- DEMO MODE -->

                        <!-- Social Login -->
                        <!-- @if (get_setting('google_login') == 1 ||
                                get_setting('facebook_login') == 1 ||
                                get_setting('twitter_login') == 1 ||
                                get_setting('apple_login') == 1)
    <div class="text-center mb-3">
                                    <span class="bg-white fs-12 text-gray">{{ translate('Or Login With') }}</span>
                                </div>
                                <ul class="d-flex justify-content-center align-items-center sm-gap p-0">
                                    @if (get_setting('facebook_login') == 1)
    <li class="list-inline-item  rounded main_add_to_cart_button px-3 py-2">
                                            <a href="{{ route('social.login', ['provider' => 'facebook']) }}"
                                                class="facebook rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px"
                                                    height="50" viewBox="0,0,256,256" style="fill:#ffffff;">
                                                    <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1"
                                                        stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10"
                                                        stroke-dasharray="" stroke-dashoffset="0" font-family="none"
                                                        font-weight="none" font-size="none" text-anchor="none"
                                                        style="mix-blend-mode: normal">
                                                        <g transform="scale(5.12,5.12)">
                                                            <path
                                                                d="M9,4c-2.74952,0 -5,2.25048 -5,5v32c0,2.74952 2.25048,5 5,5h16.83203c0.10799,0.01785 0.21818,0.01785 0.32617,0h5.67383c0.10799,0.01785 0.21818,0.01785 0.32617,0h8.8418c2.74952,0 5,-2.25048 5,-5v-32c0,-2.74952 -2.25048,-5 -5,-5zM9,6h32c1.66848,0 3,1.33152 3,3v32c0,1.66848 -1.33152,3 -3,3h-8v-14h3.82031l1.40039,-7h-5.2207v-2c0,-0.55749 0.05305,-0.60107 0.24023,-0.72266c0.18718,-0.12159 0.76559,-0.27734 1.75977,-0.27734h3v-5.63086l-0.57031,-0.27149c0,0 -2.29704,-1.09766 -5.42969,-1.09766c-2.25,0 -4.09841,0.89645 -5.28125,2.375c-1.18284,1.47855 -1.71875,3.45833 -1.71875,5.625v2h-3v7h3v14h-16c-1.66848,0 -3,-1.33152 -3,-3v-32c0,-1.66848 1.33152,-3 3,-3zM32,15c2.07906,0 3.38736,0.45846 4,0.70117v2.29883h-1c-1.15082,0 -2.07304,0.0952 -2.84961,0.59961c-0.77656,0.50441 -1.15039,1.46188 -1.15039,2.40039v4h4.7793l-0.59961,3h-4.17969v16h-4v-16h-3v-3h3v-4c0,-1.83333 0.46409,-3.35355 1.28125,-4.375c0.81716,-1.02145 1.96875,-1.625 3.71875,-1.625z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </a>
                                        </li>
    @endif
                                    @if (get_setting('google_login') == 1)
    <li class="list-inline-item  rounded main_add_to_cart_button px-3 py-2">
                                            <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google ">
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px"
                                                    height="50" viewBox="0,0,256,256" style="fill:#ffffff;">
                                                    <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1"
                                                        stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10"
                                                        stroke-dasharray="" stroke-dashoffset="0" font-family="none"
                                                        font-weight="none" font-size="none" text-anchor="none"
                                                        style="mix-blend-mode: normal">
                                                        <g transform="scale(5.12,5.12)">
                                                            <path
                                                                d="M26,2c-12.69141,0 -23,10.30859 -23,23c0,12.69141 10.30859,23 23,23c9.91797,0 15.97266,-4.5625 19.125,-10.21875c3.15234,-5.65625 3.55078,-12.30078 2.59375,-16.84375l-0.1875,-0.78125h-0.78125l-20.75,-0.03125h-1v10.40625h11.4375c-1.72656,4 -5.24219,6.75 -10.4375,6.75c-6.78906,0 -12.28125,-5.49219 -12.28125,-12.28125c0,-6.78906 5.49219,-12.28125 12.28125,-12.28125c3.05078,0 5.82031,1.12891 7.96875,2.96875l0.71875,0.59375l6.84375,-6.84375l0.71875,-0.75l-0.75,-0.6875c-4.08594,-3.72266 -9.53906,-6 -15.5,-6zM26,4c5.07422,0 9.65234,1.85547 13.28125,4.84375l-4.8125,4.8125c-2.37891,-1.77734 -5.26953,-2.9375 -8.46875,-2.9375c-7.87109,0 -14.28125,6.41016 -14.28125,14.28125c0,7.87109 6.41016,14.28125 14.28125,14.28125c6.55078,0 11.26172,-4.01562 12.9375,-9.46875l0.40625,-1.28125h-12.34375v-6.40625l18.84375,0.03125c0.66406,4.03516 0.22266,9.82813 -2.46875,14.65625c-2.85937,5.125 -8.05469,9.1875 -17.375,9.1875c-11.61328,0 -21,-9.39062 -21,-21c0,-11.60937 9.38672,-21 21,-21z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </a>
                                        </li>
    @endif
                                    @if (get_setting('twitter_login') == 1)
    <li class="list-inline-item rounded  main_add_to_cart_button px-3 py-2">
                                            <a href="{{ route('social.login', ['provider' => 'twitter']) }}"
                                                class="twitter rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px"
                                                    height="50" viewBox="0,0,256,256" style="fill:#ffffff;">
                                                    <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1"
                                                        stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10"
                                                        stroke-dasharray="" stroke-dashoffset="0" font-family="none"
                                                        font-weight="none" font-size="none" text-anchor="none"
                                                        style="mix-blend-mode: normal">
                                                        <g transform="scale(5.12,5.12)">
                                                            <path
                                                                d="M11,4c-3.85433,0 -7,3.14567 -7,7v28c0,3.85433 3.14567,7 7,7h28c3.85433,0 7,-3.14567 7,-7v-28c0,-3.85433 -3.14567,-7 -7,-7zM11,6h28c2.77367,0 5,2.22633 5,5v28c0,2.77367 -2.22633,5 -5,5h-28c-2.77367,0 -5,-2.22633 -5,-5v-28c0,-2.77367 2.22633,-5 5,-5zM13.08594,13l9.22266,13.10352l-9.30859,10.89648h2.5l7.9375,-9.29297l6.53906,9.29297h7.9375l-10.125,-14.38672l8.21094,-9.61328h-2.5l-6.83984,8.00977l-5.63672,-8.00977zM16.91406,15h3.06445l14.10742,20h-3.06445z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </a>
                                        </li>
    @endif

                                    @if (get_setting('apple_login') == 1)
    <li class="list-inline-item  rounded main_add_to_cart_button px-3 py-2">
                                            <a href="{{ route('social.login', ['provider' => 'apple']) }}"
                                                class="apple rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px"
                                                    height="50" viewBox="0,0,256,256" style="fill:#ffffff;">
                                                    <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1"
                                                        stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10"
                                                        stroke-dasharray="" stroke-dashoffset="0" font-family="none"
                                                        font-weight="none" font-size="none" text-anchor="none"
                                                        style="mix-blend-mode: normal">
                                                        <g transform="scale(10.66667,10.66667)">
                                                            <path
                                                                d="M16.125,1c-1.153,0.067 -2.47667,0.70934 -3.26367,1.52734c-0.711,0.744 -1.27197,1.84897 -1.04297,2.91797c1.253,0.033 2.51067,-0.62598 3.26367,-1.45898c0.703,-0.779 1.23597,-1.86633 1.04297,-2.98633zM16.19336,5.44336c-1.809,0 -2.56536,1.11133 -3.81836,1.11133c-1.289,0 -2.46734,-1.04102 -4.02734,-1.04102c-2.122,0.001 -5.34766,1.96666 -5.34766,6.59766c0,4.213 3.81766,8.88867 5.97266,8.88867c1.309,0.013 1.62634,-0.82303 3.40234,-0.83203c1.778,-0.013 2.16166,0.84303 3.47266,0.83203c1.476,-0.011 2.6287,-1.63297 3.4707,-2.91797c0.604,-0.92 0.85231,-1.38969 1.32031,-2.42969c-3.473,-0.88 -4.164,-6.48067 0,-7.63867c-0.786,-1.341 -3.08031,-2.57031 -4.44531,-2.57031z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </a>
                                        </li>
    @endif
                                </ul>
    @endif -->
                    </div>


                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var isPhoneShown = true,
            countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phone-code");

        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            if (country.iso2 == 'bd') {
                country.dialCode = '88';
            }
        }

        var iti = intlTelInput(input, {
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
            onlyCountries: @php
                echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray());
            @endphp,
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                if (selectedCountryData.iso2 == 'bd') {
                    return "01xxxxxxxxx";
                }
                return selectedCountryPlaceholder;
            }
        });

        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);

        input.addEventListener("countrychange", function(e) {
            // var currentMask = e.currentTarget.placeholder;

            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

        });

        function toggleEmailPhone(el) {
            if (isPhoneShown) {
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                $('input[name=phone]').val(null);
                isPhoneShown = false;
                $(el).html('<i>*{{ translate('Use Phone Number Instead') }}</i>');
            } else {
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                $('input[name=email]').val(null);
                isPhoneShown = true;
                $(el).html('<i>*{{ translate('Use Email Instead') }}</i>');
            }
        }
    </script>
@endsection
