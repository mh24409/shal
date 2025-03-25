<div class=" main-aiz-carousel z-3 row gutters-10 sticky-top product-details-image-gallery p-0 ">
    @if ($detailedProduct->photos != null || $detailedProduct->video_link != null )
        @php
            $photos = explode(',', $detailedProduct->photos);
        @endphp
        <!-- Gallery Images -->
        <div class="col-12 p-0">
            <div class="w-100 aiz-carousel main-aiz-carousel product-gallery arrow-inactive-transparent arrow-lg-none"
                data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='true' data-arrows='true'>
                @if($detailedProduct->photos != null)
                    <?php $imagesInArray = []; ?>
                    @php
                        $lastStockImage = 0;
                    @endphp
                    @if ($detailedProduct->digital == 0)
                        @foreach ($detailedProduct->stocks as $key => $stock)
                            @if ($stock->image != null && $stock->image != $lastStockImage)
                                <a data-src="{{ uploaded_asset($stock->image) }}" data-fancybox="image-fancy-box-main"
                                    class="carousel-box img-zoom rounded-0">
                                    <img class="img-fluid h-auto lazyload mx-auto"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($stock->image) }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </a>
                                <?php $lastStockImage = $stock->image; ?>
                                <?php array_push($imagesInArray, $stock->image); ?>
                            @endif
                        @endforeach
                    @endif
                    @foreach ($photos as $key => $photo)
                        @if (!in_array($photo, $imagesInArray))
                            <a data-src="{{ uploaded_asset($photo) }}" data-fancybox="image-fancy-box-main"
                                class="carousel-box img-zoom rounded-0">
                                <img class="img-fluid h-auto lazyload mx-auto"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset($photo) }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>
                        @endif
                    @endforeach
                @endif
                @if($detailedProduct->thumbnail_img)
                <a data-src="{{ uploaded_asset($detailedProduct->thumbnail_img) }}"
                    data-fancybox="image-fancy-box-main">
                    <img class="img-fluid h-auto lazyload mx-auto"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($detailedProduct->thumbnail_img) }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </a>
                @endif
                <div class="embed-responsive embed-responsive-16by9 carousel-box h-100  ">
                    @if ($detailedProduct->video_provider == 'youtube' && isset(explode('=', $detailedProduct->video_link)[1]))
                        <iframe class="embed-responsive-item"
                            src="https://www.youtube.com/embed/{{ get_url_params($detailedProduct->video_link, 'v') }}"></iframe>
                    @elseif ($detailedProduct->video_provider == 'dailymotion' && isset(explode('video/', $detailedProduct->video_link)[1]))
                        <iframe class="embed-responsive-item"
                            src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] }}"></iframe>
                    @elseif ($detailedProduct->video_provider == 'vimeo' && isset(explode('vimeo.com/', $detailedProduct->video_link)[1]))
                        <iframe
                            src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $detailedProduct->video_link)[1] }}"
                            width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                    @endif
                </div>


            </div>
        </div>
        <!-- Thumbnail Images -->
        <div class="col-12 mt-3 d-none d-lg-block">
            <div class="aiz-carousel main-aiz-carousel product-gallery-thumb" data-items='7'
                data-nav-for='.product-gallery' data-focus-select='true' data-arrows='true' data-vertical='false'
                data-auto-height='true'>
                @if($detailedProduct->photos != null)
                @php
                    $lastStockImage = 0;
                @endphp
                <?php $imagesInArray = []; ?>
                @if ($detailedProduct->digital == 0)
                    @foreach ($detailedProduct->stocks as $key => $stock)
                        @if ($stock->image != null && $stock->image != $lastStockImage)
                            class="carousel-box c-pointer rounded-0" data-variation="{{ $stock->image }}">
                            <img class="lazyload mw-100 w-60px mx-auto border p-1"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($stock->image) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            <?php $lastStockImage = $stock->image; ?>
                            <?php array_push($imagesInArray, $stock->image); ?>
                        @endif
                    @endforeach
                @endif

                @foreach ($photos as $key => $photo)
                    @if (!in_array($photo, $imagesInArray))
                        class="carousel-box c-pointer rounded-0">
                        <img class="lazyload mw-100 w-60px mx-auto border p-1"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src="{{ uploaded_asset($photo) }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    @endif
                @endforeach
                @endif
                @if($detailedProduct->thumbnail_img)
                <img class="lazyload mw-100 w-60px mx-auto border p-1"
                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                    data-src="{{ uploaded_asset($detailedProduct->thumbnail_img) }}"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                @endif($detailedProduct->thumbnail_img)
                @if ($detailedProduct->video_provider == 'youtube' && isset(explode('=', $detailedProduct->video_link)[1]))
                    <div class="carousel-box c-pointer rounded-0">
                        <img class="lazyload mw-100 w-60px mx-auto border p-1"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src="https://i.ytimg.com/vi/{{ get_url_params($detailedProduct->video_link, 'v') }}/maxresdefault.jpg">
                    </div>
                @elseif ($detailedProduct->video_provider == 'dailymotion' && isset(explode('video/', $detailedProduct->video_link)[1]))
                    <iframe class="embed-responsive-item"
                        src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] }}"></iframe>
                @elseif ($detailedProduct->video_provider == 'vimeo' && isset(explode('vimeo.com/', $detailedProduct->video_link)[1]))
                    <iframe
                        src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $detailedProduct->video_link)[1] }}"
                        width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen
                        allowfullscreen></iframe>
                @endif
                
            </div>
        </div>
    @endif
