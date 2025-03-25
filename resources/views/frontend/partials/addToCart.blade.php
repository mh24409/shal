<div class="modal-body px-4 py-5 c-scrollbar-light">
    <div class=" ">
        <div>
            <div class=" main-aiz-carousel z-3 row gutters-10 sticky-top product-details-image-gallery ">
    @if ($product->photos != null)
        @php
            $photos = explode(',', $product->photos);
        @endphp 
        <div class="col-12 slides-margin"">
            <div class="w-100 aiz-carousel main-aiz-carousel product-gallery arrow-inactive-transparent arrow-lg-none"
                data-nav-for='.product-gallery-thumb' data-fade='false'
                data-auto-height='true' data-items="2" data-xl-items="2" data-lg-items="2" data-md-items="1"
                data-sm-items="1" data-xs-items="1" data-arrows='true' data-infinite='false'>
                <?php $imagesInArray = []; ?>
                @php
                    $lastStockImage = 0;
                @endphp
                @if ($product->digital == 0)
                    @foreach ($product->stocks as $key => $stock)
                        @if ($stock->image != null && $stock->image != $lastStockImage)
                            <a data-src="{{ uploaded_asset($stock->image) }}" data-fancybox="image-fancy-box"
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
                        <a data-src="{{ uploaded_asset($photo) }}" data-fancybox="image-fancy-box"
                            class="carousel-box img-zoom rounded-0">
                            <img class="img-fluid h-auto lazyload mx-auto"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($photo) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </a>
                    @endif
                @endforeach
                <a data-src="{{ uploaded_asset($product->thumbnail_img) }}" data-fancybox="image-fancy-box">
                    <img class="img-fluid h-auto lazyload mx-auto"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </a>
                <div class="embed-responsive embed-responsive-16by9 carousel-box h-100  ">
                    @if ($product->video_provider == 'youtube' && isset(explode('=', $product->video_link)[1]))
                        <iframe class="embed-responsive-item"
                            src="https://www.youtube.com/embed/{{ get_url_params($product->video_link, 'v') }}"></iframe>
                    @elseif ($product->video_provider == 'dailymotion' && isset(explode('video/', $product->video_link)[1]))
                        <iframe class="embed-responsive-item"
                            src="https://www.dailymotion.com/embed/video/{{ explode('video/', $product->video_link)[1] }}"></iframe>
                    @elseif ($product->video_provider == 'vimeo' && isset(explode('vimeo.com/', $product->video_link)[1]))
                        <iframe
                            src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $product->video_link)[1] }}"
                            width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                    @endif
                </div>


            </div>
        </div>
        <!-- Thumbnail Images -->
        <div class="col-12 mt-3 d-none ">
            <div class="aiz-carousel main-aiz-carousel product-gallery-thumb" data-items='7'
                data-nav-for='.product-gallery' data-focus-select='true' data-arrows='true' data-vertical='false'
                data-auto-height='true'>
                @php
                    $lastStockImage = 0;
                @endphp
                <?php $imagesInArray = []; ?>
                @if ($product->digital == 0)
                    @foreach ($product->stocks as $key => $stock)
                        @if ($stock->image != null && $stock->image != $lastStockImage)
                            <a data-src="{{ uploaded_asset($stock->image) }}" data-fancybox="image-fancy-box"
                                class="carousel-box c-pointer rounded-0" data-variation="{{ $stock->image }}">
                                <img class="lazyload mw-100 w-60px mx-auto border p-1"
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
                        <a data-src="{{ uploaded_asset($photo) }}" data-fancybox="image-fancy-box"
                            class="carousel-box c-pointer rounded-0">
                            <img class="lazyload mw-100 w-60px mx-auto border p-1"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($photo) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </a>
                    @endif
                @endforeach
                <a data-src="{{ uploaded_asset($product->thumbnail_img) }}" data-fancybox="image-fancy-box">
                    <img class="lazyload mw-100 w-60px mx-auto border p-1"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </a>
                @if ($product->video_provider == 'youtube' && isset(explode('=', $product->video_link)[1]))
                    <div class="carousel-box c-pointer rounded-0">
                        <img class="lazyload mw-100 w-60px mx-auto border p-1"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src="https://i.ytimg.com/vi/{{ get_url_params($product->video_link, 'v') }}/maxresdefault.jpg">
                    </div>
                @elseif ($product->video_provider == 'dailymotion' && isset(explode('video/', $product->video_link)[1]))
                    <iframe class="embed-responsive-item"
                        src="https://www.dailymotion.com/embed/video/{{ explode('video/', $product->video_link)[1] }}"></iframe>
                @elseif ($product->video_provider == 'vimeo' && isset(explode('vimeo.com/', $product->video_link)[1]))
                    <iframe src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $product->video_link)[1] }}"
                        width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen
                        allowfullscreen></iframe>
                @endif
            </div>
        </div>
    @endif
