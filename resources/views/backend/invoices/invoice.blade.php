<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ translate('INVOICE') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <style media="all">
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 0.875rem;
            font-family: "IBM Plex Sans", sans-serif;
            font-weight: normal;
            direction: <?php echo $direction; ?>;
            text-align: <?php echo $text_align; ?>;
            padding: 0;
            margin: 0;
        }

        .gry-color *,
        .gry-color {
            color: #000;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .25rem .7rem;
        }

        table.padding td {
            padding: .25rem .7rem;
        }

        table.sm-padding td {
            padding: .1rem .7rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }

        .text-left {
            text-align: <?php echo $text_align; ?>;
        }

        .text-right {
            text-align: <?php echo $not_text_align; ?>;
        }
    </style>
</head>

<body>
    <div>
       @php
			$logo = get_setting('header_logo');
		@endphp
        <div style="background: #eceff4;padding: 1rem;">
            <table>
                <tr>
                    <td>
                       <td> 
							<img src="data:image/png;base64,{{$logoImage}}" height="30" style="display:inline-block;"> 
					</td>
                    </td>
                    <td style="font-size: 1.5rem;" class="text-right strong">{{ translate('INVOICE') }} </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="font-size: 1rem;" class="strong">{{ get_setting('site_name') }}</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ get_setting('contact_address') }}</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Email') }}: {{ get_setting('contact_email') }}</td>
                    <td class="text-right small"><span class="gry-color small">{{ translate('Order ID') }}:</span> <span
                            class="strong">{{ $order->code }}</span></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Phone') }}: {{ get_setting('contact_phone') }}</td>
                    <td class="text-right small"><span class="gry-color small">{{ translate('Order Date') }}:</span>
                        <span class=" strong">{{ date('d-m-Y', $order->date) }}</span></td>
                </tr>
                <tr>
                    <td class="gry-color small"></td>
                    <td class="text-right small">
                        <span class="gry-color small">
                            {{ translate('Payment method') }}:
                        </span>
                        <span class="strong">
                            {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                        </span>
                    </td>
                </tr>
            </table>

        </div>

        <div style="padding: 1rem;padding-bottom: 0">
            <table>
                @php
                    $shipping_address = json_decode($order->shipping_address);
                @endphp
                <tr>
                    <td class="strong small gry-color">{{ translate('Bill to') }}:</td>
                </tr>
                <tr>
                    <td class="strong">{{ $order->user->name }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ $shipping_address->address }}, {{ $shipping_address->city }},
                      </td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Email') }}: {{ $shipping_address->email }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Phone') }}: {{ $shipping_address->phone }}</td>
                </tr>
            </table>
        </div>

        <div style="padding: 1rem;">
            <table class="padding text-left small border-bottom">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th width="35%" class="text-left">{{ translate('Product Name') }}</th> 
                        <th width="10%" class="text-left">{{ translate('Qty') }}</th>
                        <th width="15%" class="text-left">{{ translate('Unit Price') }}</th>
                        <th width="10%" class="text-left">{{ translate('Tax') }}</th>
                        <th width="15%" class="text-right">{{ translate('Total') }}</th>
                    </tr>
                </thead>
                <tbody class="strong">
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        @if ($orderDetail->product != null)
                            <tr class="">
                                <td>
                                    {{ $orderDetail->product->name }}
                                    @if ($orderDetail->variation != null)
                                        ({{ $orderDetail->variation }})
                                    @endif
                                    <br>
                                    <small>
                                        @php
                                            $product_stock = json_decode($orderDetail->product->stocks->first(), true);
                                        @endphp
                                        {{ translate('SKU') }}: {{ $product_stock['sku'] }}
                                    </small>
                                </td>
                             
                                <td class="">{{ $orderDetail->quantity }}</td>
                                <td class="currency">{{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                </td>
                                <td class="currency">{{ single_price($orderDetail->tax / $orderDetail->quantity) }}</td>
                                <td class="text-right currency">
                                    {{ single_price($orderDetail->price + $orderDetail->tax) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:0 1.5rem;">
            <table class="text-right sm-padding small strong">
                <thead>
                    <tr>
                        <th width="60%"></th>
                        <th width="40%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left">
                            @php
                            $removedXML = '<?xml version="1.0" encoding="UTF-8"@endphp';
?>
                            {!! str_replace($removedXML, '', QrCode::size(100)->generate($order->code)) !!}
                        </td>
                        <td>
                            <table class="text-right sm-padding small strong">
                                <tbody>
                                    <tr>
                                        <th class="gry-color text-left">{{ translate('Sub Total') }}</th>
                                        <td class="currency">{{ single_price($order->orderDetails->sum('price')) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="gry-color text-left">{{ translate('Shipping Cost') }}</th>
                                        <td class="currency">
                                            {{ single_price($order->shipping_cost) }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th class="gry-color text-left">{{ translate('Total Tax') }}</th>
                                        <td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th class="gry-color text-left">{{ translate('Coupon Discount') }}</th>
                                        <td class="currency">{{ single_price($order->coupon_discount) }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th class="gry-color text-left">{{ translate('COD FEES') }}</th>
                                        <td class="currency">{{ single_price($order->COD_tax) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left strong">{{ translate('Grand Total') }}</th>
                                        <td class="currency">{{ single_price($order->grand_total) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>
