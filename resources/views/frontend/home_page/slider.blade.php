@if (get_setting('home_slider_images') != null)
    @foreach (json_decode(get_setting('home_slider_images'), true) as $key => $value)
        <section class="hero-section" id="section_1">
            <div class="section-overlay"></div>
            <div class="container d-flex flex-column justify-content-center algin-items-center " style="z-index:3">
                <div style="font-size: 39px;" class="h5 mb-0 text-capitalize  text-white">
                    {{ translate(json_decode(get_setting('home_slider_subtitle'), true)[$key]) }}
                </div>
                <div style="font-size: 42px;" class="h5  fw-700 mb-0 text-capitalize text-white ">
                    {{ translate(json_decode(get_setting('home_slider_title'), true)[$key]) }}
                </div>
            </div>
            <div class="video-wrap">
                <video loop="true" playsInline  muted autoplay class="custom-video HeroVideo" poster="">
                    <source src="{{ json_decode(get_setting('home_slider_video_link'), true)[$key] }}" type="video/mp4">
                </video>
            </div>
        </section>
    @endforeach
@endif
 