</div>
@foreach ($product->stocks as $key => $stock)
    @if ($stock->image != null)
        <div style="display:none"
            class=" aiz-carousel-{{ $stock->id }} z-3 row gutters-10 sticky-top product-details-image-gallery ">
            @php
                $stock_photos = explode(',', $stock->image);
            @endphp
            <!-- Gallery Images -->
            <div class="col-12  slides-margin"">
                <div class="w-100  aiz-carousel aiz-carousel-gallery-{{ $stock->id }} product-gallery arrow-inactive-transparent arrow-lg-none"
                    data-nav-for='.product-gallery-thumb' data-fade='false'
                    data-auto-height='true' data-items="2" data-xl-items="2" data-lg-items="2" data-md-items="1"
                    data-sm-items="1" data-xs-items="1" data-arrows='true' data-infinite='false'>
                    @foreach ($stock_photos as $key => $stock_image)
                        <a data-src="{{ uploaded_asset($stock_image) }}" data-fancybox="image-fancy-box"
                            class="carousel-box img-zoom rounded-0">
                            <img class="img-fluid h-auto lazyload mx-auto"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($stock_image) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </a>
                    @endforeach
                    <a data-src="{{ uploaded_asset($product->thumbnail_img) }}" data-fancybox="image-fancy-box">
                        <img class="img-fluid h-auto lazyload mx-auto"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </a>
                    <div class="embed-responsive embed-responsive-16by9 carousel-box h-100  ">
                        @if ($product->video_provider == 'youtube' && isset(explode('=', $product->video_link)[1]))
                            <iframe class="embed-responsive-item"
                                src="https://www.youtube.com/embed/{{ get_url_params($product->video_link, 'v') }}"></iframe>
                        @elseif ($product->video_provider == 'dailymotion' && isset(explode('video/', $product->video_link)[1]))
                            <iframe class="embed-responsive-item"
                                src="https://www.dailymotion.com/embed/video/{{ explode('video/', $product->video_link)[1] }}"></iframe>
                        @elseif ($product->video_provider == 'vimeo' && isset(explode('vimeo.com/', $product->video_link)[1]))
                            <iframe
                                src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $product->video_link)[1] }}"
                                width="500" height="281" frameborder="0" webkitallowfullscreen
                                mozallowfullscreen allowfullscreen></iframe>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Thumbnail Images -->
            <div class="col-12 mt-3 d-none ">
                <div class="aiz-carousel aiz-carousel-gallery-thumb-{{ $stock->id }} product-gallery-thumb"
                    data-items='7' data-nav-for='.product-gallery' data-focus-select='true' data-arrows='true'
                    data-vertical='false' data-auto-height='true'>
                    @foreach ($stock_photos as $key => $stock_image)
                        <a data-src="{{ uploaded_asset($stock_image) }}" data-fancybox="image-fancy-box"
                            class="carousel-box c-pointer rounded-0">
                            <img class="lazyload mw-100 w-60px mx-auto border p-1"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($stock_image) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </a>
                    @endforeach
                    @if ($product->video_provider == 'youtube' && isset(explode('=', $product->video_link)[1]))
                        <div class="carousel-box c-pointer rounded-0">
                            <img class="lazyload mw-100 w-60px mx-auto border p-1"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="https://i.ytimg.com/vi/{{ get_url_params($product->video_link, 'v') }}/maxresdefault.jpg">
                        </div>
                        <a data-src="{{ uploaded_asset($product->thumbnail_img) }}" data-fancybox="image-fancy-box">
                            <img class="lazyload mw-100 w-60px mx-auto border p-1"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </a>
                    @elseif ($product->video_provider == 'dailymotion' && isset(explode('video/', $product->video_link)[1]))
                        <iframe class="embed-responsive-item"
                            src="https://www.dailymotion.com/embed/video/{{ explode('video/', $product->video_link)[1] }}"></iframe>
                    @elseif ($product->video_provider == 'vimeo' && isset(explode('vimeo.com/', $product->video_link)[1]))
                        <iframe
                            src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $product->video_link)[1] }}"
                            width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endforeach

        </div>
        <div class="mt-4"> <!-- Product name --> <a href="{{ route('product', $product->slug) }}"
                class="h5 fs-20 fw-700 mb-0 text-capitalize"> {{ $product->getTranslation('name') }} </a>
            {{-- rating --}} <div> {{ renderStarRating($product->rating) }} </div>
            <!-- Product Price & Club Point -->
            @if (home_price($product) != home_discounted_price($product))
                <div class="row no-gutters mt-3">
                    <div class=""> <strong class="fs-16 fw-700 text-primary">
                            {{ home_discounted_price($product) }} </strong> <del class="fs-14 opacity-60 ml-2">
                            {{ home_price($product) }} </del>
                        @if (discount_in_percentage($product) > 0)
                            <span class="bg-primary ml-2 fs-11 fw-700 text-white w-35px text-center px-2"
                                style="padding-top:2px;padding-bottom:2px;">-{{ discount_in_percentage($product) }}%</span>
                            @endif @if ($product->unit != null)
                                <span class="opacity-70 ml-1 d-none">/{{ $product->getTranslation('unit') }}</span>
                                @endif <!-- Club Point -->
                                @if (addon_is_activated('club_point') && $product->earn_point > 0)
                                    <div class="mt-2 bg-warning d-flex justify-content-center align-items-center px-3 py-1"
                                        style="width: fit-content;"> <svg xmlns="http://www.w3.org/2000/svg"
                                            width="12" height="12" viewBox="0 0 12 12">
                                            <g id="Group_23922" data-name="Group 23922"
                                                transform="translate(-973 -633)">
                                                <circle id="Ellipse_39" data-name="Ellipse 39" cx="6"
                                                    cy="6" r="6" transform="translate(973 633)"
                                                    fill="#fff" />
                                                <g id="Group_23920" data-name="Group 23920"
                                                    transform="translate(973 633)">
                                                    <path id="Path_28698" data-name="Path 28698"
                                                        d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)"
                                                        fill="#f3af3d" />
                                                    <path id="Path_28699" data-name="Path 28699"
                                                        d="M5.33,3h-1L3,5,6,9,4.331,5Z" transform="translate(0 0)"
                                                        fill="#f3af3d" opacity="0.5" />
                                                    <path id="Path_28700" data-name="Path 28700"
                                                        d="M12.666,3h1L15,5,12,9l1.664-4Z"
                                                        transform="translate(-5.995 0)" fill="#f3af3d" />
                                                </g>
                                            </g>
                                        </svg> <small
                                            class="fs-11 fw-500 text-white ml-2">{{ translate('Club Point') }}:
                                            {{ $product->earn_point }}</small> </div>
                                @endif
                    </div>
                </div>
            @else
                <div class="row no-gutters mt-3">
                    <div class=""> <strong class="fs-16 fw-700 text-primary">
                            {{ home_discounted_price($product) }} </strong>
                        @if ($product->unit != null)
                            <span class="opacity-70 d-none">/{{ $product->unit }}</span>
                            @endif
                    </div>
                </div> @endif <!-- Product name -->
                <p class="mb-2 fs-16 fw-700 text-mute"> <?php echo $product->getTranslation('description'); ?> </p>
                <div class="row no-gutters mb-3">
                    <div class="col-sm-10">
                        <div class="product-price"> <span class="opacity-50 fs-20 suits"> </span> </div>
                    </div>
                </div> @php
                    $qty = 0;
                    foreach ($product->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                @endphp <!-- Product Choice options form -->
                <form id="option-choice-form"> <input type="hidden" name="_token" id="buy_now_token"> @CSRF <input
                        type="hidden" name="id" value="{{ $product->id }}">
                    @if ($product->digital != 1) <!-- Product Choice options -->
                        @if ($product->choice_options != null)
                            @foreach (json_decode($product->choice_options) as $key => $choice)
                                <div class="row no-gutters mt-3">
                                    <div class="col-3">
                                        <div class="text-secondary fs-14 fw-400 mt-2 ">
                                            {{ \App\Models\Attribute::find($choice->attribute_id)->getTranslation('name') }}
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="aiz-radio-inline">
                                            @foreach ($choice->values as $key => $value)
                                                <label class="aiz-megabox pl-0 mr-2 mb-0 attribute-megabox"
                                                id="choice_attribute_id-{{ \App\Models\AttributeValue::where('attribute_id',$choice->attribute_id)->where('value',$value)->first()->id }}"> <input type="radio"
                                                        name="attribute_id_{{ $choice->attribute_id }}"
                                                        value="{{ $value }}"
                                                        @if ($key == 0) checked @endif> <span
                                                        class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center py-1 px-3">
                                                        {{ $value }} </span> </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach @endif <!-- Color -->
                                @if (count(json_decode($product->colors)) > 0)
                                    <div class="row no-gutters mt-3">
                                        <div class="col-3">
                                            <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Color') }}
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="aiz-radio-inline">
                                                @foreach (json_decode($product->colors) as $key => $color)
                                                 <?php
                                                    $current_color_id = \App\Models\Color::where('code', $color)->first()->id;
                                                    $color_qty = \App\Models\ProductStock::where('product_id', $product->id)->where('color_id', $current_color_id)->sum('qty');
                                                    ?>
                                                    <label class="aiz-megabox pl-0 mr-2 mb-0 {{ $color_qty < 1 && $product->back_order != 1 ? 'disabled-choice' : '' }}" data-toggle="tooltip"
                                                        data-title="{{ \App\Models\Color::where('code', $color)->first()->name }}">
                                                        <input type="radio" name="color"
                                                            value="{{ \App\Models\Color::where('code', $color)->first()->name }}"
                                                            @if ($key == 0) checked @endif> <span
                                                            class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center p-1 {{ $color == 'FFFFFF#' ? 'border border-danger' : '' }}">
                                                            <span class="size-25px d-inline-block rounded"
                                                                style="background: {{ $color }};"></span>
                                                        </span>
                                                    </label>
                                                    @endforeach
                                            </div>
                                        </div>
                                    </div> @endif <!-- Quantity -->
                                    <div class="row no-gutters mt-3 d-none">
                                        <div class="col-3">
                                            <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Quantity') }}
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="product-quantity d-flex align-items-center">
                                                <div class="row no-gutters align-items-center aiz-plus-minus mr-3"
                                                    style="width: 130px;"> <button
                                                        class="btn col-auto btn-icon btn-sm btn-light rounded"
                                                        type="button" data-type="minus" data-field="quantity"
                                                        disabled=""> <i class="las la-minus"></i> </button> <input
                                                        type="number" name="quantity"
                                                        class="col border-0 text-center flex-grow-1 fs-16 input-number"
                                                        placeholder="1" value="{{ $product->min_qty }}"
                                                        min="{{ $product->min_qty }}" max="10" lang="en">
                                                    <button class="btn col-auto btn-icon btn-sm btn-light rounded"
                                                        type="button" data-type="plus" data-field="quantity"> <i
                                                            class="las la-plus"></i> </button> </div>
                                                <div class="avialable-amount opacity-60">
                                                    @if ($product->stock_visibility_state == 'quantity')
                                                        (<span id="available-quantity">{{ $qty }}</span>
                                                        {{ translate('available') }})
                                                    @elseif($product->stock_visibility_state == 'text' && $qty >= 1)
                                                        (<span
                                                            id="available-quantity">{{ translate('In Stock') }}</span>)
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div> @endif <!-- Total Price -->
                                    <div class="row no-gutters mt-3 pb-3 d-none" id="chosen_price_div">
                                        <div class="col-3">
                                            <div class="text-secondary fs-14 fw-400 mt-1">
                                                {{ translate('Total Price') }}</div>
                                        </div>
                                        <div class="col-9">
                                            <div class="product-price"> <strong id="chosen_price"
                                                    class="fs-20 fw-700 text-primary"> </strong> </div>
                                        </div>
                                    </div>
                </form> <!-- Add to cart -->
                <div class="d-flex justify-content-between sm-gap align-items-center">
                    @if ($product->digital == 1) <button type="button"
                            class="check_stock_qty_add main_add_to_cart_button btn btn-primary rounded buy-now fw-600 add-to-cart w-50"
                            onclick="addToCart()"> <span class="d-block">{{ translate('Add to cart') }}</span>
                        </button> <button type="submit" onclick="buyNowShop()"
                            class="check_stock_qty_buy btn btn-secondary m-auto rounded buy-now fw-600 add-to-cart  w-50">
                            <i class="la la-shopping-cart d-none d-lg-block"></i> </button>
                    @elseif($qty > 0 || $product->back_order == 1 )
                        @if ($product->external_link != null)
                            <a type="button" class="btn btn-soft-primary rounded mr-2 add-to-cart fw-600"
                                href="{{ $product->external_link }}"> <i class="las la-share"></i> <span
                                    class="d-none d-md-inline-block">{{ translate($product->external_link_btn) }}</span>
                            </a>
                        @else
                            <button type="button"
                                class="check_stock_qty_add main_add_to_cart_button btn btn-primary rounded buy-now fw-600 add-to-cart  w-50"
                                onclick="addToCart()"> <span class=" d-block">{{ translate('Add to cart') }}</span>
                            </button> <button type="submit" onclick="buyNowShop()"
                                class="check_stock_qty_buy btn main_buy_now_button btn-secondary m-auto rounded buy-now fw-600 add-to-cart  w-50">
                                <span class="d-inline-block">{{ translate('Buy Now') }}</span> </button>
                        @endif
                    @endif <button type="button"
                        class="btn btn-secondary rounded out-of-stock fw-600 d-none check_stock_out_of_stock" disabled>
                        <i class="la la-cart-arrow-down"></i>{{ translate('Out of Stock') }} </button>
                </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#option-choice-form input').on('change', function() {
        getVariantPrice();
    });
</script>
