@if (get_setting('home_banner1_images') != null)
@foreach (json_decode(get_setting('home_banner1_images'), true) as $key => $value)
    <div class="container-fluid  p-0">
       <div class="container" >
           <div class="position-relative d-flex align-items-center sm-gap banner1_container"  >
              <div class="animated-banner-text">
                   {!! get_setting('home_banner1_text') !!}
             </div>
              <div class="px-4 mobile-absolute-right--1" >
                  <img class="animated-banner-img" src="{{ uploaded_asset(json_decode(get_setting('home_banner1_images'), true)[$key]) }}" >
              </div>
          </div>

       </div>
    </div>
@endforeach
@endif
