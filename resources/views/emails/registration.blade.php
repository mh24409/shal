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
        <div>
            <div class="mail-img">
                <img src="{{ uploaded_asset(get_setting('invoice_image')) }}" width="100%" alt="">
            </div>
            <div class="main_text">
                {{ translate('Welcome Dear' . '  ' . Auth::user()->name) }}
            </div>
            <div class="main_text">
                {{ translate('Your Verification Code Is ' . '  ' . Auth::user()->verification_code) }}
            </div>
            <div class="sub_text">
                {{ translate('in' . '  ' . get_setting('site_name') . '  ' . 'family') }}
            </div>
            <div class="sub_text">
                {{ translate('Congratulations, you have become one of us. Here is the meeting for everyone
                                                                                                                She loves elegance, so she wants style
                                                                                                                She carries it and highlights her beauty. In shawl, we love to support
                                                                                                                The beauty of every girl and we share her beautiful moments') }}
            </div>
            <div class="p-r">
                @if (get_setting('home_banner1_images') != null)
                    @foreach (json_decode(get_setting('home_banner1_images'), true) as $key => $value)
                        <div class="container-fluid  p-0">
                            <div class="container">
                                <div class="position-relative d-flex align-items-center sm-gap banner1_container">
                                    <div class="animated-banner-text">
                                        {!! get_setting('home_banner1_text') !!}
                                    </div>
                                    <div class="px-4 mobile-absolute-right--1">
                                        <img class="animated-banner-img"
                                            src="{{ uploaded_asset(json_decode(get_setting('home_banner1_images'), true)[$key]) }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
            <div class="sub_text">
                {{ translate('in' . get_setting('site_name') . 'family') }}
            </div>
            <div class="margin-buttom">
                {!! get_setting('register_mail_content') !!}
            </div>
            <div class="p-r">
                @include('frontend.home_page.banner1')
            </div>
        </div>
    </div>
</body>

</html>
