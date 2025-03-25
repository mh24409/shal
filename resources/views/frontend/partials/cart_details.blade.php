<div class="d-lg-none d-block">

    @if ($carts && count($carts) > 0)

        <div class="breadcrumbs d-flex align-items-center justify-content-between">
            <div class="links">
                <a href="" class="fs-15"> {{translate('Home')}} </a>
                <span> <i class="fa-solid fa-chevron-left fs-10"></i> </span>
                <a href="" class="fw-700"> {{translate('cart shopping')}} </a>
            </div>
            <div class="product-count">
                <p class="m-0"> {{translate('Number of product')}}
                    <span class="fw-700"> {{count($carts)}} </span>
                    {{translate('count_product')}}
                </p>
            </div>
        </div>
        <div class="text-left">
            <div class="mb-4">
                @php
                    $total = 0;
                @endphp
                @foreach ($carts as $key => $cartItem)
                    @php
                        $product = \App\Models\Product::find($cartItem['product_id']);
                        $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                        $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
                        $product_name_with_choice = $product->getTranslation('name');
                        if ($cartItem['variation'] != null) {
                            $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variation'];
                        }
                    @endphp
                    <div class="d-flex  position-relative my-4 " style="background-color: #fff !important;border:2px solid #f1f1f1; border-radius:0 0 0 25px;padding:15px 7px 16px 6px;gap: 10px;" >
                        <a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItem['id'] }})"
                                class=" remove-cart-item button-not-button  ">
                                <i class="fa-solid fa-xmark"></i>
                        </a>
                        <img class=" p-1" src="{{ uploaded_asset($product->thumbnail_img) }}" style="width:20%"
                                alt="{{ $product->getTranslation('name') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        <div class="discrebtion w-100 d-flex flex-column justify-content-between ">
                                    <div class="price-name d-flex flex-column align-items-start">
                                        <span
                                    class=" h5 fs-16 fw-700 mb-8 text-capitaliz">{{ $product->getTranslation('name') }}</span>
                                     <span
                                    class="fw-700  fs-14 text-primary" >{{ home_discounted_base_price_by_stock_id($product_stock->id) }}
                                    
                                    @if(home_discounted_base_price_by_stock_id($product_stock->id) != home_base_price_by_stock_id($product_stock->id) ) <del class="fw-300  fs-12 text-gray">{{ home_base_price_by_stock_id($product_stock->id) }}</del> @endif 
                                    
                                    
                                    </span>
                                    @if ($cartItem['variation'] != null)
                                    <span
                                        class="h5 fs-12 opacity-80 fw-700 mb-0 d-none text-capitaliz ">{{ $product_name_with_choice }}</span>
                                @endif
                                    </div>
                                    <div class=" d-flex justify-content-between align-items-center ">
                                <div class="">
                                    @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                    <div class="d-flex align-items-center aiz-plus-minus " style="width: 85px">
                                        <button class="btn px-1 py-0 rounded-0"
                                            style="border: 1px solid #f1f1f1 !important;
                                                width: 25px;
                                                height: 25px; border-left: none;"
                                            type="button" data-type="plus"
                                            data-field="quantity[{{ $cartItem['id'] }}]">
                                            <i class="las la-plus fs-10"></i>
                                        </button>
                                        <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                            class="col border-0 text-left px-0 flex-grow-1 fs-14 text-center input-number"
                                            placeholder="1" value="{{ $cartItem['quantity'] }}"
                                            min="{{ $product->min_qty }}" max="{{ $product->back_order == 0 ? $product_stock->qty : 100 }}"
                                            onchange="updateQuantity({{ $cartItem['id'] }}, this)"
                                            style="height:25px;width: 28px ;border: 1px solid #f1f1f1 !important;background-color: transparent;    color: black;
                                            font-weight: 600;
                                            font-size: 16px !important;">
                                        <button class="btn px-1 py-0 rounded-0"
                                            style="border: 1px solid #f1f1f1 !important;
                                                width: 25px;
                                                height: 25px; border-right: none"
                                            type="button" data-type="minus"
                                            data-field="quantity[{{ $cartItem['id'] }}]">
                                            <i class="las la-minus fs-12"></i>
                                        </button>
                                    </div>
                                    @elseif($product->auction_product == 1)
                                    <span class="fw-700 fs-14">1</span>
                                    @endif
                                </div>
                                <span class=" fs-14 fw-500 text-nowrap" style="padding:0 0 0 10px" >{{ translate('total') }} :
                                    <span class="fw-700">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem['quantity']) }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class=" p-3 mb-4" style="background-color: #efefef !important; border-radius:0 0 0 25px">
                        <div class="row d-flex sm-gap p-0 position-relative">
                            <img class="border p-1" src="{{ uploaded_asset($product->thumbnail_img) }}" style="width:28%"
                                alt="{{ $product->getTranslation('name') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            <div class="d-flex flex-column md-gap " style="width:68%">
                                <div>
                                    <span
                                    class=" h5 fs-18 fw-700 mb-8 text-capitaliz">{{ $product->getTranslation('name') }}</span>
                                @if ($cartItem['variation'] != null)
                                    <span
                                        class="h5 fs-12 opacity-80 fw-700 mb-0 d-none text-capitaliz ">{{ $product_name_with_choice }}</span>
                                @endif
                                <span
                                    class="fw-700 fs-14">{{ cart_product_price($cartItem, $product, true, false) }}</span>
                                </div>
                                <div class=" d-flex justify-content-between align-items-center ">
                                <div class="">
                                    @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                    <div class="d-flex align-items-center aiz-plus-minus ">
                                        <button class="btn px-1 py-0 rounded-0"
                                            style="border: 1px solid #bfbcbc !important;
                                                width: 20px;
                                                height: 30px;"
                                            type="button" data-type="plus"
                                            data-field="quantity[{{ $cartItem['id'] }}]">
                                            <i class="las la-plus fs-12"></i>
                                        </button>
                                        <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                            class="col border-0 text-left px-0 flex-grow-1 fs-14 text-center input-number"
                                            placeholder="1" value="{{ $cartItem['quantity'] }}"
                                            min="{{ $product->min_qty }}" max="{{ $product->back_order == 0 ? $product_stock->qty : 100 }}"
                                            onchange="updateQuantity({{ $cartItem['id'] }}, this)"
                                            style="height: 30px;border: 1px solid #bfbcbc !important;background-color: transparent;    color: black;
                                            font-weight: bold;
                                            font-size: 16px !important;">
                                        <button class="btn px-1 py-0 rounded-0"
                                            style="border: 1px solid #bfbcbc !important;
                                                width: 20px;
                                                height: 30px;"
                                            type="button" data-type="minus"
                                            data-field="quantity[{{ $cartItem['id'] }}]">
                                            <i class="las la-minus fs-12"></i>
                                        </button>
                                    </div>
                                    @elseif($product->auction_product == 1)
                                    <span class="fw-700 fs-14">1</span>
                                    @endif
                                </div>
                                <span class="fw-700 fs-16 text-primary text-nowrap">{{ translate('total') }} :
                                    {{ single_price(cart_product_price($cartItem, $product, false) * $cartItem['quantity']) }}</span>
                            </div>
                                </div>
                            <a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItem['id'] }})"
                                class="  button-not-button remove-from-cart-button ">
                                <i class="fa-solid fa-xmark"></i>
                            </a>

                        </div>

                        <div class="row position-relative py-1 px-3 d-none  ">
                            <div class="col-6">
                                @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                    <div class="d-flex align-items-center aiz-plus-minus w-100 ">
                                        <button class="btn px-1 py-0 rounded-0"
                                            style="border: 1px solid #bfbcbc !important;
                                                width: 40px;
                                                height: 45px;"
                                            type="button" data-type="plus"
                                            data-field="quantity[{{ $cartItem['id'] }}]">
                                            <i class="las la-plus"></i>
                                        </button>
                                        <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                            class="col border-0 text-left px-0 flex-grow-1 fs-14 text-center input-number"
                                            placeholder="1" value="{{ $cartItem['quantity'] }}"
                                            min="{{ $product->min_qty }}" max="{{ $product->back_order == 0 ? $product_stock->qty : 100 }}"
                                            onchange="updateQuantity({{ $cartItem['id'] }}, this)"
                                            style="height: 45px;border: 1px solid #bfbcbc !important;background-color: transparent;    color: black;
                                            font-weight: bold;
                                            font-size: 20px !important;">
                                        <button class="btn px-1 py-0 rounded-0"
                                            style="border: 1px solid #bfbcbc !important;
                                                width: 40px;
                                                height: 45px;"
                                            type="button" data-type="minus"
                                            data-field="quantity[{{ $cartItem['id'] }}]">
                                            <i class="las la-minus"></i>
                                        </button>
                                    </div>
                                @elseif($product->auction_product == 1)
                                    <span class="fw-700 fs-14">1</span>
                                @endif
                            </div>
                            <div class="col-6 d-flex justify-content-center align-items-center ">
                                <span class="fw-700 fs-16 text-primary text-nowrap">{{ translate('total') }} :
                                    {{ single_price(cart_product_price($cartItem, $product, false) * $cartItem['quantity']) }}</span>
                            </div>
                        </div>


                    </div> -->

                @endforeach
            </div>
        </div>
    @else
        <div class="  bg-white p-4">
            <!-- Empty cart -->
            <div class="text-center p-3">
                <i class="las la-frown la-3x opacity-60 mb-3"></i>
                <h3 class="h4 fw-700">{{ translate('Your Cart is empty') }}</h3>
            </div>
        </div>
    @endif
