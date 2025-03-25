<div class="modal fade" class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div style="transform: translate(0%, 80%) !important;" class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header d-none">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div style="padding: 5px;overflow:visible;" class="modal-body maxHeight position-relative"
                style="overflow: visible;">
                <button type="button" class="close btn btns-danger close-modal" data-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div style="padding-bottom:0 !important;padding-right: 8px !important;" class="p-3">
                    <h3 style="font-weight: 700;font-size: 14px;letter-spacing: 1px;" class="text-dark">
                        {{ translate('Login') }}</h3>
                    <form class="form-default loginRegisterForm LoginWithAjaxForm" role="form" method="POST">
                         
                        @csrf
                        <!-- Email or Phone -->
                        @if (addon_is_activated('otp_system') && env('DEMO_MODE') != 'On')
                            <div class="form-group phone-form-group mb-1">
                                <label style="display: flex;align-items: center;margin-top: 12px !important;"
                                    for="phone" class="fs-10 fw-700 text-soft-dark">{{ translate('Phone') }}
                                    <p style="margin:0 3px 0 0;color: #666 !important;" class="fs-10">
                                        ({{ translate('You Will Recieve NUmber On WhatsApp') }})
                                    </p>
                                </label>
                                <input type="tel" 
                                    class="form-control fs-10 {{ $errors->has('phone') ? ' is-invalid' : '' }} rounded-0" id="phone-code"
                                    value="{{ old('phone') }}" style="font-weight: 700 !important;font-size: 14px;color: #535151;" maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);" style="text-align:left" placeholder="5612 345 67" dir="ltr" name="phone" autocomplete="off">
                            </div>
                            <input type="hidden" name="country_code" value="">
                            <div class="form-group email-form-group mb-1 d-none">
                                <div class="row">
                                    <div class="col-12">
                                        <label style="display: flex;margin: 0;padding: 8px 0 0 0;" for="email"
                                            class="fs-10 fw-700 text-soft-dark">{{ translate('Email') }}
                                            <p style="margin:0 3px 6px 0">
                                                {{ translate('Your Email') }}
                                            </p>
                                        </label>
                                    </div>
                                </div>
                                <input type="email"
                                    class="form-control fs-10 rounded-0 {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    value="{{ old('email') }}" placeholder="{{ translate('johndoe@example.com') }}"
                                    name="email" id="email" autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
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
                                                class="has-transition fs-10 fw-400 text-gray-dark hov-text-primary">{{ translate('Remember Me') }}</span>
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                                <input type="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} rounded-0"
                                    value="{{ old('email') }}" placeholder="{{ translate('johndoe@example.com') }}"
                                    name="email" id="email" autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif

                        <!-- password -->
                        <div class="form-group d-none">
                            <div class="row">
                                <div class="col-6">
                                    <label for="password"
                                        class="fs-12 fw-700 text-soft-dark">{{ translate('Password') }}</label>
                                </div>
                                <div class="col-6 d-flex justify-content-end">

                                    <a href="{{ route('password.request') }}"
                                        class="text-reset fs-12 fw-400 text-gray-dark hov-text-primary"><u>{{ translate('Forgot password?') }}</u></a>
                                </div>
                            </div>

                            <input type="password"
                                class="form-control rounded-0 {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                placeholder="{{ translate('Password') }}" name="password" id="password">
                        </div>
                        <!-- Submit Button -->
                        <div class="mb-4 mt-4">
                            <button type="submit"
                                class="btn btn-block fw-700 fs-14 dark-button-style d-flex justify-content-center align-items-center "> 
                                {{ translate('Login') }} 
                                <span class="d-none request_loader" >
                                <img width="20px" src="{{static_asset('assets/img/loader.svg')}}" >
                                </span> 
                                </button>
                        </div>
                        {{-- hidden inputs  --}}
                        <input type="text" name="formUrl" value="{{ route('login') }}" hidden>
                        <input type="text" name="FromPop" value="from_pop" hidden>
                    </form>
                    <div class="form-group text-center ">
                        <button class="btn btn-link p-0 text-dark fs-10" type="button"
                            onclick="toggleEmailPhone(this)"><i>*{{ translate('Use Email Instead') }}</i></button>
                    </div>
                    <div class="form-group text-right">
                        <button class="button-not-button text-gray " id="openRegisterModal">
                            <span>{{ translate('register') }}</span>
                        </button>
                    </div>
                    @auth
                        <div class="form-group text-right">
                            <button class="button-not-button text-gray" id="openModalButton">
                                <span>{{ translate('Have An Otp Number?') }}</span>
                            </button>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
