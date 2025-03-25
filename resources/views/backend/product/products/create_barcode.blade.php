{{-- @dd(get_setting('system_default_currency')) --}}
@extends('backend.layouts.app')
@section('content')


<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Print Barcode For Product')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-3">
            <div class="container">
                <div class="aiz-titlebar text-left mt-2 mb-3">
                    <div class="form-group row mt-4">
                        <label class="col-md-3 col-from-label">{{translate('Product')}} <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control aiz-selectpicker" id="product_id" name="product_id">
                                 <option value="0">{{translate('Select Product To Get Stocks')}} </option>
                                 @foreach($products as $product)
                             <option value="{{$product->id}} ">{{$product->getTranslation('name')}} </option>
                                  @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row ">
                        <label class="col-md-3 col-from-label">{{translate('Stocks')}} </label>
                        <div class="col-md-8">
                            <select class="form-control  " id="product_sku" name="product_sku">
                                 <option   value="0">{{translate('Select Stock')}} </option>
                                 @foreach($products as $product)
                                    @foreach($product->stocks as $stock)
                                        <option   value="{{$stock->sku}}">{{$stock->variant !== '' ? $stock->variant : $product->getTranslation('name')}} </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Number Of barcode')}}</label>
                        <div class="col-md-8">
                            <input class="form-control" id="skus_number" value="1" min="1" >
                        </div>
                    </div>
                    <div class="form-group row mt-5" >
                        <button class="btn btn-primary action-btn mx-1"  onclick="createBarcode()">{{translate('Create barcode')}}</button>
                        <button class="btn btn-dark action-btn mx-1" onclick="printBarcode()">{{translate('Print Barcode')}}</button>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-6 mx-auto" style="display: none" id="barcode_container">
    <div class="card row d-flex justify-content-center">
        <div class="card-body d-flex justify-content-center col-12">
            <div class="col-12" id="barcodeContainer"  >
                <img   class="barcode barcodeImage">
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">

$('#product_id').change(function () {
    var productId = $('#product_id').val();
    var variationName = $('#product_id').find(':selected').text()
    var select = $('#product_sku');
    select.prop('disabled', true); // Disable the select before making the AJAX call
    $.ajax({
        url: "{{ route('getProductStock') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        data: {id: productId},
        success: function (response) {
            select.empty();
            select.append($('<option  >Select Stock</option>'));
            response.product.stocks.forEach(function (stock) {
                var optionText = stock.variant !== '' ? stock.variant : response.product.name;
                select.append($('<option>').attr('value', `${stock.id}-` + stock.product_id + '-' + stock.price).text(optionText));
            });
            select.prop('disabled', false); // Enable the select upon successful response
        },
        error: function (xhr, status, error) {
            select.append($('<option  >Error Getting stocks</option>'));
            select.prop('disabled', false); // Enable the select even if there's an error
        }
    });
});



function createBarcode() {
    var barcodeContainer = $('#barcodeContainer');
    barcodeContainer.empty();

    var sku = $('#product_sku').val();
    var skusNumber = $('#skus_number').val();
    var variationName = $('#product_id').find(':selected').text();

    for (var i = 0; i < skusNumber; i++) {
        var container = $('<div class="barcode-container"></div>');
        var barcodeImage = $('<img class="barcode barcodeImage" style="width:600px;height:200px;">');

        // Add additional information as text beneath the barcode
        JsBarcode(barcodeImage[0], sku, {
            format: "CODE128",
            displayValue: true,
            fontSize: 16,
            lineColor: "#000",
            text: variationName + '-' + sku,
        });

        container.append(barcodeImage);
        barcodeContainer.append(container);
    }
    $('#barcode_container').show();
}


function printBarcode() {
    var printContents = document.getElementById('barcodeContainer').outerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}

</script>

@endsection
