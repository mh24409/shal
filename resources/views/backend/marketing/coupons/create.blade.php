@extends('backend.layouts.app')

@section('content')

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Coupon Information Adding') }}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('coupon.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mt-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label" for="name">{{ translate('Coupon Type') }}</label>
                        <div class="col-lg-9">
                            <select name="type" id="coupon_type" class="form-control aiz-selectpicker"
                                onchange="coupon_form()" required>
                                <option value="">{{ translate('Select One') }}</option>
                                <option value="product_base" @if (old('type') == 'product_base') selected @endif>
                                    {{ translate('For Products') }}</option>
                                <option value="cart_base" @if (old('type') == 'cart_base') selected @endif>
                                    {{ translate('For Total Orders') }}</option>
                            </select>
                        </div>
                    </div>

                    <div id="coupon_form">

                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label" for="code">{{ translate('Description') }}</label>
                        <div class="col-lg-9">
                            <input type="text" placeholder="{{ translate('Description') }}" id="description"
                                name="description" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="start_date">{{ translate('is limited uses ?') }}</label>
                        <div class="col-sm-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="is_limited" id="is_limited" value="1"
                                    {{ old('is_limited') == 1 ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row" id="uses_number">
                        <label class="col-lg-3 control-label" for="code">{{ translate('Uses Limit') }}</label>
                        <div class="col-lg-9">
                            <input type="number" placeholder="{{ translate('Uses Limit') }}" id="uses_limit"
                                name="uses_limit" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label"
                            for="start_date">{{ translate('users limitation ?') }}</label>
                        <div class="col-sm-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="is_user_limit" id="is_user_limit" value="1"
                                    {{ old('is_user_limit') == 1 ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row" id="users_limit_number">
                        <label class="col-lg-3 control-label" for="code">{{ translate('Users Limit number') }}</label>
                        <div class="col-lg-9">
                            <input type="number" placeholder="{{ translate('Users Limit number') }}" id="users_limit"
                                name="users_limit" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label"
                            for="start_date">{{ translate('Life Time Coupon ?') }}</label>
                        <div class="col-sm-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="lifetime" id="lifetime" value="1"
                                    {{ old('lifetime') == 1 ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        function coupon_form() {
            var coupon_type = $('#coupon_type').val();
            $.post('{{ route('coupon.get_coupon_form') }}', {
                _token: '{{ csrf_token() }}',
                coupon_type: coupon_type
            }, function(data) {
                $('#coupon_form').html(data);
            });
        }

        @if ($errors->any())
            coupon_form();
        @endif
        $('#users_limit_number').hide();
        $('#uses_number').hide();
        $('#is_user_limit').change(function() {
            if ($(this).is(':checked')) {
                $('#users_limit_number').show();
            } else {
                $('#users_limit_number').hide();
            }
        });
        $('#is_limited').change(function() {
            if ($(this).is(':checked')) {
                $('#uses_number').show();
            } else {
                $('#uses_number').hide();
            }
        });
        $('#lifetime').change(function() {
            if ($(this).is(':checked')) {
                $('#date_range').hide();

            } else {
                $('#date_range').show();
            }
        });
    </script>
@endsection