</div>
@foreach ($detailedProduct->stocks as $key => $stock)
    @if ($stock->image != null || $detailedProduct->photos != null )
    <div style="display:none"
            class=" aiz-carousel-{{ $stock->id }} z-3 row gutters-10 sticky-top product-details-image-gallery  p-0">
            @php
                $stock_photos = explode(',', $stock->image);
            @endphp
            <!-- Gallery Images -->
            <div class="col-12 p-0">
                <div class="w-100  aiz-carousel aiz-carousel-gallery-{{ $stock->id }} product-gallery arrow-inactive-transparent arrow-lg-none"
                    data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='true' data-arrows='true'>
                    @foreach ($stock_photos as $key => $stock_image)
                        <a onclick="fireThisFancyBox({{ $stock->id }})"
                            data-src="{{ uploaded_asset($stock_image) }}"
                            data-fancybox="image-fancy-box-{{ $stock->id }}"
                            class="carousel-box img-zoom rounded-0">
                            <img class="img-fluid h-auto lazyload mx-auto"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($stock_image) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </a>
                    @endforeach
                    <div class="embed-responsive embed-responsive-16by9 carousel-box h-100">
                        @if ($detailedProduct->video_provider == 'youtube' && isset(explode('=', $detailedProduct->video_link)[1]))
                            <iframe class="embed-responsive-item"
                                src="https://www.youtube.com/embed/{{ get_url_params($detailedProduct->video_link, 'v') }}"></iframe>
                        @elseif ($detailedProduct->video_provider == 'dailymotion' && isset(explode('video/', $detailedProduct->video_link)[1]))
                            <iframe class="embed-responsive-item"
                                src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] }}"></iframe>
                        @elseif ($detailedProduct->video_provider == 'vimeo' && isset(explode('vimeo.com/', $detailedProduct->video_link)[1]))
                            <iframe
                                src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $detailedProduct->video_link)[1] }}"
                                width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen
                                allowfullscreen></iframe>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Thumbnail Images -->
            <div class="col-12 mt-3 d-none d-lg-block">
                <div class="aiz-carousel aiz-carousel-gallery-thumb-{{ $stock->id }} product-gallery-thumb"
                    data-items='7' data-nav-for='.product-gallery' data-focus-select='true' data-arrows='true'
                    data-vertical='false' data-auto-height='true'> 
                    @foreach ($stock_photos as $key => $stock_image)
                        <img class="lazyload mw-100 w-60px mx-auto border p-1"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src="{{ uploaded_asset($stock_image) }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    @endforeach 
                    @if ($detailedProduct->video_provider == 'youtube' && isset(explode('=', $detailedProduct->video_link)[1]))
                        <div class="carousel-box c-pointer rounded-0">
                            <img class="lazyload mw-100 w-60px mx-auto border p-1"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="https://i.ytimg.com/vi/{{ get_url_params($detailedProduct->video_link, 'v') }}/maxresdefault.jpg">
                        </div>
                    @elseif ($detailedProduct->video_provider == 'dailymotion' && isset(explode('video/', $detailedProduct->video_link)[1]))
                        <iframe class="embed-responsive-item"
                            src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] }}"></iframe>
                    @elseif ($detailedProduct->video_provider == 'vimeo' && isset(explode('vimeo.com/', $detailedProduct->video_link)[1]))
                        <iframe
                            src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $detailedProduct->video_link)[1] }}"
                            width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endforeach
