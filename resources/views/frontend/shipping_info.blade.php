@extends('frontend.layouts.app')

@section('content')
    @if (get_setting('checkout_banner') != null && get_setting('checkout_banner_small') != null)
        <div class="container p-0 position-relative  d-flex justify-content-around align-items-center">
            <img class="w-100 d-none d-lg-block" src="{{ uploaded_asset(get_setting('checkout_banner')) }}" alt="">
            <img class="w-100 d-bloack d-lg-none" src="{{ uploaded_asset(get_setting('checkout_banner_small')) }}"
                alt="">
        </div>
    @endif

    <!-- Shipping Info -->
    <section class="container">
        <div class="row mb-2">
            <div class="col-12 mt-5">
                <ul class=" px-2 breadcrumb bg-transparent p-0 justify-content-start justify-content-lg-start">
                    <li class="breadcrumb-item">
                        <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="text-reset" href="{{ route('cart') }}">{{ translate('Cart') }}</a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        <a class="text-reset" href="{{ route('orders.track') }}">{{ translate('checkout') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div>
            <div class="d-lg-flex d-none align-items-center  md-gap">
                <span class=" d-lg-block d-none payment_method_circle"></span>
                <span class="d-lg-block d-none payment_method_text fs-19 fw-700 h-5">{{ translate('Cart') }}</span>
                <span class=" d-lg-block d-none payment_method_line"></span>
                <span class="payment_method_circle active"></span>
                <span class="payment_method_text fs-19 fw-700 h-5">{{ translate('Payment Info') }}</span>
                <span class="payment_method_line d-lg-block d-none"></span>
                <span class="payment_method_circle d-lg-block d-none"></span>
                <span class="payment_method_text fs-19 fw-700 h-5 d-lg-block d-none">{{ translate('Place Order') }}</span>
            </div>
            <div style="position: relative" id="deviceTypeContent">

            </div>
        </div>
    </section>

    <a href="https://wa.me/{{ get_setting('whatsapp_number') }}" target="_blank" id="whatsapp_icon">
        <svg width="100%" height="100%" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle class="color-element" cx="19.4395" cy="19.4395" r="19.4395" fill="#49E670"></circle>
            <path
                d="M12.9821 10.1115C12.7029 10.7767 11.5862 11.442 10.7486 11.575C10.1902 11.7081 9.35269 11.8411 6.84003 10.7767C3.48981 9.44628 1.39593 6.25317 1.25634 6.12012C1.11674 5.85403 2.13001e-06 4.39053 2.13001e-06 2.92702C2.13001e-06 1.46351 0.83755 0.665231 1.11673 0.399139C1.39592 0.133046 1.8147 1.01506e-06 2.23348 1.01506e-06C2.37307 1.01506e-06 2.51267 1.01506e-06 2.65226 1.01506e-06C2.93144 1.01506e-06 3.21063 -2.02219e-06 3.35022 0.532183C3.62941 1.19741 4.32736 2.66092 4.32736 2.79397C4.46696 2.92702 4.46696 3.19311 4.32736 3.32616C4.18777 3.59225 4.18777 3.59224 3.90858 3.85834C3.76899 3.99138 3.6294 4.12443 3.48981 4.39052C3.35022 4.52357 3.21063 4.78966 3.35022 5.05576C3.48981 5.32185 4.18777 6.38622 5.16491 7.18449C6.42125 8.24886 7.39839 8.51496 7.81717 8.78105C8.09636 8.91409 8.37554 8.9141 8.65472 8.648C8.93391 8.38191 9.21309 7.98277 9.49228 7.58363C9.77146 7.31754 10.0507 7.1845 10.3298 7.31754C10.609 7.45059 12.2841 8.11582 12.5633 8.38191C12.8425 8.51496 13.1217 8.648 13.1217 8.78105C13.1217 8.78105 13.1217 9.44628 12.9821 10.1115Z"
                transform="translate(12.9597 12.9597)" fill="#FAFAFA"></path>
            <path
                d="M0.196998 23.295L0.131434 23.4862L0.323216 23.4223L5.52771 21.6875C7.4273 22.8471 9.47325 23.4274 11.6637 23.4274C18.134 23.4274 23.4274 18.134 23.4274 11.6637C23.4274 5.19344 18.134 -0.1 11.6637 -0.1C5.19344 -0.1 -0.1 5.19344 -0.1 11.6637C-0.1 13.9996 0.624492 16.3352 1.93021 18.2398L0.196998 23.295ZM5.87658 19.8847L5.84025 19.8665L5.80154 19.8788L2.78138 20.8398L3.73978 17.9646L3.75932 17.906L3.71562 17.8623L3.43104 17.5777C2.27704 15.8437 1.55796 13.8245 1.55796 11.6637C1.55796 6.03288 6.03288 1.55796 11.6637 1.55796C17.2945 1.55796 21.7695 6.03288 21.7695 11.6637C21.7695 17.2945 17.2945 21.7695 11.6637 21.7695C9.64222 21.7695 7.76778 21.1921 6.18227 20.039L6.17557 20.0342L6.16817 20.0305L5.87658 19.8847Z"
                transform="translate(7.7758 7.77582)" fill="white" stroke="white" stroke-width="0.2"></path>
        </svg>
    </a>


    @include('frontend.checkout_one_Step.popups')
@endsection

@section('script')
    <script>
        function SelectNewAddress(addressId) {
            $('#address_inputs').html('');
            $.ajax({
                type: 'POST',
                url: "{{ route('addresses.render_address_input') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'address_id': addressId
                },
                success: function(response) {
                    $('#address_inputs').html(response.html);
                    check_state_to_jana(response.state_id)
                    AIZ.plugins.bootstrapSelect('refresh');

                },
                error: function(xhr, status, error) {
                    console.error([xhr, status, error]);
                    alert("Failed to fetch address inputs");
                }
            });
        }

        function open_add_address_modal() {
            $('#AddNewAddressModal').modal('show');
        }

        function submitNewAddressForm() {
            var formData = $('#add_new_address_form').serialize();
            
            var formDataArray = formData.split('&');
            var formDataObject = {};
            formDataArray.forEach(function (pair) {
                var keyValue = pair.split('=');
                formDataObject[keyValue[0]] = decodeURIComponent(keyValue[1] || '');
            }); 
            if(formDataObject.name === ''){
                AIZ.plugins.notify('danger',
                        '{{ translate('You Need To Enter Your Name') }}');
            }else if(formDataObject.state_id === ''){
                AIZ.plugins.notify('danger',
                        '{{ translate('You Need To Select Your State') }}');
            }else if(formDataObject.address === ''){
                AIZ.plugins.notify('danger',
                        '{{ translate('You Need To Enter Your Address') }}');
            }else if(formDataObject.address === ''){
                AIZ.plugins.notify('danger',
                        '{{ translate('You Need To Enter Your Phone') }}');
            } else {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('addresses.store_address_fron_checkout') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    success: function(response) {
                        $('#address_inputs').html(response.address_inputs);
                        $('#userAddresses').html(response.user_addresses);
                        $('#AddNewAddressModal').modal('hide');
                        AIZ.plugins.bootstrapSelect('refresh');
                        $('input[name="address_id"][value="' + response.address_id + '"]').prop('checked', true);
                        SelectNewAddress(response.address_id)
                    },
                    error: function(xhr, status, error) {
                        console.error([xhr, status, error]);
                        AIZ.plugins.notify('danger',
                            '{{ translate('Something went wrong') }}');
                    }
                });
            }

        }

        function OpenEditAddressFromCheckout(event, id) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: "{{ route('addresses.render_address_to_checkout') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id
                },
                success: function(response) {
                    $('#UpdateAddressFromCheckoutFormDiv').html(response.html);
                    $('#EditAddressFromCheckoutModal').modal('show');
                    AIZ.plugins.bootstrapSelect('refresh'); 
                },
                error: function(xhr, status, error) {
                    console.error([xhr, status, error]);
                    AIZ.plugins.notify('danger',
                        '{{ translate('Something went wrong') }}');
                }
            });

        } 
        function update_address_form_checkout(event) {
             event.preventDefault();
            var formData = $('#update_address_form_checkout').serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('addresses.updateFromCheckout') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(response) {
                     $('#userAddresses').html(response.user_addresses);
                    $('#EditAddressFromCheckoutModal').modal('hide');
                    AIZ.plugins.bootstrapSelect('refresh');


                },
                error: function(xhr, status, error) {
                    console.error([xhr, status, error]);
                    AIZ.plugins.notify('danger',
                        '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
    <script>
        var userAgent = navigator.userAgent.toLowerCase();
        if (userAgent.match(/mobile/i) || userAgent.match(/tablet/i)) {
            $.ajax({
                url: '{{ route('checkout.return_mobile_checkout') }}',
                method: 'GET',
                success: function(response) {
                    $('#deviceTypeContent').html(response.view)
                    makeThisWizer();
                    $('.cart_summary_to_read_more').readall({
                        showheight: 371,
                        showrows: null,
                        animationspeed: 200,
                        btnTextShowmore: '{{ translate('View More') }}',
                        btnTextShowless: '{{ translate('View Less') }}',
                        btnClassShowmore: 'readall-button',
                        btnClassShowless: 'readall-button'
                    });
                    AIZ.plugins.bootstrapSelect('refresh');
                },
                error: function(xhr, status, error) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('Something went wrong') }}');
                }
            });
        } else {
            $.ajax({
                url: '{{ route('checkout.return_desktop_checkout') }}',
                method: 'GET',
                success: function(response) {

                    $('#deviceTypeContent').html(response.view)
                    AIZ.plugins.bootstrapSelect('refresh');
                },
                error: function(xhr, status, error) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('Something went wrong') }}');
                }
            });
        }


        function makeThisWizer() {
            $("#steps_checkout").steps({
                headerTag: "StepHeader",
                bodyTag: "StepContent",
                stepsOrientation: "vertical",
                transitionEffect: "slide"
            });
        }
        $(document).on('click', '.open-this-step', function() {
            var stepIndex = $(this).data('step');
            var stepHeaderID = "#steps_checkout-t-" + stepIndex;
            $(stepHeaderID).click();
        });
        $(document).on('click', '.next-step-btn', function() {
            var stepName = $(this).data('step-name');
            // alert()
            if (stepName == 'shipping_info') {
                if ($('#client_name').val() != '' && $('#phone_number').val() != '' &&
                    $('#select_state').val() != '' && $('#client_email').val() != '' && $('#address').val() != '') {
                    $("#steps_checkout").steps('next');
                } else {
                    AIZ.plugins.notify('warning',
                        '{{ translate('Please Verify All Info.') }}');
                }
            }
            if (stepName == 'company_info') {
                $("#steps_checkout").steps('next');
            }

        });

        document.getElementById('checkout-form').addEventListener('change', function(event) {
            var selectedPaymentMethod = document.querySelector('input[name="payment_option"]:checked');
            if (selectedPaymentMethod && selectedPaymentMethod.value === 'cash_on_delivery') {
                $('#cash_on_delivery_tax').show();
                var total = parseFloat($('#order_total').data('value')) + 25
                $('#order_total').html(total + 'ر.س')
            } else {
                $('#cash_on_delivery_tax').hide();
                var total = parseFloat($('#order_total').data('value')) - 25;
                $('#order_total').html(total + 'ر.س')
            }
        });

        $(document).ready(function() {
            var selectedPaymentMethod = document.querySelector('input[name="payment_option"]:checked');
            if (selectedPaymentMethod && selectedPaymentMethod.value === 'cash_on_delivery') {
                $('#cash_on_delivery_tax').show();
                var total = parseFloat($('#order_total').data('value')) + 25
                $('#order_total').html(total + 'ر.س')
            } else {
                $('#cash_on_delivery_tax').hide();
                var total = parseFloat($('#order_total').data('value')) - 25;
                $('#order_total').html(total + 'ر.س')
            }
        });
    </script>
    <script type="text/javascript">
        function display_option(key) {}

        function show_pickup_point(el, type) {
            var value = $(el).val();
            var target = $(el).data('target');
            if (value == 'home_delivery' || value == 'carrier') {
                if (!$(target).hasClass('d-none')) {
                    $(target).addClass('d-none');
                }
                $('.carrier_id_' + type).removeClass('d-none');
            } else {
                $(target).removeClass('d-none');
                $('.carrier_id_' + type).addClass('d-none');
            }
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            $(".online_payment").click(function() {
                $('#manual_payment_description').parent().addClass('d-none');
            });
            toggleManualPaymentData($('input[name=payment_option]:checked').data('id'));
            $('#checkout-form').submit();
 
        });
        var minimum_order_amount_check = {{ get_setting('minimum_order_amount_check') == 1 ? 1 : 0 }};
        var minimum_order_amount =
            {{ get_setting('minimum_order_amount_check') == 1 ? get_setting('minimum_order_amount') : 0 }};

        function use_wallet() {
            $('input[name=payment_option]').val('wallet');
            if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                AIZ.plugins.notify('danger',
                    '{{ translate('You order amount is less then the minimum order amount') }}');
            } else {
                
                 $('#checkout-form').submit();
            }
             
        }

        function submitOrder(el) { 
            if ($('#phone_number').val() == '' || $('#client_name').val() == '') {
                AIZ.plugins.notify('danger', '{{ translate('Please Insert Your Name And Phone Number ') }}');
            } else {
                $(el).prop('disabled', true); 
                    if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                        AIZ.plugins.notify('danger',
                            '{{ translate('You order amount is less then the minimum order amount') }}');
                    } else {
                        var offline_payment_active = '{{ addon_is_activated('offline_payment') }}';
                        if (offline_payment_active == 'true' && $('.offline_payment_option').is(":checked") && $('#trx_id')
                            .val() == '') {
                            AIZ.plugins.notify('danger', '{{ translate('You need to put Transaction id') }}');
                            $(el).prop('disabled', false);
                        } else { 
                            if ($('input[name="address_id"]:checked').length > 0) {
                                 $('#checkout-form').submit();
                               
                            } else {
                                AIZ.plugins.notify('danger',
                                    '{{ translate('You need to select address') }}');
                                     $(el).prop('disabled', false); 
                              }  
                        }
                    }
            }
        }


        function toggleManualPaymentData(id) {
            if (typeof id != 'undefined') {
                $('#manual_payment_description').parent().removeClass('d-none');
                $('#manual_payment_description').html($('#manual_payment_info_' + id).html());
            }
        }

        $(document).on("click", "#coupon-apply", function() {
            var code = $('input[name="code"]').val();
            var url = '{{ route('checkout.apply_coupon_code_checkout', ':code') }}';
            url = url.replace(':code', code);

            var owner_id = $('input[name="owner_id"]').val();
            var data = {
                'code': code,
                'owner_id': owner_id
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "GET",
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                     AIZ.plugins.notify(data.response_message.response, data.response_message.message);
                    $("#cart_summary").html(data.html);
                    $("#cart_summary_mobile").html(data.htmlMobile);
                }
            })
        });

        $(document).on("click", "#coupon-remove", function() {
            // var data = new FormData($('#remove-coupon-form')[0]);
            var code = $('input[name="code"]').val();
            var url = '{{ route('checkout.remove_coupon_code_checkout', ':code') }}';
            url = url.replace(':code', code);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "GET",
                url: url,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                    $("#cart_summary").html(data.html);
                    $("#cart_summary_mobile").html(data.htmlMobile);
                }
            })
        })
    </script>
    <script type="text/javascript">
        $(document).on('change', 'input[name="payment_option"]', function() {
            if ($(this).val() === 'cash_on_delivery' && $(this).is(':checked')) {
                var total = parseFloat($('#order_total').data('value')) + 25
                $('#order_total').html(total + 'ر.س')
                $('#cash_on_delivery_tax').show();
            } else {
                var total = parseFloat($('#order_total').data('value')) - 25;
                $('#order_total').html(total + 'ر.س')
                $('#cash_on_delivery_tax').hide();
            }
        });

        function add_new_address() {
            $('#new-address-modal').modal('show');
        }

        function edit_address(address) {
            var url = '{{ route('addresses.edit', ':id') }}';
            url = url.replace(':id', address);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#edit_modal_body').html(response.html);
                    $('#edit-address-modal').modal('show');
                    AIZ.plugins.bootstrapSelect('refresh');

                    @if (get_setting('google_map') == 1)
                        var lat = -33.8688;
                        var long = 151.2195;

                        if (response.data.address_data.latitude && response.data.address_data.longitude) {
                            lat = parseFloat(response.data.address_data.latitude);
                            long = parseFloat(response.data.address_data.longitude);
                        }

                        initialize(lat, long, 'edit_');
                    @endif
                }
            });
        }

        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-state') }}",
                type: 'POST',
                data: {
                    country_id: country_id
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }
        $(document).on('change', '#select_state', function() {
            var state_id = $(this).val();
            check_state_to_jana(state_id)
            get_city(state_id);
        });
       $(document).on('change', 'input[name="shipping_company"]', function() {
                if ($(this).val() === '3') {
                    $('#cod_div').hide();
                    $('#cod_div_input').prop('checked', false);
                    $('#tamara_input').prop('checked', true);
                } else {
                    alert
                    $('#cod_div').show();
                    $('#cod_div_input').prop('checked', true);
                    $('#tamara_input').prop('checked', false);
                }
        });
        function check_state_to_jana(state_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('check-state-to-jana') }}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function(response) {
                    if (response.founded == true) {
                         var total = parseFloat($('#order_total').data('value')) + 25
                        $('#order_total').html(total + 'ر.س')
                        $('#cash_on_delivery_tax').show();
                        $('#JANA_div_input').prop('checked', true);
                        $('#JANA_div_input').prop('disabled', false);
                        $('#JANA_div').show();
                        $('#cod_div').show();
 
                        $('#cod_div_input').prop('checked', true);
                        $('#cod_div_input').prop('disabled', false);
                        $('#tamara_input').prop('checked', false);
                    } else {
                        var total = parseFloat($('#order_total').data('value')) - 25;
                        $('#order_total').html(total + 'ر.س')
                        $('#cash_on_delivery_tax').hide(); 
                        $('#JANA_div_input').prop('checked', false);
                        $('#JANA_div_input').prop('disabled', true);
                        $('#JANA_div').hide();
                        $('#cod_div').hide();
 
                        $('#AyMakan_div_input').prop('checked', true);
                        $('#cod_div_input').prop('checked', false);
                        $('#cod_div_input').prop('disabled', true);
                        $('#tamara_input').prop('checked', true);
                    }
                }
            });
        }


        function get_city(state_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-city') }}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="city_id"]').html(obj);
                        $('[name="city_id"]').selectpicker('refresh');
                    }
                }
            });
        }


        $('#select_citiy').on('change', function() {
            var state_id = $('select[name="state_id"]').val();
            var city_id = $('select[name="city_id"]').val();
            var country_id = $('input[name="country_id"]').val();
            var loader = `
            <div class="c-preloader text-center p-3" style="margin-top:20%">
                <i class="las la-spinner la-spin la-3x"></i>
            </div>
            `;
            $('#cart_summary').html(loader);
            $('#cart_summary_mobile').html(loader);
            $.ajax({
                url: "{{ route('checkout.store_shipping_infostore') }}",
                type: 'POST',
                datatype: 'json',
                cache: false,
                data: {
                    '_token': "{{ csrf_token() }}",
                    'state_id': state_id,
                    'city_id': city_id,
                    'country_id': country_id
                },
                success: function(data) {
                    $("#cart_summary").html(data.html);
                    $("#cart_summary_mobile").html(data.htmlMobile);
                }
            })
        });
    </script>
    @if (get_setting('paymob_payment') == 1)
        <script>
            $('input[name="paymob_option"][value="paymob"]').on('click', showPaymobOptions);
            // Functions using jQuery
            function showPaymobOptions() {
                $('#paymobOptionsModal').modal('show');
            }

            function closePaymobOptionsModal() {
                $('#paymobOptionsModal').modal('hide');
            }

            function selectPaymobOption(option) {
                $('#paymobOptionInput').val(option);
                closePaymobOptionsModal();
            }
        </script>
    @endif
    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif
    @if (get_setting('facebook_pixel') == 1)
        @php

            $product_ids = $carts->pluck('product_id');
            $products_name = App\Models\Product::whereIn('id', $product_ids)->pluck('name');

        @endphp


        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s) 
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');

            var currentDay = new Date().getDate();
            var currentMonth = new Date().getMonth() + 1; // Months are zero-based, so we add 1

            var timeZone = 'Africa/Cairo'; // Egypt timezone
            var formattedTime = new Date().toLocaleTimeString('en-US', {
                timeZone,
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            var total = @json($total);
            var products_name = @json($products_name);
            var variations = @json($carts->pluck('variation'));
            var ids = @json($carts->pluck('id'));
            var count = @json($carts->count());

            var data = {
                event_day: new Date().getDate(),
                event_month: new Date().getMonth() + 1,
                page_title: document.title,
                page_url: window.location.href,
                total_price: total,
                items_variations: variations,
                items_names: products_name,
                items_ids: ids,
                page_title: document.title,
                page_url: window.location.href,
                items_num: count,
                content_type: 'product',
                event_time: formattedTime
            };
            fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}');
            fbq('track', 'InitiateCheckout', data, {
                event_id: "{{ $pixel_event_id }}"
            });
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}&ev=PageView&noscript=1" />
        </noscript>
    @endif
                 <!-- Snap Pixel Code -->
        <script type='text/javascript'>
        (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
        {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
        a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
        r.src=n;var u=t.getElementsByTagName(s)[0];
        u.parentNode.insertBefore(r,u);})(window,document,
        'https://sc-static.net/scevent.min.js');
        
        // Initialize Snap Pixel
        snaptr('init', '{{ env('SNAPCHAT_PIXEL_ID') }}');
        var currentDay = new Date().getDate();
            var currentMonth = new Date().getMonth() + 1; // Months are zero-based, so we add 1

            var timeZone = 'Africa/Cairo'; // Egypt timezone
            var formattedTime = new Date().toLocaleTimeString('en-US', {
                timeZone,
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            var total = @json($total);
            var products_name = @json($products_name);
            var variations = @json($carts->pluck('variation'));
            var ids = @json($carts->pluck('id'));
            var count = @json($carts->count());
            var email = '{{Auth::user()->email ??  'no-email'}}';
            var phone = '{{Auth::user()->phone ?? 'no-phone'}}';
            if(phone != 'no-phone' && email != 'no-email') {
                data = {
                user_email: email,
                user_phone_number: phone,
                item_ids: ids,
                currency: 'SAR',  
                number_items : count,
                price : total , 
                client_dedup_id:'{{ $pixel_event_id }}', 
                }
            }else if (phone != 'no-phone' ){
                data = { 
                    user_phone_number: phone,
                    item_ids: ids,
                    currency: 'SAR',  
                    number_items : count,
                    price : total , 
                    client_dedup_id:'{{ $pixel_event_id }}', 
                }
            }else if (email != 'no-email'){
                data = {
                user_email: email, 
                item_ids: ids,
                currency: 'SAR',  
                number_items : count,
                price : total , 
                client_dedup_id:'{{ $pixel_event_id }}', 
                }
            }else{
                 data = {  
                item_ids: ids,
                currency: 'SAR',  
                number_items : count,
                price : total , 
                client_dedup_id:'{{ $pixel_event_id }}', 
                }
            }
             
         snaptr('track', 'StartCheckout', data);
        </script>
        
        
        
        <script>
            var contentsArray = [];
            var total = 0;

            carts.forEach(function(cart) {
                total += (cart.price + cart.tax) - cart.discount;
                var contentObject = {
                    "content_id": cart.product_id,
                    "content_type": "product",
                    "content_name": cart.product_name,
                    "quantity": cart.quantity,
                    "price": (cart.price + cart.tax) - cart.discount,
                    "brand": "Shal Store"
                };
                contentsArray.push(contentObject);
            });
            ttq.track('InitiateCheckout', {
                "contents": contentsArray,
                "value": total,
                "currency": "SAR",
            });
        </script>
@endsection
