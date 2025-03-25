<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header d-none">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body position-relative" style="    overflow: visible;">
                <button type="button" class="close btn btns-danger close-modal" data-dismiss="modal">
                   <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="p-3">
                    <form id="reg-form" class="form-default loginRegisterForm" role="form"
                        action="{{ route('register') }}" method="POST">
                        @csrf

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name"
                                class="fs-12 fw-700 text-soft-dark">{{ translate('Full Name') }}</label>
                            <input type="text"
                                class="form-control rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                value="{{ old('name') }}" placeholder="{{ translate('Full Name') }}" name="name">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Email or Phone -->
                        @if (addon_is_activated('otp_system'))
                            <div class="form-group phone-form-group mb-1">
                                <label for="phone"
                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Phone') }}</label>
                                <input type="tel" id="phone-code"
                                    class="form-control rounded-0{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                    value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                            </div>

                            <input type="hidden" name="country_code" value="">

                            <div class="form-group email-form-group mb-1 d-none">
                                <label for="email"
                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                                <input type="email"
                                    class="form-control rounded-0 {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email"
                                    autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group text-center">
                                <button class="btn btn-link p-0 text-dark" type="button"
                                    onclick="toggleEmailPhone(this)"><i>*{{ translate('Use Email Instead') }}</i></button>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="email"
                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                                <input type="email"
                                    class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email">
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
                                <span class="aiz-square-check"></span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="mb-4 mt-4">
                            <button type="submit"
                                class="btn btn-primary btn-block fw-600 dark-button-style">{{ translate('Create Account') }}</button>
                        </div>
                    </form>
 
                </div>
            </div>
        </div>
    </div>
</div>
