@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="row gutters-16 mt-2">
        <div class="col-md-12">
            <h4> {{ translate('Hello') }}
                <span class="opacity-70 text-capitalize"> {{ Auth::user()->name }} </span>
            </h4>

        </div>
        <div style="margin-top: 20px;" class="col-md-6">
            <div  id="profile_info">
                <div class="card" style="border-radius: var(--border-raduis) !important;">
                    <div class="" id="headingProfile">
                        <h5 class="mb-0">
                            <button
                                class="btn btn-link fs-18 text-dark fw-700 w-100 h-100 d-flex justify-content-between align-items-center"
                                data-toggle="collapse" data-target="#collapseProfile" aria-expanded="true"
                                aria-controls="collapseProfile">
                                <span>{{ translate('Account Info.') }}</span>
                                {{-- <p>{{ translate('Edit') }}</p> --}}

                                <i style="display:none" id="profile-plus-icon" class="fa-solid fa-plus"></i>
                                <i id="profile-minus-icon" class="fa-solid fa-minus"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseProfile" class="collapse show " aria-labelledby="headingProfile"
                        data-parent="#profile_info">
                        <div style="padding: 20px 15px" class="card-body read-more">
                            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- Photo-->
                                <div style="display: flex;align-items:center" class="form-group ">
                                    <div style="padding:0" class="col-4">
                                        <img width="80%"
                                            style="border-radius: var(--border-raduis) !important;background-color:rgb(243 240 240 / 48%)"
                                            src="{{ uploaded_asset(Auth::user()->avatar_original) }}"
                                            alt="{{ Auth::user()->name }}">
                                    </div>
                                    <div class="">
                                        <div style="text-align: center;font-weight:bold;" class=" col-form-label fs-13">{{ translate('Personal Image') }}</div>
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div style="margin-top: 0 !important;padding: 5px 60px"
                                                    class="btn rounded-0 w-100 mt-3 dark-button-style">
                                                    {{ translate('Browse') }}
                                                </div>
                                            </div>
                                            {{-- <div class="form-control file-amount h-100">{{ translate('Choose File') }}</div> --}}
                                            <input type="hidden" name="photo" value="{{ Auth::user()->avatar_original }}"
                                                class="selected-files">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                                <!-- Name-->
                                <div style="display: flex;align-items: center;margin-top: 40px;" class="">
                                    <label style="font-weight: bold;letter-spacing:1px;padding-right:0" class="col-4 fs-14">{{ translate('Your Name') }} </label>
                                    <div class="">
                                        <input style="width:130%;margin-bottom:0" type="text" class="form-control checkout-input"
                                            placeholder="{{ translate('Your Name') }}" name="name"
                                            value="{{ Auth::user()->name }}">
                                    </div>
                                </div>
                                    <!-- Phone-->
                                    <div style="display: flex;align-items: center;margin-top: 20px;" class="">
                                        <label style="font-weight: bold;letter-spacing:1px;padding-right:0" class="col-4 fs-14">{{ translate('Your Phone') }}</label>
                                        <div class="">
                                            <input style="width:130%;margin-bottom:0;text-align:right;" type="text" dir="ltr"  class="form-control checkout-input" placeholder="+966"
                                                name="phone" value="{{ Auth::user()->phone }}">
                                        </div>
                                    </div>

                                <!--Email -->
                                {{-- <div style="display: flex;align-items: center;margin-top: 20px;"  class="">
                                    <label style="font-weight: bold;letter-spacing:1px;padding-right:0" class="col-4 fs-13 "
                                        >{{ translate('Your Email') }} </label>
                                    <div class="">
                                        <input style="width:130%;margin-bottom:10px" type="text" class="form-control checkout-input"
                                            placeholder="{{ translate('Your Emial') }}" name="name"
                                            value="{{ Auth::user()->email }}">
                                    </div>
                                </div> --}}

                                <!-- Password-->
                                <div class="form-group row d-none">
                                    <label class="col-3 col-form-label fs-14">{{ translate('Your Password') }}</label>
                                    <div class="col-9">
                                        <input type="password" class="form-control checkout-input"
                                            placeholder="{{ translate('New Password') }}" name="new_password">
                                    </div>
                                </div>
                                <!-- Confirm Password-->
                                <div class="form-group row d-none">
                                    <label class="col-3 col-form-label fs-14">{{ translate('Confirm Password') }}</label>
                                    <div class="col-9">
                                        <input type="password" class="form-control checkout-input"
                                            placeholder="{{ translate('Confirm Password') }}" name="confirm_password">
                                    </div>
                                </div>
                                <!-- Submit Button-->
                                <div class="form-group mb-0 text-right">
                                    <button style="margin-top: 20px !important" type="submit"
                                        class="btn rounded-0 w-100 mt-3 dark-button-style">{{ translate('Update Profile') }}</button>
                                </div>
                            </form>
                            <form action="{{ route('user.change.email') }}" class="mt-4" method="POST">
                                @csrf
                                <div class="row">
                                    <label class="col-3 col-form-label fs-14">{{ translate('Email') }}</label>
                                    <div class="col-9">
                                        <div class="input-group mb-3">
                                            <input type="email" class="form-control rounded-0 "
                                                style="background-color: #e4dfdf52;;height: 48px;border: unset;"
                                                placeholder="{{ translate('Your Email') }}" name="email"
                                                value="{{ Auth::user()->email }}" />
                                            <div class="input-group-append">
                                                <button type="button"
                                                    class="btn dark-button-style new-email-verification">
                                                    <span class="d-none loading">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>{{ translate('Sending Email...') }}
                                                    </span>
                                                    <span class="default">{{ translate('Verify') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit"
                                        class="btn btn-primary rounded-0 w-100 dark-button-style mt-3">{{ translate('Update Email') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div id="wishlists_info">
                <div class="card" style="border-radius: var(--border-raduis) !important;">
                    <div class="" id="headingShippingAddressCollapse">
                        <h5 class="mb-0">
                            <button
                                class="btn btn-link fs-18 text-dark fw-700 w-100 h-100 d-flex justify-content-between align-items-center"
                                data-toggle="collapse" data-target="#collapseShippingAddressCollapse"
                                aria-expanded="true" aria-controls="collapseShippingAddressCollapse">
                                <div class="d-flex sm-gap">
                                    <span class="fs-14 fw-700">{{ translate('Shipping Address') }}</span>

                                    <i style="display:none" id="ShippingAddressCollapse-plus-icon"
                                        class="fa-solid fa-angle-down"></i>
                                    <i id="ShippingAddressCollapse-minus-icon" class="fa-solid fa-angle-up"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="shipping_address_collapse">
                        <div class="card" style="border-radius: var(--border-raduis) !important;border: none !important;box-shadow: none !important;">
                            <div id="collapseShippingAddressCollapse" class="collapse show"
                                aria-labelledby="headingShippingAddressCollapse" data-parent="#shipping_address_collapse">
                                <div style="padding:20px 10px !important" class="card-body  pr-4 ">
                                    <div class="d-flex justify-content-center">
                                        <form method="POST" action="{{ route('addresses.set_default') }}"
                                            class="row w-100">
                                            @csrf
                                            @foreach (\App\Models\Address::where('user_id', Auth()->user()->id)->get() as $address)
                                                <div class="col-md-12 p-0 mx-1 mb-3">
                                                    <label class="aiz-megabox d-block ">
                                                        <input value="{{ $address->id }}" class="online_payment custom-radio-input "
                                                            type="radio" name="address_id" {{ $address->set_default == 1 ? 'checked' : '' }}>
                                                        <span
                                                            class=" d-block aiz-megabox-elem rounded d-flex jusitfy-content-center align-items-center">
                                                            <div
                                                                class=" rounded bg-white w-100 d-flex justify-content-between align-items-center sm-gap align-items-center p-3">
                                                                <div class="d-flex sm-gap">
                                                                    <div class="d-flex flex-column mx-3">
                                                                        <span style="font-weight: bold;padding: 0 10px;color:black"
                                                                            class="fs-14">{{ translate(\App\Models\Country::find($address->country_id)->name) . ' ' . \App\Models\State::find($address->state_id)->name . ' ' . $address->address }}</span>
                                                                    </div>
                                                                </div>
                                                                <span>
                                                                    <span class="fs-10 fw-700 text-dark d-flex sm-gap ">

                                                                        <button style="padding-left: 20px;"
                                                                            onclick="OpenEditAddressModal(event,{{ $address->id }})"
                                                                            class="button-not-button text-dark fs-14">
                                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                                        </button>
                                                                        <a href="{{ route('addresses.destroy', $address->id) }}"
                                                                            class="button-not-button text-danger fs-14"><i
                                                                                class="fa-solid fa-trash-can"></i></a>
                                                                    </span>
                                                                </span>

                                                            </div>
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach
                                            <div class="d-flex col-12 justify-content-end align-items-center p-0 ">
                                                <button
                                                    class=" btn fs-18 w-100 dark-button-style ">{{ translate('Save Changes') }}</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div id="addNewAddressDev"
                                        class="add-new-address-container mt-2 p-3 {{ count(\App\Models\Address::where('user_id', Auth()->user()->id)->get()) < 1 ? 'd-none' : 'd-none' }} ">
                                        <form action="{{ route('addresses.store') }}" class="row gutters-10  "
                                            method="POST">
                                            @csrf
                                            <div class="col-6 p-1 ">
                                                <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
                                                    <strong>{{ translate('Name') }} <span
                                                            style=" color: var(--primary)">*</span></strong>
                                                </label>
                                                </br>
                                                <input name="name"
                                                    value="{{ Auth::check() ? Auth::user()->name : '' }}"
                                                    id="client_name" type="text" required
                                                    class="rounded w-lg-75 checkout-input"
                                                    placeholder="{{ translate('Name') }} *">
                                            </div>
                                            <div class="col-6 p-1 mb-4">
                                                <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
                                                    <strong>{{ translate('State') }} <span
                                                            style=" color: var(--primary)">*</span></strong>
                                                </label>
                                                </br>
                                                <select
                                                    class="form-control checkout-input w-lg-75 rounded aiz-selectpicker"
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
                                            <div class="col-6 p-1">
                                                <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
                                                    <strong>{{ translate('Address') }} <span
                                                            style=" color: var(--primary)">*</span></strong>
                                                </label>
                                                </br>
                                                <input type="text"
                                                    class="form-control w-lg-75 mb-3 rounded checkout-input"
                                                    placeholder="{{ translate('Your Address') }}"id="address"
                                                    name="address"
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
                                                        <select class="form-control aiz-selectpicker rounded  "
                                                            data-live-search="true"
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
                                            <div class="col-6 p-1">
                                                <label class="h5 fs-10 fw-700 mb-2 text-capitalize" for="phone">
                                                    <strong>{{ translate('Phone') }} <span
                                                            style=" color: var(--primary)">*</span></strong>
                                                </label>
                                                </br>
                                                <input name="phone" type="phone" dir="ltr" style="text-align:right;"
                                                    value="{{ Auth::check() ? Auth::user()->phone : '' }}"
                                                    id="phone_number" required class="rounded w-lg-75 checkout-input  "
                                                    placeholder="05xxxxxxxx">
                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="d-flex col-12 justify-content-end align-items-center px-2 ">
                                                <button
                                                    class=" btn fs-18 w-100 dark-button-style ">{{ translate('Add New Address') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div style="padding: 10px 15px !important;border: dashed rgb(206, 205, 205) 2px;margin-top: 20px !important;" class=" add-new-address-container-button p-2 mt-2 rounded">
                                        <a onclick="showAddNewAddress()"
                                            class="text-gray">{{ translate('Add New Address') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div id="orders_info">
                <div class="card" style="border-radius: var(--border-raduis) !important;">
                    <div class="" id="headingorders">
                        <h5 class="mb-0">
                            <button
                                class="btn btn-link fs-18 text-dark fw-700 w-100 h-100 d-flex justify-content-between align-items-center"
                                data-toggle="collapse" data-target="#collapseorders" aria-expanded="true"
                                aria-controls="collapseorders">
                                <span>{{ translate('Previous Orders') }}</span>
                                <i style="display:none" id="orders-plus-icon" class="fa-solid fa-plus"></i>
                                <i id="orders-minus-icon" class="fa-solid fa-minus"></i>
                            </button>
                        </h5>
                    </div>

                    <div id="collapseorders" class="collapse show" aria-labelledby="headingorders"
                        data-parent="#orders_info">
                        <div class="card-body read-more">
                            @php
                            $orders = \App\Models\Order::with('orderDetails')
                                ->where('user_id', Auth::user()->id)
                                ->orderBy('code', 'desc')
                                ->paginate(10);
                        @endphp
                        @foreach ($orders as $key => $order)
                        @if (count($order->orderDetails) > 0)
                            {{-- start test  --}}
                            <div style="display: flex; justify-content: space-around;align-items: center;text-align: center;background: #f7f7f7;padding: 10px;font-weight:bold; margin-top: 10px;" class="order">
                                <div class="orderID">
                                    <p style="margin-bottom:5px">{{ translate('Order Code') }}</p>
                                    <p style="margin-bottom:5px"> <a
                                        href="{{ route('purchase_history.details', encrypt($order->id)) }}">{{ $order->code }}</a></p>
                                </div>
                                <div class="orderDate">
                                    <p  style="margin-bottom:5px">{{ translate('Date') }}</p>
                                    <p style="margin-bottom:5px">{{ date('d-m-Y', $order->date) }}</p>
                                </div>
                                <div class="orderStatus">
                                    <p  style="margin-bottom:5px">{{ translate('Delivery Status') }}</p>
                                    {{-- <p style="margin-bottom: 5px;color: #fff;background: yellow;padding: 5px 5px;border-radius: 10px;text-align: center;">completed</p> --}}
                                  <p> <span
                                    class="px-1 py-2 {{ $order->delivery_status == 'delivered' ? 'bg-success' : ($order->delivery_status == 'pending' ? 'bg-warning' : ($order->delivery_status == 'on_the_way' ? 'bg-warning' : ($order->delivery_status == 'cancelled' ? 'bg-danger' : 'bg-warning'))) }}
                                    rounded">{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</span></p>
                                </div>
                                <div class="toggle">
                                    <p></p>
                                    <p class="toggle-details" data-toggle="collapse" data-order-id="{{ $order->id }}" aria-expanded="false" aria-controls="details1" style="font-size: 18px; cursor: pointer;"><i  class="fa-solid fa-minus"></i></p>

                                </div>
                            </div>
                            {{-- <td class="toggle-details" data-toggle="collapse" data-target="#details1" aria-expanded="false" aria-controls="details1" style="font-size: 18px; cursor: pointer;">--</td> --}}
                            <div style="text-align: center;margin: 10px;" id="details{{ $order->id }}" class="collapse">
                                <!-- Content to show/hide -->
                                @if ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                                <a href="javascript:void(0)"
                                    class="btn btn-soft-danger btn-icon btn-circle btn-sm hov-svg-white confirm-delete"
                                    data-href="{{ route('purchase_history.destroy', $order->id) }}"
                                    title="{{ translate('Cancel') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="9.202"
                                        height="12" viewBox="0 0 9.202 12">
                                        <path id="Path_28714" data-name="Path 28714"
                                            d="M15.041,7.608l-.193,5.85a1.927,1.927,0,0,1-1.933,1.864H9.243A1.927,1.927,0,0,1,7.31,13.46L7.117,7.608a.483.483,0,0,1,.966-.032l.193,5.851a.966.966,0,0,0,.966.929h3.672a.966.966,0,0,0,.966-.931l.193-5.849a.483.483,0,1,1,.966.032Zm.639-1.947a.483.483,0,0,1-.483.483H6.961a.483.483,0,1,1,0-.966h1.5a.617.617,0,0,0,.615-.555,1.445,1.445,0,0,1,1.442-1.3h1.126a1.445,1.445,0,0,1,1.442,1.3.617.617,0,0,0,.615.555h1.5a.483.483,0,0,1,.483.483ZM9.913,5.178h2.333a1.6,1.6,0,0,1-.123-.456.483.483,0,0,0-.48-.435H10.516a.483.483,0,0,0-.48.435,1.6,1.6,0,0,1-.124.456ZM10.4,12.5V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Zm2.326,0V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Z"
                                            transform="translate(-6.478 -3.322)" fill="#d43533" />
                                    </svg>
                                </a>
                            @endif
                            <a href="{{ route('purchase_history.details', encrypt($order->id)) }}"
                                class="btn btn-soft-info btn-icon btn-circle btn-sm hov-svg-white"
                                title="{{ translate('Order Details') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                    height="10" viewBox="0 0 12 10">
                                    <g id="Group_24807" data-name="Group 24807"
                                        transform="translate(-1339 -422)">
                                        <rect id="Rectangle_18658" data-name="Rectangle 18658"
                                            width="12" height="1"
                                            transform="translate(1339 422)" fill="#3490f3" />
                                        <rect id="Rectangle_18659" data-name="Rectangle 18659"
                                            width="12" height="1"
                                            transform="translate(1339 425)" fill="#3490f3" />
                                        <rect id="Rectangle_18660" data-name="Rectangle 18660"
                                            width="12" height="1"
                                            transform="translate(1339 428)" fill="#3490f3" />
                                        <rect id="Rectangle_18661" data-name="Rectangle 18661"
                                            width="12" height="1"
                                            transform="translate(1339 431)" fill="#3490f3" />
                                    </g>
                                </svg>
                            </a>
                            <a class="btn btn-soft-warning btn-icon btn-circle btn-sm hov-svg-white"
                                href="{{ route('invoice_download', $order->id) }}"
                                title="{{ translate('Download Invoice') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                    height="12.001" viewBox="0 0 12 12.001">
                                    <g id="Group_24807" data-name="Group 24807"
                                        transform="translate(-1341 -424.999)">
                                        <path id="Union_17" data-name="Union 17"
                                            d="M13936.389,851.5l.707-.707,2.355,2.355V846h1v7.1l2.306-2.306.707.707-3.538,3.538Z"
                                            transform="translate(-12592.95 -421)"
                                            fill="#f3af3d" />
                                        <rect id="Rectangle_18661" data-name="Rectangle 18661"
                                            width="12" height="1"
                                            transform="translate(1341 436)" fill="#f3af3d" />
                                    </g>
                                </svg>
                            </a>
                            </div>
                            @endif
                            @endforeach
                            {{-- end test  --}}

                            <!-- Pagination -->
                            <div class="aiz-pagination mt-2">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-none">
            <div id="statistics_info">
                <div class="card" style="border-radius: var(--border-raduis) !important;">
                    <div class="" id="headingstatistics">
                        <h5 class="mb-0">
                            <button
                                class="btn btn-link fs-18 text-dark fw-700 w-100 h-100 d-flex justify-content-between align-items-center"
                                data-toggle="collapse" data-target="#collapsestatistics" aria-expanded="true"
                                aria-controls="collapsestatistics">
                                <span>{{ translate('Your statistics') }}</span>
                                <i style="display:none" id="statistics-plus-icon" class="fa-solid fa-plus"></i>
                                <i id="statistics-minus-icon" class="fa-solid fa-minus"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="collapsestatistics" class="collapse show " aria-labelledby="headingstatistics"
                        data-parent="#statistics_info">
                        <div class="card-body  w-100" style="padding:0 10px">
                            <div class="w-100">
                                <div class="px-2 py-1 border-bottom  mb-3 w-100">
                                    <div class="d-flex align-items-center pb-1 ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                            viewBox="0 0 48 48">
                                            <g id="Group_25000" data-name="Group 25000" transform="translate(-926 -614)">
                                                <rect id="Rectangle_18646" data-name="Rectangle 18646" width="48"
                                                    height="48" rx="24" transform="translate(926 614)"
                                                    fill="rgba(128, 128, 128, 0.5)" />

                                                <g id="Group_24786" data-name="Group 24786"
                                                    transform="translate(701.466 93)">
                                                    <path id="Path_32311" data-name="Path 32311"
                                                        d="M122.052,10V8.55a.727.727,0,1,0-1.455,0V10a2.909,2.909,0,0,0-2.909,2.909v.727A2.909,2.909,0,0,0,120.6,16.55h1.455A1.454,1.454,0,0,1,123.506,18v.727a1.454,1.454,0,0,1-1.455,1.455H120.6a1.454,1.454,0,0,1-1.455-1.455.727.727,0,1,0-1.455,0,2.909,2.909,0,0,0,2.909,2.909V23.1a.727.727,0,1,0,1.455,0V21.641a2.909,2.909,0,0,0,2.909-2.909V18a2.909,2.909,0,0,0-2.909-2.909H120.6a1.454,1.454,0,0,1-1.455-1.455v-.727a1.454,1.454,0,0,1,1.455-1.455h1.455a1.454,1.454,0,0,1,1.455,1.455.727.727,0,0,0,1.455,0A2.909,2.909,0,0,0,122.052,10"
                                                        transform="translate(127.209 529.177)" fill="#808080" />
                                                </g>
                                            </g>
                                        </svg>
                                        <div class="ml-3 d-flex flex-column justify-content-between">
                                            @php
                                                $expenditure = \App\Models\Order::where('user_id', Auth::user()->id)
                                                    ->where('payment_status', 'paid')
                                                    ->sum('grand_total');
                                            @endphp
                                            <span class="fs-20 fw-700 text-dark">{{ single_price($expenditure) }}</span>
                                            <span
                                                class="fs-14 fw-400 text-dark mb-1">{{ translate('Total Expenditure') }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('purchase_history.index') }}"
                                        class="d-flex align-items-center fs-12 text-dark">
                                        {{ translate('View Order History') }}
                                        <i class="las la-angle-right fs-10"></i>
                                    </a>
                                </div>
                                <!-- Cart summary -->
                                <div class="px-2 py-1 d-flex border-bottom mb-3 w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        viewBox="0 0 48 48">
                                        <g id="Group_25000" data-name="Group 25000" transform="translate(-1367 -427)">
                                            <path id="Path_32314" data-name="Path 32314"
                                                d="M24,0A24,24,0,1,1,0,24,24,24,0,0,1,24,0Z"
                                                transform="translate(1367 427)" fill="#d43533" />
                                            <g id="Group_24770" data-name="Group 24770"
                                                transform="translate(1382.999 443)">
                                                <path id="Path_25692" data-name="Path 25692"
                                                    d="M294.507,424.89a2,2,0,1,0,2,2A2,2,0,0,0,294.507,424.89Zm0,3a1,1,0,1,1,1-1A1,1,0,0,1,294.507,427.89Z"
                                                    transform="translate(-289.508 -412.89)" fill="#fff" />
                                                <path id="Path_25693" data-name="Path 25693"
                                                    d="M302.507,424.89a2,2,0,1,0,2,2A2,2,0,0,0,302.507,424.89Zm0,3a1,1,0,1,1,1-1A1,1,0,0,1,302.507,427.89Z"
                                                    transform="translate(-289.508 -412.89)" fill="#fff" />
                                                <g id="LWPOLYLINE">
                                                    <path id="Path_25694" data-name="Path 25694"
                                                        d="M305.43,416.864a1.5,1.5,0,0,0-1.423-1.974h-9a.5.5,0,0,0,0,1h9a.467.467,0,0,1,.129.017.5.5,0,0,1,.354.611l-1.581,6a.5.5,0,0,1-.483.372h-7.462a.5.5,0,0,1-.489-.392l-1.871-8.433a1.5,1.5,0,0,0-1.465-1.175h-1.131a.5.5,0,1,0,0,1h1.043a.5.5,0,0,1,.489.391l1.871,8.434a1.5,1.5,0,0,0,1.465,1.175h7.55a1.5,1.5,0,0,0,1.423-1.026Z"
                                                        transform="translate(-289.508 -412.89)" fill="#fff" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <div class="ml-3 d-flex flex-column justify-content-between">
                                        @php
                                            $user_id = Auth::user()->id;
                                            $cart = \App\Models\Cart::where('user_id', $user_id)->get();
                                        @endphp
                                        <span
                                            class="fs-20 fw-700 mb-1">{{ count($cart) > 0 ? sprintf('%02d', count($cart)) : 0 }}</span>
                                        <span
                                            class="fs-14 fw-400 text-secondary">{{ translate('Products in Cart') }}</span>
                                    </div>
                                </div>

                                <!-- Wishlist summary -->
                                {{-- <div class="px-2 py-1 d-flex border-bottom mb-3 w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        viewBox="0 0 48 48">
                                        <g id="Group_25000" data-name="Group 25000" transform="translate(-1367 -499)">
                                            <path id="Path_32309" data-name="Path 32309"
                                                d="M24,0A24,24,0,1,1,0,24,24,24,0,0,1,24,0Z"
                                                transform="translate(1367 499)" fill="#3490f3" />
                                            <g id="Group_24772" data-name="Group 24772" transform="translate(1383 515)">
                                                <g id="Wooden" transform="translate(0 1)">
                                                    <path id="Path_25676" data-name="Path 25676"
                                                        d="M290.82,413.6a4.5,4.5,0,0,0-6.364,0l-.318.318-.318-.318a4.5,4.5,0,1,0-6.364,6.364l6.046,6.054a.9.9,0,0,0,1.272,0l6.046-6.054A4.5,4.5,0,0,0,290.82,413.6Zm-.707,5.657-5.975,5.984-5.975-5.984a3.5,3.5,0,1,1,4.95-4.95l.389.389a.9.9,0,0,0,1.272,0l.389-.389a3.5,3.5,0,1,1,4.95,4.95Z"
                                                        transform="translate(-276.138 -412.286)" fill="#fff" />
                                                </g>
                                                <rect id="Rectangle_1603" data-name="Rectangle 1603" width="16"
                                                    height="16" transform="translate(0)" fill="none" />
                                            </g>
                                        </g>
                                    </svg>
                                    <div class="ml-3 d-flex flex-column justify-content-between">
                                        @php
                                            $user_id = Auth::user()->id;
                                            $cart = \App\Models\Cart::where('user_id', $user_id)->get();
                                        @endphp
                                        <span
                                            class="fs-20 fw-700 mb-1">{{ count(Auth::user()->wishlists) > 0 ? sprintf('%02d', count(Auth::user()->wishlists)) : 0 }}</span>
                                        <span
                                            class="fs-14 fw-400 text-secondary">{{ translate('Products in Wishlist') }}</span>
                                    </div>
                                </div> --}}

                                <!-- Order summary -->
                                <div class="px-2 py-1 d-flex  mb-3 w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        viewBox="0 0 48 48">
                                        <g id="Group_25000" data-name="Group 25000" transform="translate(-1367 -576)">
                                            <path id="Path_32315" data-name="Path 32315"
                                                d="M24,0A24,24,0,1,1,0,24,24,24,0,0,1,24,0Z"
                                                transform="translate(1367 576)" fill="#85b567" />
                                            <path id="_2e746ddacacf202af82cf4480bae6173"
                                                data-name="2e746ddacacf202af82cf4480bae6173"
                                                d="M11.483,3h-.009a.308.308,0,0,0-.1.026L4.26,6.068A.308.308,0,0,0,4,6.376V15.6a.308.308,0,0,0,.026.127v0l.009.017a.308.308,0,0,0,.157.147l7.116,3.042a.338.338,0,0,0,.382,0L18.8,15.9a.308.308,0,0,0,.189-.243q0-.008,0-.017s0-.01,0-.015,0-.01,0-.015,0,0,0,0V6.376a.308.308,0,0,0-.255-.306L11.632,3.031l-.007,0a.308.308,0,0,0-.05-.017l-.009,0-.022,0h-.062Zm.014.643L13,4.287,6.614,7.02,6.6,7.029,5.088,6.383,11.5,3.643Zm2.29.979,1.829.782L9.108,8.188a.414.414,0,0,0-.186.349v3.291l-.667-1a.308.308,0,0,0-.393-.1l-.786.392V7.493l6.712-2.87ZM16.4,5.738l1.509.645L11.5,9.124,9.99,8.48l6.39-2.733.018-.009ZM4.615,6.85l1.846.789v3.975a.308.308,0,0,0,.445.275l.987-.494,1.064,1.595v0a.308.308,0,0,0,.155.14h0l.027.009a.308.308,0,0,0,.057.012h.036l.036,0,.025,0,.018,0,.015,0a.308.308,0,0,0,.05-.022h0a.308.308,0,0,0,.156-.309V8.955l1.654.707v8.56L4.615,15.411Zm13.765,0v8.56L11.8,18.223V9.662Z"
                                                transform="translate(1379.5 588.5)" fill="#fff" stroke="#fff"
                                                stroke-width="0.25" fill-rule="evenodd" />
                                        </g>
                                    </svg>
                                    <div class="ml-3 d-flex flex-column justify-content-between">
                                        @php
                                            $orders = \App\Models\Order::where('user_id', Auth::user()->id)->get();
                                            $total = 0;
                                            foreach ($orders as $key => $order) {
                                                $total += count($order->orderDetails);
                                            }
                                        @endphp
                                        <span
                                            class="fs-20 fw-700 mb-1">{{ $total > 0 ? sprintf('%02d', $total) : 0 }}</span>
                                        <span
                                            class="fs-14 fw-400 text-secondary">{{ translate('Total Products Ordered') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div id="wishlists_info">
                <div class="card" style="border-radius: var(--border-raduis) !important;">
                    <div class="" id="headingShippingAddressCollapse">
                        <h5 class="mb-0">
                            <button
                                class="btn btn-link fs-18 text-dark fw-700 w-100 h-100 d-flex justify-content-between align-items-center"
                                data-toggle="collapse" data-target="#collapseShippingAddressCollapse"
                                aria-expanded="true" aria-controls="collapseShippingAddressCollapse">
                                <div class="d-flex sm-gap">
                                    <span class="fs-14 fw-700">{{ translate('Shipping Address') }}</span>

                                    <i style="display:none" id="ShippingAddressCollapse-plus-icon"
                                        class="fa-solid fa-angle-down"></i>
                                    <i id="ShippingAddressCollapse-minus-icon" class="fa-solid fa-angle-up"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="shipping_address_collapse">
                        <div class="card" style="border-radius: var(--border-raduis) !important;">

                            <div id="collapseShippingAddressCollapse" class="collapse show"
                                aria-labelledby="headingShippingAddressCollapse" data-parent="#shipping_address_collapse">
                                <div class="card-body  pr-4 ">
                                    <div class="d-flex justify-content-center">
                                        <form method="POST" action="{{ route('addresses.set_default') }}"
                                            class="row w-100">
                                            @csrf
                                            @foreach (\App\Models\Address::where('user_id', Auth()->user()->id)->get() as $address)
                                                <div class="col-md-12 p-0 mx-1 mb-3">
                                                    <label class="aiz-megabox d-block ">
                                                        <input value="{{ $address->id }}" class="online_payment custom-radio-input "
                                                            type="radio" name="address_id" {{ $address->set_default == 1 ? 'checked' : '' }}>
                                                        <span
                                                            class=" d-block aiz-megabox-elem rounded d-flex jusitfy-content-center align-items-center">
                                                            <div
                                                                class=" rounded bg-white w-100 d-flex justify-content-between align-items-center sm-gap align-items-center p-3">
                                                                <div class="d-flex sm-gap">
                                                                    <div class="d-flex flex-column mx-3">
                                                                        <span
                                                                            class="fs-10 text-dark">{{ translate(\App\Models\Country::find($address->country_id)->name) . ' ' . \App\Models\State::find($address->state_id)->name . ' ' . $address->address }}</span>
                                                                    </div>
                                                                </div>
                                                                <span>
                                                                    <span class="fs-10 fw-700 text-dark d-flex sm-gap ">
                                                                        <a href="{{ route('addresses.destroy', $address->id) }}"
                                                                            class="button-not-button text-danger fs-10"><i
                                                                                class="fa-solid fa-trash-can"></i></a>
                                                                        <button
                                                                            onclick="OpenEditAddressModal(event,{{ $address->id }})"
                                                                            class="button-not-button text-warning fs-10">
                                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                                        </button>
                                                                    </span>
                                                                </span>

                                                            </div>
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach
                                            <div class="d-flex col-12 justify-content-end align-items-center p-0 ">
                                                <button
                                                    class=" btn fs-18 w-100 dark-button-style ">{{ translate('Save Changes') }}</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div id="addNewAddressDev"
                                        class="add-new-address-container mt-2 p-3 {{ count(\App\Models\Address::where('user_id', Auth()->user()->id)->get()) < 1 ? 'd-none' : 'd-none' }} ">
                                        <form action="{{ route('addresses.store') }}" class="row gutters-10  "
                                            method="POST">
                                            @csrf
                                            <div class="col-6 p-1 ">
                                                <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
                                                    <strong>{{ translate('Name') }} <span
                                                            style=" color: var(--primary)">*</span></strong>
                                                </label>
                                                </br>
                                                <input name="name"
                                                    value="{{ Auth::check() ? Auth::user()->name : '' }}"
                                                    id="client_name" type="text" required
                                                    class="rounded w-lg-75 checkout-input"
                                                    placeholder="{{ translate('Name') }} *">
                                            </div>
                                            <div class="col-6 p-1 mb-4">
                                                <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
                                                    <strong>{{ translate('State') }} <span
                                                            style=" color: var(--primary)">*</span></strong>
                                                </label>
                                                </br>
                                                <select
                                                    class="form-control checkout-input w-lg-75 rounded aiz-selectpicker"
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
                                            <div class="col-6 p-1">
                                                <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
                                                    <strong>{{ translate('Address') }} <span
                                                            style=" color: var(--primary)">*</span></strong>
                                                </label>
                                                </br>
                                                <input type="text"
                                                    class="form-control w-lg-75 mb-3 rounded checkout-input"
                                                    placeholder="{{ translate('Your Address') }}"id="address"
                                                    name="address"
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
                                                        <select class="form-control aiz-selectpicker rounded  "
                                                            data-live-search="true"
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
                                            <div class="col-6 p-1">
                                                <label class="h5 fs-10 fw-700 mb-2 text-capitalize" for="phone">
                                                    <strong>{{ translate('Phone') }} <span
                                                            style=" color: var(--primary)">*</span></strong>
                                                </label>
                                                </br>
                                                <input name="phone" type="phone" dir="ltr" style="text-align:right;"
                                                    value="{{ Auth::check() ? Auth::user()->phone : '' }}"
                                                    id="phone_number" required class="rounded w-lg-75 checkout-input  "
                                                    placeholder="05xxxxxxxx">
                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="d-flex col-12 justify-content-end align-items-center px-2 ">
                                                <button
                                                    class=" btn fs-18 w-100 dark-button-style ">{{ translate('Add New Address') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class=" add-new-address-container-button p-2 mt-2 rounded">
                                        <a onclick="showAddNewAddress()"
                                            class="text-gray">{{ translate('Add New Address') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div> --}}
    </div>
@endsection

@section('modal')
    <!-- Wallet Recharge Modal -->
    @include('frontend.partials.wallet_modal')
    <script type="text/javascript">
        function show_wallet_modal() {
            $('#wallet_modal').modal('show');
        }
    </script>

    <!-- Address modal Modal -->
    @include('frontend.partials.address_modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
    <!-- Order details modal -->
    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif

    <script type="text/javascript">
        $('#order_details').on('hidden.bs.modal', function() {
            location.reload();
        })

        function OpenEditAddressModal(event,id) {
            $('#UpdateAddressFormDiv').html('');
                event.preventDefault();
            $.ajax({
                type: 'POST',
                url: "{{ route('addresses.updateAddressPopup') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id
                },
                success: function(response) {
                    $('#UpdateAddressFormDiv').html(response);
                    $('#EditAddressModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error([xhr, status, error]);
                    alert("Failed to fetch address popup");
                }
            });
        }
    </script>
@endsection
