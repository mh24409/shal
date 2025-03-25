@foreach (\App\Models\Address::where('user_id', Auth()->user()->id)->get() as $address)
    <div class="col-md-12 p-0 mx-1 mb-3">
        <label class="aiz-megabox d-block ">
            <input value="{{ $address->id }}" onchange="SelectNewAddress({{ $address->id }})"
                class="online_payment custom-radio-input " type="radio" name="address_id"
                {{ $address->set_default == 1 ? 'checked' : '' }}>
            <span class=" d-block aiz-megabox-elem rounded d-flex jusitfy-content-center align-items-center">
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
                                class="button-not-button text-danger fs-10"><i class="fa-solid fa-trash-can"></i></a>
                            <a href="#" onclick="OpenEditAddressFromCheckout(event, {{ $address->id }})"
                                class="button-not-button text-warning fs-10">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                        </span>
                    </span>

                </div>
            </span>
        </label>
    </div>
@endforeach
