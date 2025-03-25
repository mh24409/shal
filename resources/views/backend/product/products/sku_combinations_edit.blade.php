@if (count($combinations[0]) > 0)
    <table class="table table-bordered aiz-table">
        <thead>
            <tr>
                <td class="text-center">
                    {{ translate('Variant') }}
                </td>
                <td class="text-center">
                    {{ translate('Variant Price') }}
                </td>
                {{-- <td class="text-center">
                    {{ translate('Wholesale_price') }}
                </td> --}}
                {{-- <td class="text-center">
                    {{ translate('Wholesale_price_variant') }}
                </td> --}}
                <td class="text-center">
                    {{ translate('Cost Price') }}
                </td>
                <td class="text-center">
                    {{ translate('Default') }}
                </td>
                <td class="text-center">
                    {{ translate('SKU') }}
                </td>
                <td class="text-center">
                    {{ translate('Quantity') }}
                </td>
                {{-- <td class="text-center variant">
                    {{ translate('Suit') }}
                </td> --}}
                <td class="text-center">
                    {{ translate('Photo') }}
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($combinations as $key => $combination)
                @php
                    $variation_available = false;
                    $sku = '';
                    foreach (explode(' ', $product_name) as $key => $value) {
                        $sku .= substr($value, 0, 1);
                    }
                    $str = '';
                    foreach ($combination as $key => $item) {
                        if ($key > 0) {
                            $str .= '-' . str_replace(' ', '', $item);
                            $sku .= '-' . str_replace(' ', '', $item);
                        } else {
                            if ($colors_active == 1) {
                                $color_name = \App\Models\Color::where('code', $item)->first()->name;
                                $str .= $color_name;
                                $sku .= '-' . $color_name;
                            } else {
                                $str .= str_replace(' ', '', $item);
                                $sku .= '-' . str_replace(' ', '', $item);
                            }
                        }
                        $stock = $product->stocks->where('variant', $str)->first();
                        // if($stock != null) {
                        //     $variation_available = true;
                        // }
                    }
                @endphp
                @if (strlen($str) > 0)
                    <tr class="variant">
                        <td>
                            <label for="" class="control-label">{{ $str }}</label>
                        </td>
                        <td>
                            <input type="number" lang="en" name="price_{{ $str }}"
                                value="@php
                            if ($product->unit_price == $unit_price) {
                                                                if($stock != null){
                                                                    echo $stock->price;
                                                                }
                                                                else {
                                                                    echo $unit_price;
                                                                }
                                                            }
                                                            else{
                                                                echo $unit_price;
                                                            } @endphp"
                                min="0" step="0.01" class="form-control unit_price" required>
                        </td>
                        {{-- <td>
                            <input type="number" lang="en" name="Wholesale_price_{{ $str }}"
                                value="@php
                            if ($product->unit_price == $unit_price) {
                                                                if($stock != null){
                                                                    echo $stock->wholesale_price;
                                                                }
                                                                else {
                                                                    echo $unit_price;
                                                                }
                                                            }
                                                            else{
                                                                echo $unit_price;
                                                            } @endphp"
                                min="0" step="0.01" class="form-control wholesale_price" required>
                        </td> --}}
                        {{-- <td>
                            <input type="number" lang="en" name="Wholesale_price_variant_{{ $str }}"
                                value="@php
                            if ($product->unit_price == $unit_price) {
                                                                if($stock != null){
                                                                    echo $stock->wholesale_price_variant;
                                                                }
                                                                else {
                                                                    echo $unit_price;
                                                                }
                                                            }
                                                            else{
                                                                echo $unit_price;
                                                            } @endphp"
                                min="0" step="0.01" class="form-control wholesale_price_variant" required>
                        </td> --}}
                        <td>
                            <input type="number" lang="en" name="cost_price_{{ $str }}"
                                value="@php
                            if ($product->unit_price == $unit_price) {
                                                                if($stock != null){
                                                                    echo $stock->cost_price;
                                                                }
                                                                else {
                                                                    echo $unit_price;
                                                                }
                                                            }
                                                            else{
                                                                echo $unit_price;
                                                            } @endphp"
                                min="0" step="0.01" class="form-control cost_price" required>
                        </td>
                        <td>
                            <input type="radio" id="{{ $str }}"
                                {{ isset($stock->default) && $stock->default == 1 ? 'checked' : '' }}
                                name="default_variation" value="{{ $str }}">
                        </td>
                        <td>
                            <input type="text" name="sku_{{ $str }}"
                                value="@php
                            if($stock != null) {
                                                                echo $stock->sku;
                                                            }
                                                            else {
                                                                echo $str;
                                                            } @endphp"
                                class="form-control">
                        </td>
                        <td>
                            <input type="number" lang="en" name="qty_{{ $str }}"
                                value="@php
                            if($stock != null){
                                                                echo $stock->qty;
                                                            }
                                                            else{
                                                                echo '10';
                                                            } @endphp"
                                  step="1" class="form-control" required>
                        </td>
                        <td>
                            <div class="input-group" data-toggle="aizuploader" data-multiple="true" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount text-truncate">{{ translate('Choose File') }}
                                </div>
                                <input type="hidden" name="img_{{ $str }} []" class="selected-files"
                                    value="@php
                            if($stock != null){
                                    echo $stock->image;
                                }
                                else{
                                    echo null;
                                } @endphp">
                            </div>
                            <div class="file-preview box sm"></div>
                        </td>
                    </tr>
                @endif
            @endforeach

        </tbody>
    </table>
@endif

<script>
    $('input[name="unit_price_checkbox"]').on('change', function() {
        unit_price_checkbox = $('input[name="unit_price_checkbox"]')

        $('.unit_price').val($('#unit_price').val());
        AIZ.plugins.bootstrapSelect('refresh');
        setTimeout(function() {
            unit_price_checkbox.prop('checked', false);
        }, 1000); // 1000 milliseconds = 1 second


    });


    $('input[name="wholesale_price_checkbox"]').on('change', function() {

        wholesale_price_checkbox = $('input[name="wholesale_price_checkbox"]')

        $('.wholesale_price').val($('#wholesale_price').val());
        AIZ.plugins.bootstrapSelect('refresh');
        setTimeout(function() {
            wholesale_price_checkbox.prop('checked', false);
        }, 1000); // 1000 milliseconds = 1 second

    });

    // wholesale_price_variant
    $('input[name="wholesale_price_variant_checkbox"]').on('change', function() {
        wholesale_price_variant_checkbox = $('input[name="wholesale_price_variant_checkbox"]')
        $('.wholesale_price_variant').val($('#wholesale_price_variant').val());
        AIZ.plugins.bootstrapSelect('refresh');
        setTimeout(function() {
            wholesale_price_variant_checkbox.prop('checked', false);
        }, 1000); // 1000 milliseconds = 1 second
    });

    $('input[name="cost_price_checkbox"]').on('change', function() {
        cost_price_checkbox = $('input[name="cost_price_checkbox"]')
        $('.cost_price').val($('#cost_price').val());
        AIZ.plugins.bootstrapSelect('refresh');
        setTimeout(function() {
            cost_price_checkbox.prop('checked', false);
        }, 1000); // 1000 milliseconds = 1 second
    });
</script>
