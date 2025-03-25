<div class="col-6 p-1 ">
    <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
        <strong>{{ translate('Name') }} <span style=" color: var(--primary)">*</span></strong>
    </label>
    </br>
    
<input name="name" value="{{ isset($selected_address) ? $selected_address->name : Auth::user()->name }}" id="client_name" type="text" required class="rounded w-lg-75 checkout-input" placeholder="{{ translate('Name') }} *">
        
</div>
<div class="col-6 p-1 mb-4">
    <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
        <strong>{{ translate('State') }} <span style=" color: var(--primary)">*</span></strong>
    </label>
    </br>
    <select class="form-control checkout-input w-lg-75 rounded aiz-selectpicker" name="state_id" required
        id="select_state" data-live-search="true" data-placeholder="{{ translate('Select your state') }}"
        aria-label="{{ translate('Select your state') }}">
        <option value="">
            {{ translate('Select your state') }}
        </option>
        @foreach (\App\Models\State::where('country_id', 64)->get() as $key => $state)
            <option value="{{ $state->id }}" {{ isset($selected_address) && $selected_address->state_id == $state->id ? 'selected' : '' }}>
                {{ $state->name }}</option>
        @endforeach
    </select>
    @error('state_id')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
<div class="col-12 p-1">
    <label for="name" class="h5 fs-10 fw-700 mb-2 text-capitalize">
        <strong>{{ translate('Address') }} <span style=" color: var(--primary)">*</span></strong>
    </label>
    </br>
    <input type="text" class="form-control w-lg-75 mb-3 rounded checkout-input"
        placeholder="{{ translate('Your Address') }}"id="address" name="address"
        value="{{ isset($selected_address) ? $selected_address->address : '' }}">
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
<div class="col-12 p-1">
    <label class="h5 fs-10 fw-700 mb-2 text-capitalize" for="phone">
        <strong>{{ translate('Phone') }} <span style=" color: var(--primary)">*</span></strong>
    </label>
    </br>
    <input name="phone" type="phone"  id="phone-code" dir="ltr" style="text-align:right;" value="{{ isset($selected_address) ? $selected_address->phone : Auth::user()->phone }}" id="phone_number"
        class="rounded w-lg-75 checkout-input  " placeholder="5612 345 67">
    @error('phone')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
