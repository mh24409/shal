<div class="modal-body px-4 py-5 c-scrollbar-light">
    <!-- Item added to your cart -->
    <div class="d-flex flex-column justify-content-center align-items-center text-success mb-4">
        <img width="100px" height="10px" src="{{ static_asset('assets/img/addedSuccessfuly.gif') }}" alt="">
        <h3 class="fs-28 fw-500">{{ translate('Item added to your cart!') }}</h3>
    </div>

    <!-- Product Info -->
    <div class="media mb-4 ">
        <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
            data-src="{{ uploaded_asset($product->thumbnail_img) }}" class="mr-4 lazyload size-90px img-fit rounded-0"
            alt="Product Image"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        <div class="media-body mt-2 text-left d-flex flex-column justify-content-between">
            <h6 class="fs-14 fw-700 text-truncate-2">
                {{ $product->getTranslation('name') }}
            </h6>
            <div class="row mt-2">
                <div class="col-sm-3 fs-14 fw-400 text-secondary">
                    <div>{{ translate('Price') }}</div>
                </div>
                <div class="col-sm-9">
                    <div class="fs-16 fw-700 text-primary">
                        <strong>
                            {{ single_price(($data['price'] + $data['tax']) * $data['quantity']) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related product -->
    <div class="bg-white shadow-sm d-none">
        <div class="py-3">
            <h3 class="fs-16 fw-700 mb-0 text-dark">
                <span class="mr-4">{{ translate('Frequently Bought Together') }}</span>
            </h3>
        </div>
        <div class="p-3">
            <div class="aiz-carousel gutters-5 half-outside-arrow" data-items="2" data-xl-items="3" data-lg-items="4"
                data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='true'>
                @foreach (filter_products(\App\Models\Product::where('category_id', $product->category_id)->where('id', '!=', $product->id))->limit(10)->get() as $key => $related_product)
                    <div class="carousel-box hov-scale-img hov-shadow-sm">
                        <div class="aiz-card-box my-2 has-transition">
                            <div class="">
                                <a href="{{ route('product', $related_product->slug) }}" class="d-block">
                                    <img class="img-fit lazyload mx-auto h-140px h-md-200px has-transition"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($related_product->thumbnail_img) }}"
                                        alt="{{ $related_product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </a>
                            </div>
                            <div class="p-md-3 p-2 text-center">
                                <h3 class="fw-400 fs-14 text-dark text-truncate-2 lh-1-4 mb-0 h-35px">
                                    <a href="{{ route('product', $related_product->slug) }}"
                                        class="d-block text-reset hov-text-primary">{{ $related_product->getTranslation('name') }}</a>
                                </h3>
                                <div class="fs-14 mt-3">
                                    <span
                                        class="fw-700 text-primary">{{ home_discounted_base_price($related_product) }}</span>
                                    @if (home_base_price($related_product) != home_discounted_base_price($related_product))
                                        <del
                                            class="fw-600 opacity-50 ml-1">{{ home_base_price($related_product) }}</del>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Back to shopping & Checkout buttons -->
    <div class="row gutters-5">
        <div class="col-sm-6">
            <button class="btn btn-warning mb-3 mb-sm-0 btn-block rounded-0 text-white"
                data-dismiss="modal">{{ translate('Back to shopping') }}</button>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('cart') }}"
                class="btn btn-primary mb-3 mb-sm-0 btn-block rounded-0">{{ translate('Proceed to Checkout') }}</a>
        </div>

    </div>
</div>
