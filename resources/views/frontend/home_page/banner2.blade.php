@if (get_setting('home_banner2_images') != null)
    @foreach (json_decode(get_setting('home_banner2_images'), true) as $key => $value)
        {{-- <a   href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}" class="banner2 d-none d-lg-block"  style="background-color:#F6F2F2;margin-top: -75px;">
        <div class="container">
            <img width="100%"
                src="{{ uploaded_asset(json_decode(get_setting('home_banner2_images'), true)[$key]) }}"
                alt="">
        </div>
    </a>
    <a style="margin-top: -75px;" href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}" class="banner2 d-lg-none d-md-block"  style="background-color:#F6F2F2">
        <div class="container">
            <img width="100%"
                src="{{ uploaded_asset(json_decode(get_setting('banner2_small'), true)) }}"
                alt="">
        </div>
    </a> --}}

        <div class="container-fluid  p-0">
            <div class="container">
                <div class="position-relative d-flex align-items-center sm-gap banner1_container">
                    <div class="animated-banner-text">
                        {!! get_setting('home_banner2_text') !!}
                    </div>
                    <div class="px-4 mobile-absolute-right--1">
                        <img class="animated-banner-img"
                            src="{{ uploaded_asset(json_decode(get_setting('home_banner2_images'), true)[$key]) }}">
                    </div>
                </div>

            </div>
        </div>
        <div class="sections-between-space"></div>
    @endforeach
@endif