</div>
<div class="d-lg-block d-none">
    @if ($carts && count($carts) > 0)
        <div class=" bg-white p-3 p-lg-4 text-left">
            <div class="mb-4">
                <!-- Headers -->
                <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3 ">
                    <div class="col-auto h5 fs-15 fw-700 mb-0 text-capitaliz"> </div>
                    <div class="col-md-5 h5 fs-15 fw-700 mb-0 text-capitaliz">{{ translate('Product') }}</div>
                    <div class="col h5 fs-15 fw-700 mb-0 text-capitaliz">{{ translate('Price') }}</div>
                    <div class="col h5 fs-15 fw-700 mb-0 text-capitaliz">{{ translate('Qty') }}</div>
                    <div class="col h5 fs-15 fw-700 mb-0 text-capitaliz">{{ translate('Total') }}</div>
                </div>
                <ul class="list-group list-group-flush">
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($carts as $key => $cartItem)
                        @php
                            $product = \App\Models\Product::find($cartItem['product_id']);
                            $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                            $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
                            $product_name_with_choice = $product->getTranslation('name');
                            if ($cartItem['variation'] != null) {
                                $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variation'];
                            }
                        @endphp
                        <li class="list-group-item px-0">
                            <div class="row gutters-5 align-items-center">
                                <!-- Remove From Cart -->
                                <div class="col-md-auto col-6 order-2 order-md-0 text-right">
                                    <a href="javascript:void(0)"
                                        onclick="removeFromCartView(event, {{ $cartItem['id'] }})"
                                        class="button-not-button">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                </div>
                                <!-- Product Image & name -->
                                <div class="col-md-5 d-flex align-items-center mb-2 mb-md-0 md-gap">
                                    <img src="{{ uploaded_asset($product->thumbnail_img) }}" style="width:20%"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    <div class="d-flex flex-column">
                                        <span
                                            class=" h5 fs-15 fw-700 mb-0 text-capitaliz">{{ $product->getTranslation('name') }}</span>
                                        @if ($cartItem['variation'] != null)
                                            <span
                                                class="h5 fs-10 opacity-80 fw-700 mb-0 text-capitaliz ">{{ $product_name_with_choice }}</span>
                                        @endif

                                    </div>
                                </div>
                                <!-- Price -->
                                <div class="col-md col-4 order-2 order-md-0 my-3 my-md-0">
                                    <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Price') }}</span>
                                    <span class="fw-700 fs-14">{{ single_price(cart_product_price($cartItem, $product, false)) }}</span>
                                </div>
                                <!-- Quantity -->
                                <div class="col-md col-4 order-3 order-md-0 my-3 my-md-0">
                                    @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                        <div class="d-flex align-items-center aiz-plus-minus mr-2 ml-0"
                                            style="width: fit-content;">
                                            <div class="d-flex flex-column">
                                                <button class="btn px-1 py-0 rounded-0"
                                                    style="border:0.1px solid #f0f0f0 !important" type="button"
                                                    data-type="plus" data-field="quantity[{{ $cartItem['id'] }}]">
                                                    <i class="las la-plus"></i>
                                                </button>
                                                <button class="btn px-1 py-0 rounded-0"
                                                    style="border:0.1px solid #f0f0f0 !important" type="button"
                                                    data-type="minus" data-field="quantity[{{ $cartItem['id'] }}]">
                                                    <i class="las la-minus"></i>
                                                </button>
                                            </div>
                                            <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                                class="col border-0 text-left px-0 flex-grow-1 fs-14 text-center input-number"
                                                placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                min="{{ $product->min_qty }}" max="{{ $product->back_order == 0 ? $product_stock->qty : 100 }}"
                                                onchange="updateQuantity({{ $cartItem['id'] }}, this)"
                                                style="height: 45px;border: 0.1px solid #f0f0f0 !important;">
                                        </div>
                                    @elseif($product->auction_product == 1)
                                        <span class="fw-700 fs-14">1</span>
                                    @endif
                                </div>
                                <!-- Total -->
                                <div class="col-md col-4 order-4 order-md-0 my-3 my-md-0">
                                    <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Total') }}</span>
                                    <span
                                        class="fw-700 fs-16 text-primary">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem['quantity']) }}</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @else
        <div class="  bg-white p-4">
            <!-- Empty cart -->
            <div class="text-center p-3">
                <i class="las la-frown la-3x opacity-60 mb-3"></i>
                <h3 class="h4 fw-700">{{ translate('Your Cart is empty') }}</h3>
            </div>
        </div>
    @endif
</div>
<script type="text/javascript">
    AIZ.extra.plusMinus();
</script>
