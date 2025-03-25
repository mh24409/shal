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
@php
    $cart = \App\Models\Cart::where('user_id', $user_id)->get();
    $user = \App\Models\User::find($user_id);
    $total = 0;
    if (isset($cart) && count($cart) > 0) {
        foreach ($cart as $key => $cartItem) {
            $product = \App\Models\Product::find($cartItem['product_id']);
            $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
        }
    }
@endphp

<body>
    <div style="padding: 1.5rem;" class="text-right">
        <div>
            <div class="mail-img">
                <img src="{{ uploaded_asset(get_setting('invoice_image')) }}" width="100%" alt="">
            </div>
            <div class="margin-buttom">
                {!! get_setting('cart_mail_content') !!}
            </div>
            {{-- asdasd --}}
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
                            @foreach ($cart as $key => $cartItem)
                                @if ($product != null)
                                    <tr class="">
                                        <td class="sub_title">
                                            {{ cart_product_price($cartItem, $product) }}</td>
                                        <td class="sub_title">
                                            @php
                                                $product = \App\Models\Product::find($cartItem['product_id']);
                                                $variationImg = \App\Models\ProductStock::where('product_id', $cartItem['product_id'])
                                                    ->where('variant', $cartItem->variation)
                                                    ->first();
                                            @endphp
                                            {{ $product->getTranslation('name') }}
                                            <br>
                                            {{ $cartItem->variation }}
                                        </td>
                                        <td class="sub_title">
                                            <img width="70px"
                                                src="{{ uploaded_asset($variationImg->image != null ? $variationImg->image : $product->thumbnail_img) }}"
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
                    <table class="padding text-right ">
                        <thead class="border-bottom">
                            <tr>
                                <th class="sub_title"> </th>
                                <th class="sub_title"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="sub_title">{{ single_price($total) }}</td>
                                <th class="sub_title">{{ translate('Total') }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- banner  --}}
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
