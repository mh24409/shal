<div class="modal fade" id="CanOrderedModal" tabindex="-1" role="dialog" aria-labelledby="CanOrderedModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="CanOrderedModalLabel"> {{ translate('CanOrdered') }} </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"> ... </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ShouldVerifyModal" tabindex="-1" role="dialog" aria-labelledby="ShouldVerifyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ShouldVerifyModalLabel"></h5> <button type="button" class="close"
                    data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body">
                <div class="text-center pt-5">
                    <h1 class="h2 fw-600">
                        {{ translate('Phone Verification') }}
                    </h1>
                    <p>{{ translate('Verification code has been sent') }}.</p>
                    <a href="{{ route('verification.phone.resend') }}"
                        class="btn btn-link">{{ translate('Resend Code') }}</a>
                </div>
                <div class="px-5 py-lg-5">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg">
                            <form class="form-default" role="form" action="{{ route('verification.submit') }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="input-group input-group--style-1">
                                        <input type="text" class="form-control" name="verification_code">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" name="url"
                                    value="{{ route('checkout.shipping_info') }}">
                                <button type="submit"
                                    class="btn btn-primary btn-block">{{ translate('Verify') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ShouldAuthModal" tabindex="-1" role="dialog" aria-labelledby="ShouldAuthModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ShouldAuthModalLabel"></h5> <button type="button" class="close"
                    data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body">
                <div class="text-center pt-5">
                    <h1 class="h2 fw-600">
                        {{ translate('Your Phone') }}
                    </h1>
                </div>
                <div class="px-5 py-lg-5">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg">
                            <form class="form-default" role="form" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="input-group input-group--style-1">
                                        <input type="tel" class="form-control" maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);"
                                             placeholder="5612 345 67"  id="phone-code" name="phone">
                                    </div>
                                </div>
                                <button type="submit"
                                    class="btn btn-primary btn-block">{{ translate('Submit') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" class="modal fade" id="OTPModal" tabindex="-1" role="dialog" aria-labelledby="OTPModalLabel"
    aria-hidden="true">
    <div style="transform: translate(0%, 50%) !important;" class="modal-dialog modal-dialog-zoom" role="document">
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
                    <div class="row">
                        <div class="col-12">
                            <h3 style="font-weight: bold;font-size: 14px;letter-spacing: 1px;" class="text-dark">
                                {{ translate('Login') }}</h3>
                        </div>
                         @if(auth()->check()) 
                            @if(auth()->user()->email == null) 
                            <div class="col-12" id="phone_text" >
                                <p style="margin:0 3px 0 0" class="fs-12 fw-700 text-soft-dark">
                                    {{ translate('Please Enter An Otp Number That Sent To You ') }}
                                    <span id="phone_response_value">{{substr(auth()->user()->phone, 4)}}</span> </p>
                                <p class="text-dark fs-10 ">
                                    {{ translate('You Will Receive A Massege On Whats App From Shall Store') }}</p>
                            </div> 
                            @else
                            <div class="col-12" id="email_text" >
                                <p style="margin:0 3px 0 0" class="fs-12 fw-700 text-soft-dark">
                                    {{ translate('Please Enter Code That Sent To Mail') }}
                                    <span id="email_response_value">{{auth()->user()->email}}</span> </p>
                            </div>
                            @endif
                          @endif
                         
                        <div class="col-12">
                            <form id="otp-form" class="form-default" role="form"
                                action="{{ route('verification.submit') }}" method="POST">
                                @csrf
                                <input name="redirect" hidden id="otp_redirect_input" >
                                <input type="text" class="form-control" hidden id="verification_code"
                                    name="verification_code">
                                <div style="margin: 15px 0 15px 0;flex-direction: row-reverse;" class="input-field">
                                    <input class="otp-input-number" type="number" />
                                    <input class="otp-input-number" type="number" disabled />
                                    <input class="otp-input-number" type="number" disabled />
                                    <input class="otp-input-number" type="number" disabled />
                                </div> 
                                <button type="submit"
                                    class=" submit-otp mt-2 btn btn-block fw-700 fs-14 dark-button-style d-flex justify-content-center align-items-center">{{ translate('Verify') }} <span class="d-none request_loader" >
                                <img width="20px" src="{{static_asset('assets/img/loader.svg')}}" >
                                </span> </button>
                            </form>
                            <div style="margin:0" class="form-group text-center">
                                <a style="display: flex;justify-content: center;align-items: center;margin-top:5px"
                                    href="{{ route('verification.phone.resend') }}" class="btn btn-link text-dark">
                                    <p style="margin: 0" class="fs-10 fw-700">{{ translate('don"t Recive Code ?') }}
                                    </p>
                                    <h5 style="margin: 0 3px 0 0" class="fs-10 fw-300">{{ translate('Resend Code') }}
                                    </h5>
                                </a>
                            </div>
                            {{-- static  --}}
                            <div class="form-group text-center d-none">
                                <button class="btn btn-link p-0 text-dark" type="button"
                                    onclick="toggleEmailPhone(this)">
                                    <p style="color: #000000 !important;margin:0" class="fs-13">
                                        {{ translate('Use Phone Instead') }}</p>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@auth
    <div class="modal fade" class="modal fade" id="EditAddressModal" tabindex="-1" role="dialog"
        aria-labelledby="EditAddressModalLabel" aria-hidden="true">
        <div style="transform: translate(0%, 80%) !important;" class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header d-none">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div style="padding: 5px;overflow:visible" class="modal-body position-relative"
                    style="    overflow: visible;">
                    <button type="button" class="close btn btns-danger close-modal" data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div style="padding-bottom:0 !important" class="p-3">
                        <div class="row">
                            <div class="col-12">
                                <span class="text-dark fs-15">{{ translate('Edit Your Address') }}</span>
                            </div>
                            <div class="col-12 my-3" id="UpdateAddressFormDiv">

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" class="modal fade" id="AddNewAddressModal" tabindex="-1" role="dialog"
        aria-labelledby="AddNewAddressModalLabel" aria-hidden="true">
        <div style="transform: translate(0%, 20%) !important;" class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header d-none">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div style="padding: 5px;overflow:visible" class="modal-body position-relative"
                    style="    overflow: visible;">
                    <button type="button" class="close btn btns-danger close-modal" data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div style="padding-bottom:0 !important" class="p-3">
                        <form action="" method="post" class="row" id="add_new_address_form">
                            @csrf
                            <div class="col-12">
                                <span class="text-dark fs-15">{{ translate('AddNew Your Address') }}</span>
                            </div>
                            <div class="col-12">
                                <div class="row add-new-address-container p-3 mt-3 ">
                                    <div class="col-6 p-1 ">
                                        <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
                                            <strong>{{ translate('Name') }} <span
                                                    style=" color: var(--primary)">*</span></strong>
                                        </label>
                                        </br>
                                        <input  required name="name" value="{{ Auth::user()->name }}" id="client_name"
                                            type="text" required class="rounded w-lg-75 checkout-input"
                                            placeholder="{{ translate('Name') }} *">
                                    </div>
                                    <div class="col-6 p-1 mb-4">
                                        <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
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
                                                <option value="{{ $state->id }}">
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('state_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 p-1">
                                        <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
                                            <strong>{{ translate('Address') }} <span
                                                    style=" color: var(--primary)">*</span></strong>
                                        </label>
                                        </br>
                                        <input type="text" required class="form-control w-lg-75 mb-3 rounded checkout-input"
                                            placeholder="{{ translate('Your Address') }}" id="address" name="address">
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
                                                <select class="form-control aiz-selectpicker rounded  "
                                                    data-live-search="true"
                                                    data-placeholder="{{ translate('Select your country') }}">
                                                    <option value="">
                                                        {{ translate('Select your country') }}
                                                    </option>
                                                    @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                                        <option value="{{ $country->id }}">
                                                            {{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 p-1">
                                        <label class="h5 fs-10 fw-700 mb-2 text-capitalize" for="phone">
                                            <strong>{{ translate('Phone') }} <span
                                                    style=" color: var(--primary)">*</span></strong>
                                        </label>
                                        </br>
                                        <input name="phone" required  id="phone-code" type="phone" maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);"  dir="ltr" style="text-align:right;" 
                                            class="rounded w-lg-75 checkout-input  " value="{{Auth::user()->phone??''}}"  placeholder="5612 345 67">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </form>
                        <div class="d-flex col-12 justify-content-end align-items-center px-2 mt-3 ">
                            <button onclick="submitNewAddressForm()"
                                class=" btn fs-18 w-100 dark-button-style ">{{ translate('Add New Address') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" class="modal fade" id="EditAddressFromCheckoutModal" tabindex="-1" role="dialog"
        aria-labelledby="EditAddressFromCheckout" aria-hidden="true">
        <div style="transform: translate(0%, 20%) !important;" class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header d-none">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div style="padding: 5px;overflow:visible" class="modal-body position-relative"
                    style="    overflow: visible;">
                    <button type="button" class="close btn btns-danger close-modal" data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div style="padding-bottom:0 !important" class="p-3">
                        <div class="row">
                            <div class="col-12">
                                <span class="text-dark fs-15">{{ translate('Edit Your Address') }}</span>
                            </div>
                            <div class="col-12 my-3" id="UpdateAddressFromCheckoutFormDiv">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endauth
