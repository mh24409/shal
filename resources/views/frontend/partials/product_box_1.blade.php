    @php
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $cart = \App\Models\Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = Session()->get('temp_user_id');
            if ($temp_user_id) {
                $cart = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
            }
        }
        $cart_added = [];
        if (isset($cart) && count($cart) > 0) {
            $cart_added = $cart->pluck('product_id')->toArray();
        }
        if (count($product->stocks) > 1) {
            foreach ($product->stocks as $key => $stock) {
                if ($stock->default == 1) {
                    $default_variation_img = get_product_stock_img($product->id, $stock->variant);
                }
            }
        }
        $dis = true;
        $defaultFounded = false;
        foreach ($product->stocks as $stock) {
            if ($stock->default == 1) {
                $defaultFounded = true;
                if ($stock->qty > 0) {
                    $dis = true;
                } else {
                    $dis = false;
                }
            }
        }
        if ($defaultFounded == false) {
            if ($product->stocks[0]->qty > 0) {
                $dis = true;
            } else {
                $dis = false;
            }
        }
    @endphp
    <div class="aiz-card-box aiz-card-box-{{ $product->id }} position-relative h-auto bg-white hov-scale-img ">
        <div class="position-relative img-fit overflow-hidden text-center">
            <a href="{{ route('product', $product->slug) }}" class="product-a-image" >
                <!-- Image -->
                <img style="aspect-ratio: 3/2;
                        object-fit: contain;
                        height: 100%;" data-imgaFormVariation="{{ $uniqueID }}" width="100%" class=" lazyload mx-auto has-transition "
                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                    data-src="{{ uploaded_asset($default_variation_img ?? $product->thumbnail_img) }}"
                    alt="{{ $product->getTranslation('name') }}" title="{{ $product->getTranslation('name') }}"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            </a>
            <div class="absolute-top-left d-flex flex-column sm-gap " style="top: 10%;">
                @php
                    $qty = 0;
                    if ($product->variant_product) {
                        foreach ($product->stocks as $key => $stock) {
                            $qty += $stock->qty;
                        }
                    } else {
                        //$qty = $product->current_stock;
                        $qty = optional($product->stocks->first())->qty;
                    }
                @endphp
                @if ($qty <= $product->low_stock_quantity && $qty != 0)
                    <span class=" product_status_out_of_stock text-capitalize ">{{ translate('low Stock') }}</span>
                @endif

                @if ($qty == 0 && $product->back_order == 1)
                    <span class=" product_status_out_of_stock text-capitalize ">{{ translate('low Stock') }}</span>
                @endif

                @if ($qty == 0 && $product->back_order == 0)
                    <span class=" product_status_out_of_stock text-capitalize "
                        style="background-color: black !important ;color:white">{{ translate('out of stock') }}</span>
                @endif
                {{-- @if (discount_in_percentage($product) > 0)
                    <span class=" product_status_badge ">{{ discount_in_percentage($product) . '%' . ' ' . translate('discount') }}</span>
                @endif --}}
                @if (count(json_decode($product->colors)) > 0)
                    <span class="product_status_badge">{{ translate('have many colors') }}</span>
                @endif
                @if ($dis)
                    <span
                        class=" product_status_badge {{ 'check_stock_out_of_stock_' . $uniqueID }} {{ $dis ? 'd-none' : 'd-flex' }} ">{{ translate('Out of stock') }}</span>
                @endif
            </div>
            <div class="absolute-bottom-left addToCartPosition">
                <button title="{{ translate('Quick View') }}"
                    class="button-not-button d-flex justify-content-center align-items-center"
                    onclick="showAddToCartModal('{{ $product->id }}')">
                   <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     viewBox="0 0 256 256" style="enable-background:new 0 0 256 256;" xml:space="preserve" width="35px" height="35px">
    <style type="text/css">
        .st0{fill:#FFFFFF !important;}
        .st1{fill:#EAEAEA !important;}
        .st2{fill:#5B5B5B !important;}
    </style>
    <g>
        <g>
            <ellipse transform="matrix(0.7071 -0.7071 0.7071 0.7071 -53.0193 127.9999)" class="st0" cx="128" cy="128" rx="123.39" ry="123.39"/>
            <g>
                <path class="st1" d="M128,5.1c32.83,0,63.69,12.78,86.9,36c23.21,23.21,36,54.07,36,86.9s-12.78,63.69-36,86.9
                    c-23.21,23.21-54.07,36-86.9,36s-63.69-12.78-86.9-36c-23.21-23.21-36-54.07-36-86.9s12.78-63.69,36-86.9
                    C64.31,17.89,95.17,5.1,128,5.1 M128,4.1C59.57,4.1,4.1,59.57,4.1,128S59.57,251.9,128,251.9S251.9,196.43,251.9,128
                    S196.43,4.1,128,4.1L128,4.1z"/>
            </g>
        </g>
        <g>
            <g>
                <path class="st2" d="M155.62,149.68H85.25c-4.63,0-8.39-3.75-8.39-8.38c0-4.63,3.76-8.38,8.39-8.38h59.25l21.23-50.28h25.67
                    c4.63,0,8.38,3.75,8.38,8.38c0,4.63-3.75,8.38-8.38,8.38h-14.56L155.62,149.68z"/>
            </g>
            <g>
                <path class="st2" d="M105.35,168.91c0,7.41-6,13.41-13.4,13.41c-7.41,0-13.41-6-13.41-13.41s5.99-13.41,13.41-13.41
                    C99.35,155.5,105.35,161.51,105.35,168.91z"/>
            </g>
            <g>
                <path class="st2" d="M155.64,168.91c0,7.41-6,13.41-13.41,13.41c-7.41,0-13.41-6-13.41-13.41s6-13.41,13.41-13.41
                    C149.64,155.5,155.64,161.51,155.64,168.91z"/>
            </g>
            <polygon class="st2" points="134.14,121.38 81.33,121.38 65.18,82.63 151.33,82.63"/>
        </g>
    </g>
</svg>

                </button>
            </div>
            <!-- Discount percentage tag -->


            <!-- Wholesale tag -->
            {{-- @if ($product->wholesale_product)
                <span class="absolute-top-left fs-11 text-white fw-700 px-2 lh-1-8 ml-1 mt-1"
                    style="background-color: #455a64; @if (discount_in_percentage($product) > 0) top:25px; @endif">
                    {{ translate('Wholesale') }}
                </span>
            @endif --}}
            {{-- <div class="absolute-top-right d-flex flex-column sm-gap ">
                <button title="{{ translate('Buy Now') }}"
                    class="{{ 'check_stock_qty_buy_' . $uniqueID }} {{ $dis ? 'd-flex' : 'd-none' }} btn btn-dark px-2 py-1"
                    onclick="buyNowFromCard('{{ $uniqueID }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-bag" width="16"
                        height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M6.331 8h11.339a2 2 0 0 1 1.977 2.304l-1.255 8.152a3 3 0 0 1 -2.966 2.544h-6.852a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304z" />
                        <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
                    </svg>
                </button>
                <button title="{{ translate('Quick View') }}" class=" btn btn-dark px-2 py-1"
                    onclick="showAddToCartModal('{{ $product->id }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-plus" width="16"
                        height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M12 18c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                    </svg>
                </button>
            </div>
            <div class="d-flex justify-content-center absolute-bottom-left w-100">
                <button title="{{ translate('Add To Cart') }}"
                    class=" btn btn-dark {{ 'check_stock_qty_add_' . $uniqueID }} {{ $dis ? 'd-flex' : 'd-none' }} align-items-center sm-gap justify-content-center align-items-center "
                    onclick="addToCartFromCard('{{ $uniqueID }}')">
                    <span>{{ translate('Add To Cart') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart"
                        width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M17 17h-11v-14h-2" />
                        <path d="M6 5l14 1l-1 7h-13" />
                    </svg>
                </button>
                <button title="{{ translate('out of stock') }}"
                    class=" btn btn-dark {{ 'check_stock_out_of_stock_' . $uniqueID }} {{ $dis ? 'd-none' : 'd-flex' }} align-items-center sm-gap justify-content-center align-items-center "
                    {{ $dis ? '' : 'disabled' }}>
                    <span>{{ translate('out of stock') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart"
                        width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M17 17h-11v-14h-2" />
                        <path d="M6 5l14 1l-1 7h-13" />
                    </svg>
                </button>
            </div> --}}
        </div>

        <div class="p-3">
            <a href="{{ route('product', $product->slug) }}">
                <!-- Product name -->
                <h3 class="fw-400 fs-18 text-truncate-2 d-flex justify-content-start w-100 text-dark">
                    <strong>{{ $product->getTranslation('name') }}</strong>
                </h3>
            </a>
            <div class="d-lg-flex justify-content-start sm-gap">
                @if ($product->auction_product == 0)
                    <span class="fs-20  fw-700  product_card_price">{{ home_discounted_base_price($product) }}</span>
                    @if (home_base_price($product) != home_discounted_base_price($product))
                        <del class="fs-16  fw-400 text-secondary mr-1 d-flex align-items-center">{{ home_base_price($product) }}</del>
                    @endif
                @endif
                @if ($product->auction_product == 1)
                    <!-- Bid Amount -->
                    <div class="">
                        <span class="fs-20 fw-700 product_card_price">{{ single_price($product->starting_bid) }}</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="d-none">
            @if ($product->auction_product != 1)
                <form class="cart-option-choice-form" id="{{ $uniqueID }}">
                    <!-- Total Price -->

                    <div class="product-price d-flex align-items-center sm-gap w-100 justify-content-center mb-3 ">
                        <strong class="chosen_price fs-20 fw-700 text-primary ">
                            {{ format_price($product->stocks[0]->price ?? $product->unit_price) }}
                        </strong>
                    </div>
                    @csrf
                    <input data-FormId="{{ $uniqueID }}" type="hidden" name="id"
                        value="{{ $product->id }}">
                    @if ($product->digital == 0)
                        <!-- Color Options -->
                        @if (count(json_decode($product->colors)) > 0)
                            <div class=" no-gutters mb-3">
                                <div class=" ">
                                    <div class="aiz-radio-inline text-center">
                                        @foreach (json_decode($product->colors) as $key => $color)
                                            @php
                                                $name = get_colors_by_code($color);
                                            @endphp
                                            @if ($name != null)
                                                <label class="aiz-megabox " data-toggle="tooltip"
                                                    data-title="{{ $name }}">
                                                    <input data-FormId="{{ $uniqueID }}" type="radio"
                                                        name="color" value="{{ $name }}"
                                                        @if ( isset($product->default_variation[0]) && $product->default_variation[0] == $name) checked @endif>
                                                    <span
                                                        class="aiz-megabox-elem d-flex align-items-center justify-content-center p-1 {{ $color == 'FFFFFF#' ? 'border border-danger' : '' }} ">
                                                        <span class="size-25px d-inline-block  "
                                                            style="background: {{ $color }};border-radius: 50%;"></span>
                                                    </span>
                                                </label>
                                            @endif
                                        @endforeach


                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Choice Options -->
                        @if ($product->choice_options != null)
                            @foreach (json_decode($product->choice_options) as $key => $choice)
                                <div class="  no-gutters mb-3">
                                    <div class=" ">
                                        <div class="aiz-radio-inline text-center">
                                            @foreach ($choice->values as $key => $value)
                                                <label class="aiz-megabox ">
                                                    <input data-FormId="{{ $uniqueID }}" type="radio"
                                                        name="attribute_id_{{ $choice->attribute_id }}"
                                                        value="{{ $value }}"
                                                        @foreach ($product->default_variation as $key => $element)
                                                            @php
                                                            $pure =  str_replace(' ', '', $value)
                                                            @endphp
                                                        @if ($pure == $element) checked @endif @endforeach>
                                                    <span
                                                        class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center py-1 px-3">
                                                        {{ $value }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <!-- Quantity + Add to cart -->
                        <div class="no-gutters mb-3 d-none">
                            <div class=" ">
                                <div class="product-quantity  sm-gap quantity-card-md">
                                    <div class="row no-gutters align-items-center aiz-plus-minus" style="width: 130px;">
                                        <button data-FormId="{{ $uniqueID }}" data-changeType="minus"
                                            onclick="changeQuantityValue(this)"
                                            class="btn col-auto btn-icon btn-sm btn-primary rounded-0 minus-one-card"
                                            type="button">
                                            <i class="las la-minus"></i>
                                        </button>
                                        <input data-FormId="{{ $uniqueID }}" type="number" name="quantity"
                                            class="col border-0 text-center flex-grow-1 fs-16 card-input-number input-number"
                                            placeholder="1" value="{{ $product->min_qty }}"
                                            min="{{ $product->min_qty }}"
                                            max="{{ $product->stocks[0]->qty ?? $product->current_stock }}"
                                            lang="en">
                                        <button data-FormId="{{ $uniqueID }}" data-changeType="plus"
                                            onclick="changeQuantityValue(this)"
                                            class="btn col-auto btn-icon btn-sm btn-primary rounded-0 plus-one-card "
                                            type="button">
                                            <i class="las la-plus"></i>
                                        </button>
                                    </div>
                                    @php
                                        $qty = 0;
                                        foreach ($product->stocks as $key => $stock) {
                                            $qty += $stock->qty;
                                        }
                                    @endphp
                                    <div class="avialable-amount opacity-60">
                                        @if ($product->stock_visibility_state == 'quantity')
                                            (<span
                                                class="available-quantity">{{ $product->stocks[0]->qty ?? $product->current_stock }}</span>
                                            {{ translate('available') }})
                                        @elseif($product->stock_visibility_state == 'text' && $qty >= 1)
                                            (<span class="available-quantity">{{ translate('In Stock') }}</span>)
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </form>
            @endif
        </div>
    </div>
