@extends('backend.layouts.app')
@section('css')
<style>
    .shipping-information-label {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .shipping-information-row {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .shipping-information-label-col {
        flex: 1;
        margin-right: 10px;
    }

    .shipping-information-input-col {
        flex: 2;
    }
</style>
@endsection
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{ translate('Add New Order') }}</h5>
</div>
<div class="">
    <!-- Error Meassages -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form class="form form-horizontal mar-top" action="{{ route('add_order_by_admin') }}" method="POST"
        enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-12">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">

                        <div class="form-group ">
                            <div class="order-slider-target">

                                <div class="row gutters-5">
                                    <div class="col-md-5">
                                        <select class="form-control aiz-selectpicker" name="product_id" id="product_id"
                                            onchange="getProductInfo(this.value ,'1')" data-live-search="true" required>
                                            @foreach ($products as $product)
                                            @if ($product->stocks->count() ==1)
                                            @if($product->stocks->first()->variant == null)

                                            <option value="P_{{ $product->id }}">
                                                {{ $product->getTranslation('name') }} ( {{translate('Qty')}} {{
                                                $product->stocks->first()->qty
                                                }})
                                            </option>
                                            @endif
                                            @endif
                                            @if ($product->stocks->count() > 0)
                                            @foreach ($product->stocks as $variant)
                                            @if ($variant->variant != null)
                                            <option value="V_{{ $variant->id }}">

                                                {{ $variant->variant }} ({{ $product->getTranslation('name') }}) (
                                                {{translate('Qty')}} {{$variant->qty}})

                                            </option>
                                            @endif
                                            @endforeach
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md">
                                        <select class="form-control aiz-selectpicker" name="product_info"
                                            id="product_info_1" data-live-search="true">
                                            <option value="0">0 {{translate('Unit Price')}} </option>
                                            <option value="1">
                                                1 ({{ translate('Wholesale Price') }})
                                            </option>
                                            <option value="2">
                                                2 ({{ translate('Wholesale Price Variant') }})
                                            </option>

                                        </select>
                                    </div>
                                    <div class="col-md">

                                        <input type="text" class="form-control" name="product_quantity" required
                                            id="product_quantity_1" placeholder="{{ translate('Quantity') }}">

                                        </select>
                                    </div>

                                    <div class="col-md-auto">
                                        <div class="form-group">
                                            <button type="button"
                                                class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                data-toggle="remove-parent" data-parent=".row">
                                                <i class="las la-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn-primary p-2 " onclick="addNewProduct()">{{translate('Add New
                            Product')}}</a>
                        </div>

                        <div >
                            <div class="form-group row ">
                                <label class="col-md-3 col-from-label">{{ translate('Order For') }} <span
                                    class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select class="form-control aiz-selectpicker" name="customer_id" id="customer_id"
                                        onchange="customer_type(this.value);">
                                        <option value="">{{ translate('Select Customer') }}</option>
                                        <option value="0">{{ translate('WalkIn Customer') }}</option>
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" data-customer-data = "{{$customer}}">
                                            {{ $customer->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row d-none" id="customer_name">
                                <label class="col-md-3 col-from-label">{{ translate('Client Name') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="client_name"
                                        placeholder="{{ translate('Client Name') }}">
                                </div>
                            </div>
                            <div class="form-group row" id="customer_phone">
                                <label class="col-md-3 col-from-label">{{ translate('Phone') }}</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="phone" id="customer_phone"
                                        placeholder="{{ translate('Phone') }}">
                                </div>
                            </div>
                            <div class="form-group row d-none" id="customer_email">
                                <label class="col-md-3 col-from-label">{{ translate('Email') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="email"
                                        placeholder="{{ translate('Email') }}">
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Shipping Method') }}</label>
                            <div class="col-md-9">

                                <select class="form-control aiz-selectpicker" name="shipping_method"
                                    onchange="changeShippingMethod(this.value)">
                                    <option value="home_delivery">
                                        {{translate('Home Delivery')}}
                                    </option>
                                    <option value="pick_up_from_store">
                                        {{translate('Pick Up From Store')}}
                                    </option>
                                    {{-- <option value="shipping_company">
                                        {{translate('Other Shipping Company')}}
                                    </option> --}}
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" id="state_div">
                            <label class="col-md-3 col-from-label">{{ translate('Shipping State') }}</label>
                            <div class="col-md-9">
                                <select class="form-control aiz-selectpicker" name="state_id" id="state_id"
                                    onchange="changeState(this.value)">
                                    <option value="">{{ translate('Select State') }}</option>
                                    @foreach ($states as $state)
                                    <option value="{{ $state->id }}">
                                        {{ $state->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="city_div">
                            <label class="col-md-3 col-from-label">{{ translate('Shipping City') }}</label>
                            <div class="col-md-9">
                                <select class="form-control aiz-selectpicker" name="city_id" id="city_id">
                                    <option value="">{{ translate('Select City') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="address_div">
                            <label class="col-md-3 col-from-label">{{ translate('Shipping Address') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="address"
                                    placeholder="{{ translate('Address') }}">
                            </div>
                        </div>
                        {{-- <div class="form-group row" id="shipping_information_div" style="display:none;">
                            <label class="col-md-3 col-from-label shipping-information-label">{{ translate('Shipping Information') }}</label>
                            <div class="col-md-9">
                                <div class="shipping-information-row mx-2">
                                    <div class="shipping-information-input-col">
                                        <input type="text" class="form-control" name="shipping_company_name" placeholder="{{ translate('company name') }}">
                                    </div>
                                </div>

                                <div class="shipping-information-row mx-2">
                                    <div class="shipping-information-input-col">
                                        <input type="text" class="form-control" name="shipping_customer_name" placeholder="{{ translate('customer name') }}">
                                    </div>
                                </div>

                                <div class="shipping-information-row mx-2">
                                    <div class="shipping-information-input-col">
                                        <input type="email" class="form-control" name="shipping_customer_email" placeholder="{{ translate('customer email') }}">
                                    </div>
                                </div>

                                <div class="shipping-information-row mx-2">
                                    <div class="shipping-information-input-col">
                                        <input type="text" class="form-control" name="shipping_customer_phone" placeholder="{{ translate('customer phone') }}">
                                    </div>
                                </div>
                            </div>
                        </div> --}}



                        <div class="form-group row" style="padding-top: 20px;padding-bottom:20px;">
                            <label class="col-md-3 col-from-label">{{ translate('VAT & Tax') }}
                                <span class="text-danger">*</span></label>

                            <div class="col-md-7">
                                <input type="number" lang="en" min="0" value="0" step="0.01"
                                    placeholder="{{ translate('VAT & Tax') }}" name="tax" class="form-control" required>
                            </div>

                            <div class="col-md-2">
                                <select class="form-control aiz-selectpicker" name="tax_type">
                                    <option value="fixed">{{translate('Flat')}}</option>
                                    <option value="percent">{{translate('Percent')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" style="padding-top: 20px;padding-bottom:20px;">
                            <label class="col-md-3 col-from-label">{{ translate('Discount') }}
                                <span class="text-danger">*</span></label>

                            <div class="col-md-7">
                                <input type="number" lang="en" min="0" value="0" step="0.01"
                                    placeholder="{{ translate('Discount') }}" name="discount" class="form-control"
                                    required>
                            </div>

                            <div class="col-md-2">
                                <select class="form-control aiz-selectpicker" name="discount_type">
                                    <option value="fixed">{{translate('Flat')}}</option>
                                    <option value="percent">{{translate('Percent')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="shipping_div_btns">
                        @if (get_setting('auto_shipping') == 0)
                        <div class="col-md-6 mx-4 d-flex justify-content-between" style="padding-top:20px;" id="is_shipping">
                            <label for="shipping" class="form-check-label">{{ translate('Shipping Company')}}</label>
                            <input type="checkbox" name="is_shipping" class="form-check-input" id="shipping">
                        </div>
                        @endif

                        <div class="col-md-6 mx-4 d-flex justify-content-between " style="padding-top:20px;" id="is_open">

                        </div>
                    </div>


                    <div class="col-md-6 mx-4 d-flex justify-content-between" style="padding-top:20px;" id="is_paid">
                        <label for="is_paid" class="form-check-label">{{ translate('Is This Order Is Paid')}}</label>
                        <input type="checkbox" name="is_paid" class="form-check-input">
                    </div>


                        <div class="col-md-12" style="padding-top: 20px;padding-bottom:20px;">
                                <select class="form-control aiz-selectpicker" name="payment_type">
                                    <option value="">
                                        {{translate('Select Option')}}
                                    </option>
                                    <option value="cash">
                                        {{translate('Cash')}}
                                    </option>
                                    <option value="cash_on_delivery">
                                        {{translate('Cash On Delivery')}}
                                    </option>
                                    <option value="visa">
                                        {{translate('Visa')}}
                                    </option>
                                </select>
                        </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Additional Info') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Additional Info') }}</label>
                                <div class="col-md-8">
                                    <textarea class="aiz-text-editor" name="additional_info"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group" role="group" aria-label="Second group">
                            <button type="submit" name="button" value="publish" class="btn btn-success action-btn">{{
                                translate('Save & Publish') }}</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>

@endsection

@section('script')
<script type="text/javascript">


    $('input[name=is_shipping]').on('change',function (){
        if($('input[name=is_shipping]').is(':checked')){
            $('#is_open').append(`
                <label for="is_open" class="form-check-label ">{{ translate('Is The Shipping Package Can Be
                    Opened')}}</label>
                <input type="checkbox" name="is_open" class="form-check-input">
            `);
        }else{
            $('#is_open').empty();
        }
    });

    var productsData = @json($products);


    function getProductInfo(selectedValue, rowId) {
        var productInfoSelect = document.getElementById(`product_info_${rowId}`);
        productInfoSelect.innerHTML = '';
        if (selectedValue.startsWith("P_")) {
            var productId = selectedValue.substring(2);
            var selectedProduct = productsData.find(product => product.id == productId);
            var price = selectedProduct.unit_price;
            var wholesale_price = selectedProduct.wholesale_price;
            var wholesale_price_variant = selectedProduct.wholesale_price_variant;
            var optionValues = {
                "0": price,
                "1": wholesale_price,
                "2": wholesale_price_variant
            };
            var optionValuesText = {
                "0": price + " ({{ translate('Unit Price') }})",
                "1": wholesale_price + " ({{ translate('Wholesale Price') }})",
                "2": wholesale_price_variant + " ({{ translate('Wholesale Price Variant') }})"
            };
        } else if (selectedValue.startsWith("V_")) {
            var variantId = selectedValue.substring(2);
            var selectedProduct = productsData.find(product => product.stocks.some(variant => variant.id == variantId));

            var selectedVariant = selectedProduct.stocks.find(variant => variant.id == variantId);
            var price = selectedVariant.price;
            var wholesale_price = selectedVariant.wholesale_price;
            var wholesale_price_variant = selectedVariant.wholesale_price_variant;
            var optionValues = {
                "0": price,
                "1": wholesale_price,
                "2": wholesale_price_variant
            };

            var optionValuesText = {
                "0": price + " ({{ translate('Unit Price') }})",
                "1": wholesale_price + " ({{ translate('Wholesale Price') }})",
                "2": wholesale_price_variant + " ({{ translate('Wholesale Price Variant') }})"
            };

        }

        for (var value in optionValues) {
            var option = document.createElement('option');
            option.value = optionValues[value];
            option.text = optionValuesText[value];
            productInfoSelect.appendChild(option);
        }
        $(productInfoSelect).selectpicker('refresh');
    }



    var newRowId = 1;
    var productsData = {!! json_encode($products)!!};
    function addNewProduct() {
        var productContainer = document.querySelector('.order-slider-target');
        var newId = newRowId + 1;
        var newRow = document.createElement('div');
        newRow.className = 'row gutters-5 mt-2';
        newRow.id = 'row-' + newId;

        var colMd5Product = document.createElement('div');
        colMd5Product.className = 'col-md-5';


        var productSelect = document.getElementById('product_id').cloneNode(true);
        productSelect.name = 'product_id_' + newId;
        productSelect.className = 'form-control aiz-selectpicker';
        productSelect.setAttribute('data-live-search', 'true');

        productSelect.selectedIndex = 0;
        colMd5Product.appendChild(productSelect);

        newRow.appendChild(colMd5Product);

        var colMd5Price = document.createElement('div');
        colMd5Price.className = 'col-md';

        var colMd5Quantity = document.createElement('div');
        colMd5Quantity.className = 'col-md';


        var priceSelect = document.getElementById('product_info_1').cloneNode(true);
        priceSelect.name = 'product_info_' + newId;
        priceSelect.id = 'product_info_' + newId;
        priceSelect.selectedIndex = 0;

        var quantity = document.getElementById('product_quantity_1').cloneNode(true);
        quantity.name = 'product_quantity_' + newId;
        quantity.id = 'product_quantity_' + newId;


        colMd5Price.appendChild(priceSelect);
        colMd5Quantity.appendChild(quantity);
        newRow.appendChild(colMd5Price);
        newRow.appendChild(colMd5Quantity);

        var colMdAuto = document.createElement('div');
        colMdAuto.className = 'col-md-auto';

        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger';
        removeButton.setAttribute('data-toggle', 'remove-parent');
        removeButton.setAttribute('data-parent', '.row');
        removeButton.innerHTML = '<i class="las la-times"></i>';

        colMdAuto.appendChild(removeButton);
        newRow.appendChild(colMdAuto);

        productContainer.appendChild(newRow);

        productSelect.onchange = function () {
            getProductInfo(this.value, newId);
        }
        $(productSelect).selectpicker('refresh');
        $(priceSelect).selectpicker('refresh');
        newRowId++;
    }

    function customer_type(id) {
        if (id == 0) {
            document.getElementById('customer_name').classList.remove('d-none');
            document.getElementById('customer_email').classList.remove('d-none');
            document.getElementById('customer_id').classList.add('d-block');

        } else {
            document.getElementById('customer_name').classList.add('d-none');
            document.getElementById('customer_email').classList.add('d-none');
            document.getElementById('customer_id').classList.remove('d-block');

            // var selectedOption = $('#customer_id option:selected');
            // var customerData = selectedOption.data('customer-data');
            // $('input[name="shipping_customer_name"]').val(customerData.name);
            // $('input[name="shipping_customer_email"]').val(customerData.email);
            // $('input[name="shipping_customer_phone"]').val(customerData.phone);
        }
    }

    // $(document).ready(function (){
    //     $('#customer_name').on('keyup',function (){
    //             var name = $('input[name="client_name"]').val();
    //             $('input[name="shipping_customer_name"]').val(name);
    //     });

    //     $('#customer_email').on('keyup',function (){
    //             var email = $('input[name="email"]').val();
    //             $('input[name="shipping_customer_email"]').val(email);
    //     });

    //     $('#customer_phone').on('keyup',function (){
    //             var phone = $('input[name="phone"]').val();
    //             $('input[name="shipping_customer_phone"]').val(phone);
    //     });
    // })

    var statesData = @json($states);

    function changeState(selectedState) {
        var citySelect = document.getElementById("city_id");
        citySelect.innerHTML = "";
        var selectedStateData = statesData.find(state => state.id == selectedState);
        var defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "{{ translate('Select City') }}";
        citySelect.appendChild(defaultOption);

        if (selectedStateData) {
            selectedStateData.cities.forEach(city => {
                var option = document.createElement("option");
                option.value = city.id;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
        }
        $(citySelect).selectpicker('refresh');
    }
    function changeShippingMethod(value) {
        var shippingDetails = document.getElementById("state_div");
        var StateDiv = document.getElementById("state_div");
        var CityDiv = document.getElementById("city_div");
        var AddressDiv = document.getElementById("address_div");
        var IsShipping = document.getElementById("is_shipping");
        var shippingInformation = document.getElementById("shipping_information_div");
        var IsOpen = document.getElementById("is_open");
        var IsPaid = document.getElementById("is_paid");

        if (value == "pick_up_from_store") {
            shippingDetails.style.display = "none";
            StateDiv.style.display = "none";
            CityDiv.style.display = "none";
            AddressDiv.style.display = "none";
            IsPaid.style.display = "none";
            $('#shipping_div_btns').hide();
            // shippingInformation.style.display = "none";
            if(IsShipping != null)
            {
                IsShipping.style.display = "none";
            }
        } else if(value == "shipping_company") {
            shippingDetails.style.display = "flex";
            StateDiv.style.display = "flex";
            CityDiv.style.display = "flex";
            AddressDiv.style.display = "flex";
            IsPaid.style.display = "flex";
            $('#shipping_div_btns').show();
            // shippingInformation.style.display = "flex";
            if(IsShipping != null)
            {
                IsShipping.style.display = "flex";
            }
        } else {
            shippingDetails.style.display = "flex";
            StateDiv.style.display = "flex";
            CityDiv.style.display = "flex";
            AddressDiv.style.display = "flex";
            IsPaid.style.display = "flex";
            $('#shipping_div_btns').show();
            // shippingInformation.style.display = "none";
            if(IsShipping != null)
            {
                IsShipping.style.display = "flex";
            }
        }
    }


</script>
@endsection
