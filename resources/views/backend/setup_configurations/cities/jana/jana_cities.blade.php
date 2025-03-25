@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h1 class="h3">{{ translate('All Aymakan cities') }}</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <form class="" action="" method="GET">
                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('Cities') }}</h5>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="sort_city" name="sort_city"
                                @isset($sort_city) value="{{ $sort_city }}" @endisset
                                placeholder="{{ translate('Type city name & Enter') }}">
                        </div>
                        <div class="col-md-4">
                            <select class="form-control aiz-selectpicker" data-live-search="true" id="sort_state"
                                name="sort_state">
                                <option value="">{{ translate('Select State') }}</option>
                                {{-- @foreach ($states as $state)
                                    <option value="{{ $state->id }}" @if ($sort_state == $state->id) selected @endif
                                        {{ $sort_state }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach --}}
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary" type="submit">{{ translate('Filter') }}</button>
                        </div>
                    </div>
                </form>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th data-breakpoints="lg">#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('city') }}</th>
                                <th data-breakpoints="lg" class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cities as $key => $city)
                                <tr>
                                    <td>{{ $key + 1 + ($cities->currentPage() - 1) * $cities->perPage() }}</td>
                                    <td>{{ $city->name_ar }}</td>
                                    <td>{{ $city->city_id }}</td>
                                    <td class="text-right">
                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('delete_jana_city', $city->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $cities->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Add New City To Jana') }}</h5>
                </div>
                <div class="card-body">
                    <form class="" action="{{ route('save_jana_city') }}" method="POST">
                        @csrf
                        <div class="card-header row gutters-5"> 
                            <div class="col-12 mb-2">
                                <input required type="text" class="form-control" name="name_ar"
                                    placeholder="{{ translate('Arabic Name') }}">
                            </div>
                            <div class="col-12 mb-2">
                                <input required type="text" class="form-control" name="name_en"
                                    placeholder="{{ translate('Arabic Name') }}">
                            </div>
                            <div class="col-12 mb-2">
                                <select required class="form-control aiz-selectpicker" data-live-search="true"
                                    name="state_id">
                                    <option value="">{{ translate('Select State') }}</option>
                                    @foreach (\App\Models\State::all() as $state)
                                        <option value="{{ $state->id }}">
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary" type="submit">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Attach Cities To State') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th data-breakpoints="lg">#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('city') }}</th>
                                <th data-breakpoints="lg" class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Models\State::all() as $state)
                                <tr>
                                    <td>{{ $key + 1 + ($cities->currentPage() - 1) * $cities->perPage() }}</td>
                                    <td>{{ $state->name }}</td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('jana_city_attach.attach', $state->id) }}"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
