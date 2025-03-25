<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ translate('Shipping data') }}</title>
    <style media="all">
        body {
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f8f8f8;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background-color: #141423;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 24px;
        }

        .section {
            padding: 20px;
            border-bottom: 1px solid #eee;
            color: #555;
        }

        .bill-to,
        .shipping-address {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #141423;
            color: #fff;
            padding: 12px;
        }

        .total {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            color: #333;
            border-radius: 5px;
            text-align: right;
        }

        .total p {
            margin: 5px 0;
        }

        .footer {
            background-color: #f3f3f3;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(get_setting('system_logo_white') != null)
            <img class="mw-100" style="width: 100px" src="{{ uploaded_asset(get_setting('system_logo_white')) }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
            @else
                <img class="mw-100" style="width: 100px" src="{{ static_asset('assets/img/logo.png') }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
            @endif
            <h1>{{ translate('Shipping Information') }}</h1>
        </div>

        <div class="section">
            <div class="bill-to">
                <h2>{{ translate('Bill To') }} :</h2>
                <table>
                    <tr><td><strong>{{ translate('Company Name') }}:</strong></td><td>{{ $shipping_address['company_name'] }}</td></tr>
                    <tr><td><strong>{{ translate('Customer Name') }}:</strong></td><td>{{ $shipping_address['name'] }}</td></tr>
                    <tr><td><strong>{{ translate('Address') }}:</strong></td><td>{{ $shipping_address['address'] }}, {{ $shipping_address['city'] }}, {{ $shipping_address['state'] }}, {{ $shipping_address['country'] }}</td></tr>
                    <tr><td><strong>{{ translate('Email') }}:</strong></td><td>{{ $shipping_address['email'] }}</td></tr>
                    <tr><td><strong>{{ translate('Phone') }}:</strong></td><td>{{ $shipping_address['phone'] }}</td></tr>
                </table>
            </div>
        </div>

        <div class="section">
            <h2>{{ translate('Order Details') }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>{{ translate('Product') }}</th>
                        <th>{{ translate('Variation') }}</th>
                        <th>{{ translate('Price') }}</th>
                        <th>{{ translate('Quantity') }}</th>
                        <th>{{ translate('Subtotal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $shipping_cost = 0;
                    @endphp
                    @foreach($order->orderDetails as $product)
                        <tr>
                            <td><strong>{{ $product->product->name }}</strong></td>
                            <td>{{ $product->variation }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->price * $product->quantity }}</td>
                            @php
                                $shipping_cost = $product->shipping_cost;
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total">
            <p><strong>{{ translate('Shipping Cost') }}:</strong> ${{ $shipping_cost }}</p>
            <p><strong>{{ translate('tax') }}:</strong> ${{ $order->tax_value }}</p>
            <p><strong>{{ translate('coupon discount') }}:</strong> ${{ $order->coupon_discount }}</p>
            <p><strong>{{ translate('Total') }}:</strong> ${{ $order->grand_total }}</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{env('APP_NAME')}}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
