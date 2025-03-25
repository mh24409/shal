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
@endphp
<div class="aiz-card-box  hov-scale-img d-flex flex-row row-card rounded ">
    <div class="  position-relative img-fit overflow-hidden"
        style="    height: 176px  !important;width: 154px !important">
        @php
            $product_url = route('product', $product->slug);
            if ($product->auction_product == 1) {
                $product_url = route('auction-product', $product->slug);
            }
        @endphp
        <!-- Image -->

        <img class="   h-100 w-100 lazyload mx-auto img-fit has-transition"
            src="{{ static_asset('assets/img/placeholder.jpg') }}"
            data-src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}"
            title="{{ $product->getTranslation('name') }}"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        <!-- Discount percentage tag -->
        @if (discount_in_percentage($product) > 0)
            <span class="absolute-top-left bg-primary ml-1 mt-1 fs-11 fw-700 text-white w-35px text-center"
                style="padding-top:2px;padding-bottom:2px;">-{{ discount_in_percentage($product) }}%</span>
        @endif
        <!-- Wholesale tag -->
        @if ($product->wholesale_product)
            <span class="absolute-top-left fs-11 text-white fw-700 px-2 lh-1-8 ml-1 mt-1"
                style="background-color: #455a64; @if (discount_in_percentage($product) > 0) top:25px; @endif">
                {{ translate('Wholesale') }}
            </span>
        @endif

        @if (
            $product->auction_product == 1 &&
                $product->auction_start_date <= strtotime('now') &&
                $product->auction_end_date >= strtotime('now'))
            <!-- Place Bid -->
            @php
                $highest_bid = $product->bids->max('amount');
                $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
            @endphp
            <a class="cart-btn absolute-bottom-left w-100 h-35px aiz-p-hov-icon text-white fs-13 fw-700 d-flex flex-column justify-content-center align-items-center @if (in_array($product->id, $cart_added)) active @endif"
                href="javascript:void(0)" onclick="bid_single_modal({{ $product->id }}, {{ $min_bid_amount }})">
                <span class="cart-btn-text">{{ translate('Place Bid') }}</span>
                <br>
                <span><i class="las la-2x la-gavel"></i></span>
            </a>
        @endif
    </div>

    <div class="p-2 p-md-3 card-data">
        <!-- Product name -->
        <strong class=" ">
            {{ Illuminate\Support\Str::limit($product->getTranslation('name'), 22) }}
        </strong>
        <p class="text-muted mt-4">
            {!! Illuminate\Support\Str::limit(strip_tags($product->getTranslation('description')), 30) !!}
        </p>
        <div class="fs-14 d-flex justify-content-start flex-column mt-3">
            @if ($product->auction_product == 0)
                <!-- Previous price -->

                <!-- price -->
                <div class="">
                    <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
                </div>
                @if (home_base_price($product) != home_discounted_base_price($product))
                    <div class=" ">
                        <del class="fw-400 text-secondary mr-1"> {{ home_base_price($product) }}</del>
                    </div>
                @endif
            @endif
            @if ($product->auction_product == 1)
                <!-- Bid Amount -->
                <div class="">
                    <span class="fw-700 text-primary">{{ single_price($product->starting_bid) }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
