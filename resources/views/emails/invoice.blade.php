<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <meta http-equiv="Content-Type" content="text/html;" />
    <meta charset="UTF-8">
    <style media="all">
        @font-face {
            font-family: 'Roboto';
            src: url({{ static_asset('assets/fonts/ArbFONTS-cocon-next-arabic.ttf') }}) format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            font-family: 'Roboto';
            color: #333542;
        }

        body {
            font-size: .875rem;
        }

        .border-bottom *,
        .border-bottom {
            color: #878f9c;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .5rem .7rem;
        }

        table.padding td {
            padding: .7rem;
        }

        table.sm-padding td {
            padding: .2rem .7rem;
        }

        .border-bottom {
            border-bottom: 1px solid #272727;
        }

        .text-right {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .small {
            font-size: .85rem;
        }

        .mail-img {
            margin-bottom: 50px;
        }

        .main_text {
            font-size: 30px;
            font-weight: bolder;
            color: black;
            margin-bottom: 15px;
        }

        .sub_text {
            font-size: 20px;
            font-weight: bolder;
            color: rgb(86, 86, 86);
            margin-bottom: 10px;
        }

        .sub_title {
            font-size: 20px;
            font-weight: bolder;
            color: rgb(0, 0, 0);
        }

        .btn-btn {
            border: unset;
            font-weight: bold;
            border-radius: 0px 0px 0px 20px;
            color: white;
            background-color: black;
            padding: 15px 30px;
            text-decoration: unset
        }

        .margin-buttom {
            margin-bottom: 30px
        }

        .banner {
            display: flex;
        }

        .banner img {
            position: absolute;
            top: 50%;
            left: -50%;
        }

        .banner .text-container {
            position: absolute;
            top: 50%;
            right: 0px;
            padding-top: 75px;
            z-index: 50;

        }

        .banner .title {
            font-weight: bold;
            font-size: 40px;
            color: #ED48FF;
        }

        .banner .subtitle {
            font-size: 23px;
            font-weight: bold;
        }

        .p-r {
            position: relative;
        }
    </style>
</head>

<body>
    <div style="padding: 1.5rem;" class="text-right">
        @php
            $shipping_address = json_decode($order->shipping_address);
        @endphp
        <div>
            <div class="mail-img">
                <img src="{{ uploaded_asset(get_setting('invoice_image')) }}" width="100%" alt="">
            </div>
            <div class="main_text">
                {{ translate('Welcome' . '  ' . $shipping_address->name) }}
            </div>
            <div class="sub_text">
                {{ translate('Your choice of' . get_setting('site_name') . ' for your daily elegance!') }}
            </div>
            <div class="sub_text">
                {{ translate('Thank you. We are currently processing your order, and we are diligently preparing your favorite products with love and care.') }}
            </div>
            <div class="main_text">
                {{ translate('Order Details') }}
            </div>
            <div>
                <div>
                    <table class="padding text-right small border-bottom">
                        <thead>
                            <tr>
                                <th class="sub_title">{{ translate('Total') }}</th>
                                <th class="sub_title"> </th>

                                <th class="sub_title  text-left">{{ translate('Product') }}</th>
                            </tr>
                        </thead>
                    </table>
                    <table class="padding text-right small ">
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                @if ($orderDetail->product != null)
                                    <tr class="">
                                        <td class="sub_title">
                                            {{ single_price($orderDetail->price + $orderDetail->tax) }}</td>
                                        <td class="sub_title">
                                            @php
                                                $variationImg = \App\Models\ProductStock::where('product_id', $orderDetail->product->id)
                                                    ->where('variant', $orderDetail->variation)
                                                    ->first();
                                            @endphp
                                            {{ $orderDetail->product->getTranslation('name') }} @if ($orderDetail->variation != null)
                                                ({{ $orderDetail->variation }})
                                            @endif
                                        </td>
                                        <td class="sub_title">
                                            <img width="70px"
                                                src="{{ uploaded_asset($variationImg->image != null ? $variationImg->image : $orderDetail->product->thumbnail_img) }}"
                                                alt="">
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="margin-buttom">
                <div>
                    <table class="padding text-right small border-bottom">
                        <thead>
                            <tr class="border-bottom">
                                <th class="sub_title"> </th>
                                <th class="sub_title"> </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="">
                                <td class="sub_title">
                                    {{ translate('ORder number') }}
                                </td>
                                <td class="sub_title">
                                    {{ $order->code }}
                                </td>
                            </tr>
                            <tr class="">
                                <td class="sub_title">
                                    {{ translate('Order phone') }}
                                </td>
                                <td class="sub_title">
                                    {{ $shipping_address->phone }}
                                </td>
                            </tr>
                            <tr>
                                <th class="sub_title">{{ translate('Sub Total') }}</th>
                                <td class="sub_title">{{ single_price($order->orderDetails->sum('price')) }}</td>
                            </tr>
                            <tr>
                                <th class="sub_title">{{ translate('Shipping Cost') }}</th>
                                <td class="sub_title">{{ single_price($order->orderDetails->sum('shipping_cost')) }}
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="sub_title">{{ translate('Total Tax') }}</th>
                                <td class="sub_title">{{ single_price($order->orderDetails->sum('tax')) }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="sub_title">{{ translate('Coupon') }}</th>
                                <td class="sub_title">{{ single_price($order->coupon_discount) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="padding text-right ">
                        <thead class="border-bottom">
                            <tr>
                                <th class="sub_title"> </th>
                                <th class="sub_title"> </th>
                            </tr>
                        </thead>
                        @php
                            $shipping_address = json_decode($order->shipping_address);
                        @endphp
                        <tbody>
                            <tr>
                                <th class="sub_title">{{ translate('Grand Total') }}</th>
                                <td class="sub_title">{{ single_price($order->grand_total) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="margin-buttom">
                <a class="btn-btn"
                    href="{{ route('invoice_download', $order->id) }}">{{ translate('Print Invoice') }}</a>
            </div>
            <div class="main_text">
                {{ translate('in' . '  ' . get_setting('site_name')) }}
            </div>
            <div class="sub_text">
                {{ translate('Every piece we design, we design with the idea that it should embody the beauty and luxury you dream of. We hope you feel comfortable and elegant every time you wear our products.') }}
            </div>
            <div class="main_text">
                {{ translate('When will the order be delivered?') }}
            </div>
            <div class="sub_text margin-buttom">
                {{ translate("The delivery of your order doesn't take much time! Within a few days, you will receive your order at your doorstep, ready for you to enjoy a striking and distinctive look.") }}
            </div>
            <div class="margin-buttom">
                <a class="btn-btn" href="{{ route('orders.track') }}">{{ translate('Trancking Your Order') }}</a>
            </div>
            <div class="main_text">
                {{ translate('Enjoy a delightful shopping experience!') }}
            </div>
            <div class="sub_text margin-buttom">
                {{ translate("Don't hesitate to reach out to us if you have any questions or need further assistance. We are always here to help you!") }}
            </div>
            <div class="sub_text margin-buttom">
                {{ translate("Don't deprive us of your photos and elegance with #shawl. Follow us on our social media accounts!") }}
            </div>
            <div class="sub_text margin-buttom">
                {{ translate('Thank you for your trust!') }}
            </div>
            <div class="main_text">
                {{ translate(get_setting('site_name') . '  ' . 'Team') }}
            </div>
            <div class="p-r">
                @if (get_setting('home_banner1_images') != null)
                    @foreach (json_decode(get_setting('home_banner1_images'), true) as $key => $value)
                        <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}" class="banner1">
                            <div class="banner">
                                <div class="text-container">
                                    <div class="subtitle">
                                        {{ json_decode(get_setting('home_banner1_subtitle'), true)[$key] }}</div>
                                    <div class="title">
                                        {{ json_decode(get_setting('home_banner1_title'), true)[$key] }}</div>
                                </div>
                                <div class=" text-center">
                                    <img width="300px"
                                        src="{{ uploaded_asset(json_decode(get_setting('home_banner1_images'), true)[$key]) }}"
                                        alt="">
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</body>

</html>
