<div>
    <!-- Product Name -->
    <h1 class="mb-4 fs-26 fw-700 text-dark d-inline">
        {{ $detailedProduct->getTranslation('name') }}
    </h1>
    <!-- For auction product -->
    @if ($detailedProduct->auction_product)
        <div class="py-2">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Auction Will End') }}</div>
            </div>
            <div class="col-sm-10">
                @if ($detailedProduct->auction_end_date > strtotime('now'))
                    <div class="aiz-count-down align-items-center"
                        data-date="{{ date('Y/m/d H:i:s', $detailedProduct->auction_end_date) }}"></div>
                @else
                    <p>{{ translate('Ended') }}</p>
                @endif
            </div>
        </div>
        <div class="py-2">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Starting Bid') }}</div>
            </div>
            <div class="col-sm-10">
                <span class="opacity-50 fs-20">
                    {{ single_price($detailedProduct->starting_bid) }}
                </span>
                @if ($detailedProduct->unit != null)
                    <span class="opacity-70">/{{ $detailedProduct->getTranslation('unit') }}</span>
                @endif
            </div>
        </div>
        @if (Auth::check() &&
                Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
            <div class="py-2">
                <div class="col-sm-2">
                    <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('My Bidded Amount') }}</div>
                </div>
                <div class="col-sm-10">
                    <span class="opacity-50 fs-20">
                        {{ single_price(Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first()->amount) }}
                    </span>
                </div>
            </div>
        @endif
        @php $highest_bid = $detailedProduct->bids->max('amount'); @endphp
        <div class="row no-gutters my-2 mb-3">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Highest Bid') }}</div>
            </div>
            <div class="col-sm-10">
                <strong class="h3 fw-600 text-primary">
                    @if ($highest_bid != null)
                        {{ single_price($highest_bid) }}
                    @endif
                </strong>
            </div>
        </div>
    @else
        @if ($detailedProduct->wholesale_product == 1)
            <table class="table mb-3">
                <thead>
                    <tr>
                        <th class="border-top-0">{{ translate('Min Qty') }}</th>
                        <th class="border-top-0">{{ translate('Max Qty') }}</th>
                        <th class="border-top-0">{{ translate('Unit Price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailedProduct->stocks->first()->wholesalePrices as $wholesalePrice)
                        <tr>
                            <td>{{ $wholesalePrice->min_qty }}</td>
                            <td>{{ $wholesalePrice->max_qty }}</td>
                            <td>{{ single_price($wholesalePrice->price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            @if (home_price($detailedProduct) != home_discounted_price($detailedProduct))
                <div class="py-2">
                    <div>
                        <div class="price-discounted-clubpoint d-block ">
                            <div>
                                <strong class="fs-24 fw-700 text-primary product_card_price">
                                    {{ home_discounted_price($detailedProduct) }}

                                </strong>
                            </div>
                            <div>
                                <del class="fs-18 fw-700 font-weight-bold ml-2">
                                    {{ home_price($detailedProduct) }}
                                </del>
                            </div>
                            <div class=" justify-content-center align-items-center d-none">
                                <!-- Discount percentage -->
                                @if (discount_in_percentage($detailedProduct) > 0)
                                    <span
                                        class="product_status_badge ">-{{ discount_in_percentage($detailedProduct) }}%</span>
                                @endif
                                <!-- Club Point -->
                                @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                                    <div class="ml-2 bg-light d-flex justify-content-center align-items-center px-3 py-1"
                                        style="width: fit-content;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            viewBox="0 0 12 12">
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
                                        </svg>
                                        <small class="fs-11 fw-500 text-dark ml-2">
                                            {{ translate('Club Point') }}: {{ $detailedProduct->earn_point }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="py-2">
                    <div class="col-sm-10">
                        <div class="price-discounted-clubpoint">
                            <!-- Discount Price -->
                            <strong class="fs-24 fw-700 text-primary product_card_price">
                                {{ home_discounted_price($detailedProduct) }}
                            </strong>
                            <!-- Unit -->
                            {{-- @if ($detailedProduct->unit != null)
                                <span class="opacity-70">/{{ $detailedProduct->getTranslation('unit') }}</span>
                            @endif --}}
                            <!-- Club Point -->
                            @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                                <div class="ml-2 bg-light d-flex justify-content-center align-items-center px-3 py-1"
                                    style="width: fit-content;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        viewBox="0 0 12 12">
                                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                                            <circle id="Ellipse_39" data-name="Ellipse 39" cx="6" cy="6"
                                                r="6" transform="translate(973 633)" fill="#fff" />
                                            <g id="Group_23920" data-name="Group 23920" transform="translate(973 633)">
                                                <path id="Path_28698" data-name="Path 28698"
                                                    d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)"
                                                    fill="#f3af3d" />
                                                <path id="Path_28699" data-name="Path 28699"
                                                    d="M5.33,3h-1L3,5,6,9,4.331,5Z" transform="translate(0 0)"
                                                    fill="#f3af3d" opacity="0.5" />
                                                <path id="Path_28700" data-name="Path 28700"
                                                    d="M12.666,3h1L15,5,12,9l1.664-4Z" transform="translate(-5.995 0)"
                                                    fill="#f3af3d" />
                                            </g>
                                        </g>
                                    </svg>
                                    <small class="fs-11 fw-500 text-dark ml-2">{{ translate('Club Point') }}:
                                        {{ $detailedProduct->earn_point }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endif
    <div class="fs-13 fw-400">
        {{ translate('Taxes included') }}
    </div>
    <div style="font-size: 16px;" class=" mt-2 mb-3  fw-700">
        {{ translate('Buy it now and pay later') }}
    </div>
    {{-- product ads  --}}
    <div id="tamara">
        @include('frontend.partials.tamara_widget', [
            'price_to_widget' => home_discounted_base_price($detailedProduct),
        ])
    </div>
    <div class="row align-items-center py-2 d-none">
        @if ($detailedProduct->auction_product != 1)
            <div class="col-md-6  mb-3 ">
                @php
                    $total = 0;
                    $total += $detailedProduct->reviews->count();
                @endphp
                <span class="rating rating-mr-1">
                    {{ renderStarRating($detailedProduct->rating) }}
                </span>
                <span class="ml-1 opacity-50 fs-14">({{ $total }}
                    {{ translate('reviews') }})</span>
            </div>
        @endif
        <div class="col-md-6 mb-3">
            @if ($detailedProduct->auction_product != 1)
                <div class="d-flex">
                    <!-- Add to wishlist button -->
                    <a href="javascript:void(0)" onclick="addToWishList({{ $detailedProduct->id }})"
                        class="mr-3 fs-14 text-dark opacity-60 has-transitiuon hov-opacity-100">
                        <i class="la la-heart-o mr-1"></i>
                        {{ translate('Add to Wishlist') }}
                    </a>
                    <!-- Add to compare button -->
                    <a href="javascript:void(0)" onclick="addToCompare({{ $detailedProduct->id }})"
                        class="fs-14 text-dark opacity-60 has-transitiuon hov-opacity-100">
                        <i class="las la-sync mr-1"></i>
                        {{ translate('Add to Compare') }}
                    </a>
                </div>
            @endif
        </div>

    </div>

    @if (get_setting('counter_end') > 0 && $detailedProduct->counter_down == 1)
        @php
            $business_settings = App\Models\BusinessSetting::where('type', 'counter_end')->first();
            $currentDate = new DateTime();
            $providedDate = new DateTime($business_settings->updated_at);

            // Compare the current date with the provided date
            if ($currentDate > $providedDate) {
                $providedDate->add(new DateInterval('PT' . $business_settings->value . 'H'));
            }

            // Get the updated date string
            $updatedDateString = $providedDate->format('Y-m-d H:i:s');
            $business_settings->updated_at = $updatedDateString;
            $business_settings->save();

        @endphp
        <div class="container mt-5">
            <h5>{{ translate('flash ended at :') }}</h5>

            <div class="large-container">
                <div class="small-square" id="days">
                    <div id="days-value">00</div>
                    <div>{{ translate('Days') }}</div>
                </div>
                <div class="small-square" id="hours">
                    <div id="hours-value">00</div>
                    <div>{{ translate('Hours') }}</div>
                </div>
                <div class="small-square" id="minutes">
                    <div id="minutes-value">00</div>
                    <div>{{ translate('Minutes') }}</div>
                </div>
                <div class="small-square" id="seconds">
                    <div id="seconds-value">00</div>
                    <div>{{ translate('Seconds') }}</div>
                </div>
            </div>

        </div>
    @endif
    <div class="row no-gutters py-2 d-none">
        <div class="col-sm-10">
            <span class="opacity-50 fs-20">
                <?php echo $detailedProduct->getTranslation('description'); ?>
            </span>
        </div>
    </div>

    <div style="background-color: white; padding: 15px 10px; border-radius: var(--border-raduis);">
        <small style="font-size: 16px;" class=" fw-700">{{ translate('Delivered to your door in') }}</small>
        <span style="font-size: 16px;" class="  fw-700">
            <?php \Carbon\Carbon::setLocale('ar'); ?>
            {{ \Carbon\Carbon::now()->addDays(3)->translatedFormat('j F') }}
        </span>
    </div>

    @if (home_price($detailedProduct) != home_discounted_price($detailedProduct))
        @php
            $base = home_base_price($detailedProduct, false);
            $reduced = home_discounted_base_price($detailedProduct, false);
            $discount_value = $base - $reduced;
        @endphp
        <div class="fs-20 fw-700  ">
            <span class="text-gray"> {{ translate('save_slug') }}</span> <span class="text-primary"
                id="selected_product_discount_value"> {{ single_price($discount_value) }}</span>
        </div>
    @endif
    @if ($detailedProduct->auction_product != 1)
        <form class="mt-2" id="option-choice-form">
            @csrf
            <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

            @if ($detailedProduct->digital == 0)
                <!-- Choice Options -->
                @if ($detailedProduct->choice_options != null)
                    @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
                        <div class="d-flex align-items-center">
                            <div class="h5 fs-18 fw-700 mb-2 text-capitalize" style="text-wrap: nowrap;">
                                {{ \App\Models\Attribute::find($choice->attribute_id)->getTranslation('name') }} :
                            </div>
                            <div class="aiz-radio-inline mx-2">
                                @foreach ($choice->values as $key => $value)
                                    <label class="aiz-megabox my-2 attribute-megabox "
                                        id="choice_attribute_id-{{ \App\Models\AttributeValue::where('attribute_id',$choice->attribute_id)->where('value',$value)->first()->id }}">
                                        <input type="radio" id="choice_input_id-{{ $choice->attribute_id }}"
                                            name="attribute_id_{{ $choice->attribute_id }}"
                                            value="{{ $value }}"
                                            @foreach ($detailedProduct->default_variation as $key => $element)
                                                    @php
                                                     $pure =  str_replace(' ', '', $value)
                                                    @endphp
                                                    @if ($pure == $element) checked @endif
                                            @endforeach>
                                        <span
                                            class="aiz-megabox-elem  d-flex align-items-center justify-content-center w-35px rounded-0 py-1 px-1 mx-2">
                                            {{ $value }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif 
                <!-- Color Options -->
                @if (count(json_decode($detailedProduct->colors)) > 0)
                    <div class="py-2">
                        <div class="h5 fs-14 fw-700 mb-2 text-capitalize">{{ translate('Color') }}:</div>
                        <div class="aiz-radio-inline">
                            @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                <?php
                                $current_color_id = \App\Models\Color::where('code', $color)->first()->id;
                                $color_qty = \App\Models\ProductStock::where('product_id', $detailedProduct->id)->where('color_id', $current_color_id)->sum('qty');
                                ?>
                                <label
                                    class="aiz-megabox my-2 {{ $color_qty < 1 && $detailedProduct->back_order != 1 ? 'disabled-choice' : '' }} "
                                    data-toggle="tooltip"
                                    data-title="{{ \App\Models\Color::where('code', $color)->first()->name }}">
                                     
                                    <input type="radio" name="color"
                                        value="{{ \App\Models\Color::where('code', $color)->first()->name }}"
                                        @if (  isset($detailedProduct->default_variation[0]) && $detailedProduct->default_variation[0] == \App\Models\Color::where('code', $color)->first()->name) checked @endif>
                                    <span
                                        class="aiz-megabox-elem  d-flex align-items-center justify-content-center rounded-0 py-1 px-1 mx-2 overflow-hidden">
                                        <span class="size-25px d-inline-block"
                                            style="background: {{ $color }};"></span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
                @php
                    $qty = 0;
                    foreach ($detailedProduct->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                @endphp
                <div class="avialable-amount h5 fs-15 fw-700 mt-2 text-capitalize d-none">
                    @if ($detailedProduct->stock_visibility_state == 'quantity')
                        <span class="h5 fs-15 fw-700 mb-0 text-capitalize"
                            id="available-quantity">{{ $qty }}</span>
                        {{ translate('available In Stock') }}
                    @elseif($detailedProduct->stock_visibility_state == 'text' && $qty >= 1)
                        <span class="h5 fs-15 fw-700 mb-0 text-capitalize"
                            id="available-quantity">{{ translate('available In Stock') }}</span>
                    @endif
                </div>
                <!-- Total Price -->
                <div class="row no-gutters  d-none " style="margin: 30px 0px" id="chosen_price_div">
                    <div class="col-sm-2">
                        <div class="h5 fs-18 fw-700 mb-2 text-capitalize">{{ translate('Your Price') }}</div>
                    </div>
                    <div class="col-sm-10">
                        <div class="product-price">
                            <strong id="chosen_price" class="h5 fs-20 mb-2 fw-700 text-primary">

                            </strong>
                            <span class="fs-12 fw-400 text-dark">
                                {{ translate('Taxes included') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row quantity-and-place-buttons">
                    <div class="col-md-4 mb-4  d-none">
                        <!-- Quantity   -->
                        <div class="product-quantity d-flex align-items-center justify-content-center"
                            style="height: 48px;
                        border: solid #d4cdcdb8 0.5px;
                        width: 200px;">
                            <div class="row no-gutters align-items-center aiz-plus-minus w-100 h-100">
                                <button
                                    style="border-left: solid #d4cdcdb8 0.5px; border-radius: 0px !important;width: 25%;"
                                    class="btn col-auto btn-icon btn-sm btn-qty h-100" type="button"
                                    data-type="minus" data-field="quantity" disabled="">
                                    <i class="las la-minus"></i>
                                </button>
                                <input type="number" name="quantity"
                                    class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1"
                                    value="{{ $detailedProduct->min_qty }}" min="{{ $detailedProduct->min_qty }}"
                                    max="10" lang="en">
                                <button
                                    style="border-left: solid #d4cdcdb8 0.5px; border-radius: 0px !important;width: 25%;"
                                    class="btn col-auto btn-icon btn-sm btn-qty h-100  " type="button"
                                    data-type="plus" data-field="quantity">
                                    <i class="las la-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- add to cart and buy  --}}
                    @if ($detailedProduct->auction_product)
                        @php
                            $highest_bid = $detailedProduct->bids->max('amount');
                            $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $detailedProduct->starting_bid;
                        @endphp
                        @if ($detailedProduct->auction_end_date >= strtotime('now'))
                            <div class=" ">
                                @if (Auth::check() && $detailedProduct->user_id == Auth::user()->id)
                                    <span
                                        class="badge badge-inline badge-danger">{{ translate('Seller Can Not Place Bid to His Own Product') }}</span>
                                @else
                                    <button type="button" class="btn btn-primary buy-now  fw-600 w-150px rounded"
                                        onclick="bid_modal()">
                                        <i class="las la-gavel"></i>
                                        @if (Auth::check() &&
                                                Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
                                            {{ translate('Change Bid') }}
                                        @else
                                            {{ translate('Place Bid') }}
                                        @endif
                                    </button>
                                @endif
                            </div>
                        @endif
                    @else
                        <!-- Add to cart & Quick Buy Buttons -->
                        <div class="d-flex sm-gap w-100 px-2">
                            @if ($detailedProduct->digital == 0)
                                @if ($detailedProduct->external_link != null)
                                    <a type="button" class="btn btn-primary buy-now fw-600 add-to-cart px-4 rounded"
                                        href="{{ $detailedProduct->external_link }}">
                                        <i class="la la-share"></i>
                                        {{ translate($detailedProduct->external_link_btn) }}
                                    </a>
                                @else
                                    <button type="button"
                                        class="  add-to-cart-fixed-bottom fs-18 main_add_to_cart_button rounded-0 btn mr-lg-2 add-to-cart fs-18 d-lg-none d-flex justify-content-center align-items-center fw-600 w-150px rounded text-white"
                                        onclick="addToCart()">
                                        <span class="d-none add_to_cart_loader" >
                                <img width="20px" src="{{static_asset('assets/img/loader.svg')}}" >
                                </span>
                                        <span class="d-block">
                                            
                                            {{ translate('Add to cart') }}</span> 
                                    </button>
                                    <button type="button"
                                        class="main_add_to_cart_button w-50  add-to-cart-qty add-to-cart d-flex justify-content-center align-items-center rounded-0 fs-18"
                                        onclick="addToCart()">
                                        <span class="d-none add_to_cart_loader" >
                                <img width="20px" src="{{static_asset('assets/img/loader.svg')}}" >
                                </span>
                                        <span class="d-block ">
                                            {{ translate('Add to cart') }}</span>
                                    </button>
                                    <button type="button" class="main_buy_now_button w-50 buy-now add-to-cart  fs-18 d-flex justify-content-center align-items-center"
                                        onclick="buyNow()">
                                        <span class="d-none quick_buy_loader" >
                                <img width="20px" src="{{static_asset('assets/img/loader.svg')}}" >
                                </span>
                                        {{ translate('Quick Buy') }}
                                    </button>
                                @endif
                                <button type="button" class="btn btn-secondary out-of-stock fw-600 d-none" disabled>
                                    <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock') }}
                                </button>
                            @elseif ($detailedProduct->digital == 1)
                                <button type="button"
                                    class="main_add_to_cart_button add-to-cart-fixed-bottom fs-18 main_add_to_cart_button fs-18 add-to-cart d-lg-none d-flex justify-content-center align-items-center"
                                    onclick="addToCart()">
                                <span class="d-none add_to_cart_loader" >
                                <img width="20px" src="{{static_asset('assets/img/loader.svg')}}" >
                                </span>
                                    <span class="d-block"> {{ translate('Add to cart') }}</span>
                                </button>
                                <button type="button"
                                    class=" w-50 add-to-cart-qty  add-to-cart d-flex justify-content-center align-items-center rounded-0"
                                    onclick="addToCart()">
                                    <span class="d-none add_to_cart_loader" >
                                <img width="20px" src="{{static_asset('assets/img/loader.svg')}}" >
                                </span>
                                    <span class="d-block fs-18"> {{ translate('Add to cart') }}</span>
                                </button>
                                <button type="button"
                                    class="main_buy_now_button w-50 fs-18 buy-now add-to-cart rounded d-flex justify-content-center align-items-center"
                                    onclick="buyNow()">
                                    <span class="d-none quick_buy_loader" >
                                <img width="20px" src="{{static_asset('assets/img/loader.svg')}}" >
                                </span>
                                    {{ translate('Quick Buy') }}
                                </button>
                            @endif
                        </div>

                    @endif
                    <div class="col-12 mt-3">
                        <div class="d-flex align-items-center sm-gap">
                            <span> <i class="fa-regular h5 fs-18 fw-700 text-capitaliz fa-eye"></i></span>
                            <span class="d-flex align-items-center sm-gap"><span
                                    class="h5 fs-18 fw-700 text-capitaliz">{{ rand(50, 100) }}</span> <span
                                    class="h5 fs-18 fw-700 text-capitaliz">{{ translate('Views') }}</span> </span>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    @else
        @if ($detailedProduct->auction_product)
            @php
                $highest_bid = $detailedProduct->bids->max('amount');
                $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $detailedProduct->starting_bid;
            @endphp
            @if ($detailedProduct->auction_end_date >= strtotime('now'))
                <div class="mt-4">
                    @if (Auth::check() && $detailedProduct->user_id == Auth::user()->id)
                        <span
                            class="badge badge-inline badge-danger">{{ translate('Seller Can Not Place Bid to His Own Product') }}</span>
                    @else
                        <button type="button" class="btn btn-primary buy-now  fw-600 w-150px rounded"
                            onclick="bid_modal()">
                            <i class="las la-gavel"></i>
                            @if (Auth::check() &&
                                    Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
                                {{ translate('Change Bid') }}
                            @else
                                {{ translate('Place Bid') }}
                            @endif
                        </button>
                    @endif
                </div>
            @endif
        @else
            <!-- Add to cart & Quick Buy Buttons -->
            <div class="mt-3 px-2">
                @if ($detailedProduct->digital == 0)
                    @if ($detailedProduct->external_link != null)
                        <a type="button" class="btn btn-primary buy-now fw-600 add-to-cart px-4 rounded"
                            href="{{ $detailedProduct->external_link }}">
                            <i class="la la-share"></i> {{ translate($detailedProduct->external_link_btn) }}
                        </a>
                    @else
                        <button type="button" class="btn btn-dark mr-2 add-to-cart fw-600 w-150px rounded text-white"
                            onclick="addToCart()">
                            <i class="las la-shopping-bag"></i>
                            <span class="d-none d-md-inline-block"> {{ translate('Add to cart') }}</span>
                        </button>
                        <button type="button" class="btn btn-light buy-now fw-600 add-to-cart w-150px rounded"
                            onclick="buyNow()">
                            <i class="la la-shopping-cart"></i> {{ translate('Quick Buy') }}
                        </button>
                    @endif
                    <button type="button" class="btn btn-secondary out-of-stock fw-600 d-none" disabled>
                        <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock') }}
                    </button>
                @elseif ($detailedProduct->digital == 1)
                    <button type="button" class="btn btn-dark mr-2 add-to-cart fw-600 w-150px rounded text-white"
                        onclick="addToCart()">
                        <i class="las la-shopping-bag"></i>
                        <span class="d-none d-md-inline-block"> {{ translate('Add to cart') }}</span>
                    </button>
                    <button type="button" class="btn btn-light buy-now fw-600 add-to-cart w-150px rounded"
                        onclick="buyNow()">
                        <i class="la la-shopping-cart"></i> {{ translate('Quick Buy') }}
                    </button>
                @endif
            </div>

        @endif


    @endif

    @if ($detailedProduct->auction_product)
    @else
        <!-- Promote Link -->
        <div class="d-table width-100 mt-3 d-none">
            <div class="d-table-cell">
                @if (Auth::check() &&
                        addon_is_activated('affiliate_system') &&
                        (\App\Models\AffiliateOption::where('type', 'product_sharing')->first()->status ||
                            \App\Models\AffiliateOption::where('type', 'category_wise_affiliate')->first()->status) &&
                        Auth::user()->affiliate_user != null &&
                        Auth::user()->affiliate_user->status)
                    @php
                        if (Auth::check()) {
                            if (Auth::user()->referral_code == null) {
                                Auth::user()->referral_code = substr(Auth::user()->id . Str::random(10), 0, 10);
                                Auth::user()->save();
                            }
                            $referral_code = Auth::user()->referral_code;
                            $referral_code_url =
                                URL::to('/product') .
                                '/' .
                                $detailedProduct->slug .
                                "?product_referral_code=$referral_code";
                        }
                    @endphp
                    <div>
                        <button type="button" id="ref-cpurl-btn" class="btn btn-secondary w-200px rounded"
                            data-attrcpy="{{ translate('Copied') }}" onclick="CopyToClipboard(this)"
                            data-url="{{ $referral_code_url }}">{{ translate('Copy the Promote Link') }}</button>
                    </div>
                @endif
            </div>
        </div>
        <!-- Brand Logo & Name -->
        @if ($detailedProduct->brand != null)
            <div class=" flex-wrap align-items-center mb-3 d-none">
                <span class="text-secondary fs-14 fw-400 mr-4 w-50px">{{ translate('Brand') }}</span><br>
                <a href="{{ route('shop.visit', $detailedProduct->brand->slug) }}"
                    class="text-reset hov-text-primary fs-14 fw-700">{{ $detailedProduct->brand->name }}</a>
            </div>
        @endif
        <!-- Refund -->
        @php
            $refund_sticker = get_setting('refund_sticker');
        @endphp
        @if (addon_is_activated('refund_request'))
            <div class="row no-gutters mt-3">
                <div class="col-sm-2">
                    <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Refund') }}</div>
                </div>
                <div class="col-sm-10">
                    @if ($detailedProduct->refundable == 1)
                        <a href="{{ route('returnpolicy') }}" target="_blank">
                            @if ($refund_sticker != null)
                                <img src="{{ uploaded_asset($refund_sticker) }}" height="36">
                            @else
                                <img src="{{ static_asset('assets/img/refund-sticker.jpg') }}" height="36">
                            @endif
                        </a>
                        <a href="{{ route('returnpolicy') }}" class="text-blue hov-text-primary fs-14 ml-3"
                            target="_blank">{{ translate('View Policy') }}</a>
                    @else
                        <div class="text-dark fs-14 fw-400 mt-2">{{ translate('Not Applicable') }}</div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Seller Guarantees -->
        @if ($detailedProduct->digital == 1)
            @if ($detailedProduct->added_by == 'seller')
                <div class="row no-gutters mt-3">
                    <div class="col-2">
                        <div class="text-secondary fs-14 fw-400">{{ translate('Seller Guarantees') }}</div>
                    </div>
                    <div class="col-10">
                        @if ($detailedProduct->user->shop->verification_status == 1)
                            <span class="text-success fs-14 fw-700">{{ translate('Verified seller') }}</span>
                        @else
                            <span class="text-danger fs-14 fw-700">{{ translate('Non verified seller') }}</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    @endif




    <div id="accordion1" style="margin-top: 20px;">
        <div class="">
            <div class="card-header bg-transparent product-details-card" id="headingOne">
                <h5 class="mb-0 w-100 h-100">
                    <button
                        class="btn btn-link text-dark w-100 h-100 d-flex justify-content-between align-items-center collapse-button"
                        data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                        aria-controls="collapseOne">
                        <strong class="fs-18">{{ translate('Product Description') }}</strong>
                        <i style="display:none" id="description-plus-icon" class="fa-solid fa-plus"></i>
                        <i id="description-minus-icon" class="fa-solid fa-minus"></i>
                    </button>
                </h5>
            </div>

            <div class="collapse show" id="collapseOne" aria-labelledby="headingOne" data-parent="#accordion1">
                <div class="card-body p-3 fs-18 text-dark">
                    @include('frontend.product_details.description')
                </div>
            </div>
        </div>
    </div>
    <div id="accordion2" class="my-2">
        <div class="">
            <div class="card-header bg-transparent product-details-card" id="headingTwo">
                <h5 class="mb-0 h-100 w-100">
                    <button
                        class="btn btn-link d-flex text-dark justify-content-between align-items-center  collapsed w-100 h-100 collapse-button"
                        data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                        aria-controls="collapseTwo">
                        <strong class="fs-18">{{ translate('product details') }}</strong>
                        <i style="display:none" id="details-plus-icon" class="fa-solid fa-plus"></i>
                        <i id="details-minus-icon" class="fa-solid fa-minus"></i>
                    </button>
                </h5>
            </div>
            <div class="collapse show" id="collapseTwo" aria-labelledby="headingTwo" data-parent="#accordion2">
                <div class="card-body">
                    <div>
                        <span class="h5 fs-15  mb-0 opacity-80 text-capitalize">{{ translate('SKU') }}</span>
                        <span class="mx-2">:</span> <span class="h5 fs-15 mb-0 fw-700 text-capitalize"
                            id="selected_product_sku">{{ translate($detailedProduct->sku) }}</span>

                    </div>

                    @if (count(json_decode($detailedProduct->product_details, true)) > 0)
                        @foreach (json_decode($detailedProduct->product_details, true) as $key => $value)
                            <div> <span class="h5 fs-15  mb-0 opacity-80 text-capitalize">{{ $key }}</span>
                                <span class="mx-2">:</span> <span
                                    class="h5 fs-15 mb-0 fw-700 text-capitalize">{{ $value }}</span>

                            </div>
                        @endforeach
                    @endif


                    @if (count($detailedProduct->sub_categories) > 0)
                        <div>
                            <span
                                class="h5 fs-15  mb-0 opacity-80 text-capitalize">{{ translate('Categories') }}</span>
                            <span class="mx-2">:</span>
                            <span class="h5 fs-15 mb-0 fw-700 text-capitalize">
                                @foreach ($detailedProduct->sub_categories as $index => $sub)
                                    <a
                                        href="{{ route('products.category', $sub->slug) }}">{{ $sub->getTranslation('name') }}</a>
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </span>
                        </div>
                    @endif
                    @include('frontend.product_details.seller_info')
                </div>
            </div>
        </div>
    </div>
    @if ($detailedProduct->guarantee_type == 'normal')
        <div id="accordion3">
            <div class="">
                <div class="card-header bg-transparent product-details-card" id="headingThree">
                    <h5 class="mb-0 h-100 w-100">
                        <button
                            class="btn btn-link d-flex  text-dark justify-content-between align-items-center  w-100 h-100 collapsed collapse-button"
                            data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                            aria-controls="collapseThree">
                            <strong class="fs-18">{{ translate('deliver and return') }}</strong>
                            <i style="display:none" id="return-plus-icon" class="fa-solid fa-plus"></i>
                            <i id="return-minus-icon" class="fa-solid fa-minus"></i>
                        </button>
                    </h5>
                </div>

                <div class="collapse show" id="collapseThree" aria-labelledby="headingThree"
                    data-parent="#accordion3">
                    <div class="card-body h5 fs-15 fw-400 mb-0 text-capitalize">
                        {{ translate('You have 7 days to submit a return request if you change your mind. Learn more about the return process ') }}
                        <a class="fw-700 " href="{{ route('returnpolicy') }}">{{ translate('here') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @else
    <div id="accordion4">
        <div class="">
            <div class="card-header bg-transparent product-details-card" id="headingFour">
                <h5 class="mb-0 h-100 w-100">
                    <button st
                        class="btn btn-link d-flex  text-dark justify-content-between align-items-center  w-100 h-100 collapsed collapse-button"
                        data-toggle="collapse" data-target="#collapseFour" aria-expanded="false"
                        aria-controls="collapseFour">
                        <strong
                            style=" background: {{ $detailedProduct->guarantee_type == 'normal' ? '' : 'linear-gradient(252.57deg, #FFC700 -25.08%, #543800 143.91%)' }}"
                            class="dark-button-style py-1 px-4">{{ $detailedProduct->guarantee_type == 'normal' ? translate('guarantee') : translate('Golden guarantee') }}</strong>
                        <i style="display:none" id="guarantee-plus-icon" class="fa-solid fa-plus"></i>
                        <i id="guarantee-minus-icon" class="fa-solid fa-minus"></i>
                    </button>
                </h5>
            </div>
            <div class="collapse show" id="collapseFour" aria-labelledby="headingFour" data-parent="#accordion4">
                <div class="card-body h5 fs-15 fw-400 mb-0">
                     {{ translate('golden_guarantee_text') }}
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <div>
        <ul class="list-inline mb-0">
            @if (get_setting('product_payment_method') != null)
                @foreach (explode(',', get_setting('product_payment_method')) as $key => $value)
                    <li class="list-inline-item ">
                        <img width="100%" src="{{ uploaded_asset($value) }}" height="30">
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
