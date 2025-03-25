@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">{{ translate('Attach City To State') }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('aymakan_city_attach.store') }}" method="POST">
                @csrf
                <div class="fs-18 w-100 text-center fw-bold mb-5"> {{ $state->name }} </div>
                <input type="text" name="state_id" readonly hidden value="{{ $state->id }}">
                {{-- @dd($state_cities) --}}
                <select class="mb-5 form-control aiz-selectpicker" multiple name="cities[]" data-live-search="true" required>
                    @foreach ($aymakan_cities as $city)
                        <option {{ in_array($city->id, $state_cities) ? 'selected' : '' }} value="{{ $city->id }}">
                            {{ $city->name_ar }}
                        </option>
                    @endforeach
                </select>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
