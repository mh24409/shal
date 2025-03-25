<section class="mb-4">
    <div class="container text-left" style="    overflow: visible !important;">
        {{-- <div class="row mb-4">
                <strong>
                    <h4   style="font-family: 'VeryCustomWebFont';">{{ translate('Payment Selection') }}</h4>
                </strong>
            </div> --}}
        <div class="row">
            <div class=" ">
                @error('country_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <form action="{{ route('payment.checkout') }}" class="form-default  " role="form" method="POST"
                    id="checkout-form">
                    @csrf
                    <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                    <div class="row" style="border:unset">
                        <div class="col-md-7 px-5">
                            <div class="fs-18 fw-700 h-5 mb-3">
                                {{ translate('Shipping and delivery information') }}
                            </div>
                            <div class="col-md-12 p-0 ">
                                <label for="name" class="h5 fs-20 fw-700 mb-2 text-capitalize">
                                    <strong>{{ translate('Name') }} <span
                                            style=" color: var(--primary)">*</span></strong>
                                </label>
                                </br>
                                <input name="name" value="{{ Auth::check() ? Auth::user()->name : '' }}"
                                    id="client_name" type="text" required class="rounded w-lg-75 checkout-input"
                                    placeholder="{{ translate('Name') }} *">
                            </div>
                            <div class="col-md-12 p-0">
                                <label class="h5 fs-20 fw-700 mb-2 text-capitalize" for="phone">
                                    <strong>{{ translate('Phone') }} <span
                                            style=" color: var(--primary)">*</span></strong>
                                </label>
                                </br>
                                <input name="phone" type="phone"
                                    value="{{ Auth::check() ? Auth::user()->phone : '' }}" id="phone_number" required
                                    class="rounded w-lg-75 checkout-input  " placeholder="5612 345 67">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 p-0">
                                <label class="h5 fs-20 fw-700 mb-2 text-capitalize" for="phone">
                                    <strong>{{ translate('Country/Region') }}</strong>
                                </label>
                                </br>
                                <input type="text" value="المملكة العربية السعودية" readonly
                                    class="rounded w-lg-75 checkout-input  ">



                                <input type="hidden" value="{{ $ShouldAuthenticated }}" id="ShouldAuthenticated">
                                <input type="hidden" value="{{ $ShouldVerify }}" id="ShouldVerify">
                                <input type="hidden" value="{{ $CanOrdered }}" id="CanOrdered">
                                <input type="hidden" value="{{ $AutoOrdered }}" id="AutoOrder">

                            </div>
                            <div class="col-md-12 p-0 mb-4">
                                <label for="name" class="h5 fs-20 fw-700 mb-2 text-capitalize">
                                    <strong>{{ translate('State') }} <span
                                            style=" color: var(--primary)">*</span></strong>
                                </label>
                                </br>
                                <select class="form-control checkout-input w-lg-75 rounded aiz-selectpicker"
                                    name="state_id" required id="select_state" data-live-search="true"
                                    data-placeholder="{{ translate('Select your state') }}"
                                    aria-label="{{ translate('Select your state') }}">
                                    <option value="">
                                        {{ translate('Select your state') }}
                                    </option>
                                    @foreach (\App\Models\State::where('country_id', 64)->get() as $key => $state)
                                        <option value="{{ $state->id }}"
                                            {{ Auth::check() && Auth::user()->state == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}</option>
                                    @endforeach
                                </select>
                                @error('state_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 p-0">
                                <label for="email" class="h5 fs-20 fw-700 mb-2 text-capitalize">
                                    <strong>{{ translate('Email') }} </strong> <span
                                        style=" color: var(--primary)">*</span> </label>
                                </br>
                                <input name="email" value="{{ Auth::check() ? Auth::user()->email : '' }}"
                                    type="email" class="rounded w-lg-75 checkout-input " id="client_email"
                                    placeholder="{{ translate('Email') }} ">
                            </div>
                            <div class="col-md-12 p-0">
                                <label for="name" class="h5 fs-20 fw-700 mb-2 text-capitalize">
                                    <strong>{{ translate('Address') }} <span
                                            style=" color: var(--primary)">*</span></strong> </label>
                                </br>
                                <input type="text" class="form-control w-lg-75 mb-3 rounded checkout-input"
                                    placeholder="{{ translate('Your Address') }}"id="address" name="address"
                                    value="{{ Auth::check() ? Auth::user()->address : '' }}" required>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                @if ($country->name == 'Saudi Arabia')
                                    <input hidden name="country_id" value="{{ $country->id }}">
                                @endif
                            @endforeach
                            <div class="row d-none">
                                <div class="col-md-12 p-0">
                                    <div class="mb-3">
                                        <select class="form-control aiz-selectpicker rounded  " data-live-search="true"
                                            data-placeholder="{{ translate('Select your country') }}">
                                            <option value="">
                                                {{ translate('Select your country') }}
                                            </option>
                                            @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                                <option value="{{ $country->id }}">
                                                    {{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-none">
                                <label class="h5 fs-20 fw-700 mb-2 text-capitalize" for="name">
                                    <strong>{{ translate('Order Notes') }}</strong> </label>
                                <textarea name="additional_info" rows="5" class="rounded w-lg-75 checkout-input "
                                    placeholder="{{ translate('Order Notes') }}"></textarea>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="fs-18 fw-700 h-5 mb-3">
                                {{ translate('Order Review') }}
                            </div>
                            <div id="cart_summary">
                                @include('frontend.partials.cart_summary')
                            </div>
                        </div>
                        <div class="col-12 mt-5 p-0">
                            <!-- Payment Options -->
                            <div class="card-body pt-0 w-100">
                                <div class="mb-5">
                                    <label class="h5 fs-20 fw-700 mb-2 text-capitalize" for="phone">
                                        <strong>{{ translate('Shipping company') }}</strong>
                                    </label>
                                    </br>
                                    <input type="text" value=" JNT EXPRESS  (خلال 1 - 5 أيام عمل )" readonly
                                        class="rounded w-50  fs-15 fw-700 text-gray checkout-input  ">
                                </div>
                                <div class="mb-5">
                                    <label class="h5 fs-20 fw-700 mb-2 text-capitalize" for="phone">
                                        <strong>{{ translate('Agree to terms and conditions') }}<span
                                                style=" color: var(--primary)">*</span></strong>
                                    </label>
                                    </br>
                                    <span class="d-flex align-items-center">
                                        <label class="aiz-checkbox fs-15 fw-700 text-soft-gray  ">
                                            <input type="checkbox" required id="agree_checkbox">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                        <span
                                            class="fs-15 fw-700 text-soft-gray">{{ translate('I have read the') }}</span>
                                        <a href="{{ route('terms') }}"
                                            class="fw-700 fs-15 fw-700 text-primary mx-2">{{ translate('terms and conditions') }}</a>
                                        <span
                                            class="fs-15 fw-700 text-soft-gray">{{ translate('and agree to this') }}</span>
                                    </span>
                                </div>
                                <label class="h5 mb-4 fs-14 fw-700 mb-0 text-capitalize" for="phone">
                                    <strong>{{ translate('Payment Method') . '(' . translate('100% safe online shopping') . ')' }}
                                        <span style=" color: var(--primary)">*</span></strong> </label>
                                <div class="row gutters-10 sm-gap">
                                    <!-- Paypal -->
                                    @if (get_setting('paypal_payment') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="paypal" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/paypal.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Paypal') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif

                                    <!-- Paymob -->
                                    @if (get_setting('paymob_payment') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="Paymob" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <input type="hidden" name="paymob_option" id="paymobOptionInput">
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-3 d-flex justify-content-start align-items-center"
                                                    onclick="showPaymobOptions()">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/paymob.png') }}"
                                                        class="mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Paymob') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>

                                        <!-- Modal for Paymob options -->
                                        <div id="paymobOptionsModal" class="modal" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="paymobOptionsModalLabel">
                                                            {{ translate('Select Paymob Option') }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <button class="btn btn-danger" type="button"
                                                            onclick="selectPaymobOption('wallet')">{{ translate('Mobile Wallet') }}</button>

                                                        <button class="btn btn-secondary text-white" type="button"
                                                            onclick="selectPaymobOption('credit_card')">{{ translate('Credit Card') }}</button>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-dark" type="button"
                                                            onclick="closePaymobOptionsModal()">{{ translate('cancel') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (addon_is_activated('paytm') && get_setting('myfatoorah') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="myfatoorah" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="100%" class="d-lg-block d-none"
                                                        src="{{ static_asset('assets/img/cards/myfatoorah.png') }}">
                                                    <div
                                                        class="w-100 d-lg-none d-flex justify-content-between align-items-center p-3">
                                                        <span>{{ translate('Tamara') }}</span>
                                                        <img width="100px"
                                                            src="{{ static_asset('assets/img/cards/myfatoorah.png') }}">
                                                    </div>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!--tamara -->

                                    @if (get_setting('tamara_payment') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="tamara" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-center align-items-center">
                                                    <img width="100%" class="d-lg-block d-none"
                                                        src="{{ static_asset('assets/img/cards/tamara-logo-badge-ar.png') }}">
                                                    <div
                                                        class="w-100 d-lg-none d-flex justify-content-between align-items-center p-3">
                                                        <span>{{ translate('Tamara') }}</span>
                                                        <img width="100px"
                                                            src="{{ static_asset('assets/img/cards/tamara-logo-badge-ar.png') }}">
                                                    </div>
                                                </span>
                                            </label>
                                        </div>
                                    @endif

                                    @if (get_setting('stripe_payment') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="stripe" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/stripe.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Stripe') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- Mercadopago -->
                                    @if (get_setting('mercadopago_payment') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="mercadopago" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/mercadopago.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Mercadopago') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- sslcommerz -->
                                    @if (get_setting('sslcommerz_payment') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="sslcommerz" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/sslcommerz.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('sslcommerz') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- instamojo -->
                                    @if (get_setting('instamojo_payment') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="instamojo" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/instamojo.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Instamojo') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- razorpay -->
                                    @if (get_setting('razorpay') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="razorpay" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/rozarpay.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Razorpay') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- paystack -->
                                    @if (get_setting('paystack') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="paystack" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/paystack.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Paystack') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- voguepay -->
                                    @if (get_setting('voguepay') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="voguepay" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/vogue.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('VoguePay') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- payhere -->
                                    @if (get_setting('payhere') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="payhere" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/payhere.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('payhere') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- ngenius -->
                                    @if (get_setting('ngenius') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="ngenius" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/ngenius.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('ngenius') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- iyzico -->
                                    @if (get_setting('iyzico') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="iyzico" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/iyzico.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Iyzico') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- nagad -->
                                    @if (get_setting('nagad') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="nagad" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/nagad.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Nagad') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- bkash -->
                                    @if (get_setting('bkash') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="bkash" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/bkash.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Bkash') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- aamarpay -->
                                    @if (get_setting('aamarpay') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="aamarpay" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/aamarpay.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Aamarpay') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- authorizenet -->
                                    @if (get_setting('authorizenet') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="authorizenet" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/authorizenet.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Authorize Net') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- payku -->
                                    @if (get_setting('payku') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="payku" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/payku.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Payku') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- African Payment Getaway -->
                                    @if (addon_is_activated('african_pg'))
                                        <!-- flutterwave -->
                                        @if (get_setting('flutterwave') == 1)
                                            <div class="col-md-2 p-0 mx-1">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input value="flutterwave" class="online_payment" type="radio"
                                                        name="payment_option" checked>
                                                    <span
                                                        class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                        <img width="50px"
                                                            src="{{ static_asset('assets/img/cards/flutterwave.png') }}"
                                                            class=" mb-2">
                                                        <span class="d-block text-center mr-3 ml-3">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('flutterwave') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                        <!-- payfast -->
                                        @if (get_setting('payfast') == 1)
                                            <div class="col-md-2 p-0 mx-1">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input value="payfast" class="online_payment" type="radio"
                                                        name="payment_option" checked>
                                                    <span
                                                        class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                        <img width="50px"
                                                            src="{{ static_asset('assets/img/cards/payfast.png') }}"
                                                            class=" mb-2">
                                                        <span class="d-block text-center mr-3 ml-3">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('payfast') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    <!--paytm -->
                                    @if (addon_is_activated('paytm') && get_setting('paytm_payment') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="paytm" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/paytm.jpg') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Paytm') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- toyyibpay -->
                                    @if (addon_is_activated('paytm') && get_setting('toyyibpay_payment') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="toyyibpay" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span
                                                    class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/toyyibpay.png') }}"
                                                        class=" mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('ToyyibPay') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- myfatoorah -->
                                    <!-- khalti -->
                                    @if (addon_is_activated('paytm') && get_setting('khalti_payment') == 1)
                                        <div class="col-md-2 p-0 mx-1">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="Khalti" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem p-3">
                                                    <img width="50px"
                                                        src="{{ static_asset('assets/img/cards/khalti.png') }}"
                                                        class="img-fluid mb-2">
                                                    <span class="d-block text-center mr-3 ml-3">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Khalti') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- Cash Payment -->
                                    @if (get_setting('cash_payment') == 1)
                                        @php
                                            $digital = 0;
                                            $cod_on = 1;
                                            foreach ($carts as $cartItem) {
                                                $product = \App\Models\Product::find($cartItem['product_id']);
                                                if ($product['digital'] == 1) {
                                                    $digital = 1;
                                                }
                                                if ($product['cash_on_delivery'] == 0) {
                                                    $cod_on = 0;
                                                }
                                            }
                                        @endphp
                                        @if ($digital != 1 && $cod_on == 1)
                                            <div class="col-md-2 p-0 mx-1">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input value="cash_on_delivery" class="online_payment"
                                                        type="radio" name="payment_option" checked>
                                                    <span
                                                        class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                        <img width="100%" class="d-lg-block d-none"
                                                            src="{{ static_asset('assets/img/cards/cod.png') }}">
                                                        <div
                                                            class="w-100 d-lg-none d-flex justify-content-between align-items-center p-3">
                                                            <span>{{ translate('Tamara') }}</span>
                                                            <img width="100px"
                                                                src="{{ static_asset('assets/img/cards/cod.png') }}">
                                                        </div>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::check())
                                        @if (addon_is_activated('offline_payment'))
                                            @foreach (\App\Models\ManualPaymentMethod::all() as $method)
                                                <div class="col-md-2 p-0 mx-1">
                                                    <label class="aiz-megabox d-block mb-3">
                                                        <input value="{{ $method->heading }}" type="radio"
                                                            name="payment_option" class="offline_payment_option"
                                                            onchange="toggleManualPaymentData({{ $method->id }})"
                                                            data-id="{{ $method->id }}" checked>
                                                        <span
                                                            class="d-block aiz-megabox-elem rounded p-1 d-flex jusitfy-content-start align-items-center">
                                                            <img width="50px"
                                                                src="{{ uploaded_asset($method->photo) }}"
                                                                class=" mb-2">
                                                            <span class="d-block text-center mr-3 ml-3">
                                                                <span
                                                                    class="d-block fw-600 fs-15">{{ $method->heading }}</span>
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach

                                            @foreach (\App\Models\ManualPaymentMethod::all() as $method)
                                                <div id="manual_payment_info_{{ $method->id }}" class="d-none">
                                                    @php echo $method->description @endphp
                                                    @if ($method->bank_info != null)
                                                        <ul>
                                                            @foreach (json_decode($method->bank_info) as $key => $info)
                                                                <li>{{ translate('Bank Name') }} -
                                                                    {{ $info->bank_name }},
                                                                    {{ translate('Account Name') }} -
                                                                    {{ $info->account_name }},
                                                                    {{ translate('Account Number') }} -
                                                                    {{ $info->account_number }},
                                                                    {{ translate('Routing Number') }} -
                                                                    {{ $info->routing_number }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    @endif
                                </div>

                                <!-- Offline Payment Fields -->
                                @if (addon_is_activated('offline_payment'))
                                    <div class="d-none mb-3 rounded border bg-white p-3 text-left">
                                        <div id="manual_payment_description">

                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>{{ translate('Transaction ID') }} <span
                                                        class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control mb-3" name="trx_id"
                                                    id="trx_id" placeholder="{{ translate('Transaction ID') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ translate('Photo') }}</label>
                                            <div class="col-md-9">
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div
                                                            class="input-group-text bg-soft-secondary font-weight-medium">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">
                                                        {{ translate('Choose image') }}
                                                    </div>
                                                    <input type="hidden" name="photo" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Wallet Payment -->
                                @if (Auth::check() && get_setting('wallet_system') == 1)
                                    <div class="py-4 px-4 text-center bg-soft-warning mt-4">
                                        <div class="fs-14 mb-3">
                                            <span
                                                class="opacity-80">{{ translate('Or, Your wallet balance :') }}</span>
                                            <span class="fw-700">{{ single_price(Auth::user()->balance) }}</span>
                                        </div>
                                        @if (Auth::user()->balance < $total)
                                            <button type="button" class="btn btn-secondary" disabled>
                                                {{ translate('Insufficient balance') }}
                                            </button>
                                        @else
                                            <button type="button" onclick="use_wallet()"
                                                class="btn btn-primary fs-14 fw-700 px-5 rounded">
                                                {{ translate('Pay with wallet') }}
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-end align-items-center px-4 mb-4 mt-4 w-100">
                                <button type="button" onclick="submitOrder(this)"
                                    class="btn btn-primary fs-18 w-100 main_add_to_cart_button">{{ translate('Place Order') }}</button>
                            </div>
                        </div>


                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
