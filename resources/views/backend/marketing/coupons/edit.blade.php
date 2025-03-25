@extends('backend.layouts.app')

@section('content')

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6">{{ translate('Coupon Information Update') }}</h3>
            </div>
            <form action="{{ route('coupon.update', $coupon->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="card-body">
                    <input type="hidden" name="id" value="{{ $coupon->id }}" id="id">
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
                                @if ($coupon->type == 'product_base')
                                    )
                                    <option value="product_base" selected>{{ translate('For Products') }}</option>
                                @elseif ($coupon->type == 'cart_base')
                                    <option value="cart_base">{{ translate('For Total Orders') }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div id="coupon_form">

                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label" for="code">{{ translate('Description') }}</label>
                        <div class="col-lg-9">
                            <input type="text" placeholder="{{ translate('Description') }}" id="description"
                                name="description" value="{{ $coupon->description }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="start_date">{{ translate('is limited ?') }}</label>
                        <div class="col-sm-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="is_limited" id="is_limited" value="1"
                                    {{ $coupon->is_limited == '1' ? 'checked' : '' }}><span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row" id="uses_number">
                        <label class="col-lg-3 control-label" for="code">{{ translate('Uses Limit') }}</label>
                        <div class="col-lg-9">
                            <input type="number" placeholder="{{ translate('Uses Limit') }}"
                                value="{{ $coupon->uses_limit }}" id="uses_limit" name="uses_limit" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label"
                            for="start_date">{{ translate('users limitation ?') }}</label>
                        <div class="col-sm-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="is_user_limit" id="is_user_limit" value="1"
                                    {{ $coupon->is_user_limit == 1 ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row" id="users_limit_number">
                        <label class="col-lg-3 control-label" for="code">{{ translate('Users Limit number') }}</label>
                        <div class="col-lg-9">
                            <input type="number" placeholder="{{ translate('Users Limit number') }}" id="users_limit"
                                name="users_limit" value="{{ $coupon->users_limit }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label"
                            for="start_date">{{ translate('Life Time Coupon ?') }}</label>
                        <div class="col-sm-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="lifetime" id="lifetime" value="1"
                                    {{ $coupon->lifetime == '1' ? 'checked' : '' }}><span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection
@section('script')
    <script type="text/javascript">
        function coupon_form() {
            var coupon_type = $('#coupon_type').val();
            var id = $('#id').val();
            $.post('{{ route('coupon.get_coupon_form_edit') }}', {
                _token: '{{ csrf_token() }}',
                coupon_type: coupon_type,
                id: id
            }, function(data) {
                $('#coupon_form').html(data);
            });
        }

        $(document).ready(function() {
            coupon_form();

        });
        if ($('#is_user_limit').is(':checked')) {
            $('#users_limit_number').show();
        } else {
            $('#users_limit_number').hide();
        }
        $('#is_user_limit').change(function() {
            if ($(this).is(':checked')) {
                $('#users_limit_number').show();
            } else {
                $('#users_limit_number').hide();
            }
        });
        if ($('#is_limited').is(':checked')) {
            $('#uses_number').show();
        } else {
            $('#uses_number').hide();
        }
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
