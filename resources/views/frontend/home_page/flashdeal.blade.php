@php
    $flash_deal = \App\Models\FlashDeal::where('status', 1)
        ->where('featured', 1)
        ->first();
@endphp
@if (
    $flash_deal != null &&
        strtotime(date('Y-m-d H:i:s')) >= $flash_deal->start_date &&
        strtotime(date('Y-m-d H:i:s')) <= $flash_deal->end_date)
    <div class="container">
        <section class="row">
            <div class="col-md-3 section-from-db p-2 order1">
                <div class="title h5 fs-25 mb-2 fw-700 text-capitalize">{{ $flash_deal->title }}
                </div>
                <div class="text h5 fs-18 opacity-80  mb-2 text-capitalize">{{ strip_tags($flash_deal->description) }} </div>
                <div class="button"><a class="btn btn-md section-btn w-150px py-2 text-white"
                        href="{{ route('flash-deal-details', $flash_deal->slug) }}">{{ translate('view all') }}</a>
                </div>
            </div>
            <div class="col-md-9 order2">
                @php
                    $flash_deals = $flash_deal->flash_deal_products->take(10);
                @endphp
                @php
                    $init = 0;
                    $end = 1;
                @endphp
                <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="3" data-xl-items="3" data-lg-items="3"
                    data-md-items="2" data-sm-items="2" data-xs-items="1" data-arrows='false' data-infinite='false'>
                    @for ($i = 0; $i < 5; $i++)
                        @foreach ($flash_deals as $key => $flash_deal_product)
                            @if ($key >= $init && $key <= $end)
                                @php
                                    $product = \App\Models\Product::find($flash_deal_product->product_id);
                                @endphp
                                @if ($product != null && $product->published != 0)
                                    @php
                                        $product_url = route('product', $product->slug);
                                        if ($product->auction_product == 1) {
                                            $product_url = route('auction-product', $product->slug);
                                        }
                                    @endphp

                                    <?php $uniqueID = uniqid('prefix_' . $product->id . '_'); ?>
                                    <div
                                        class="carousel-box px-3 position-relative has-transition hov-animate-outline ">
                                        @include('frontend.partials.product_box_1', [
                                            'product' => $product,
                                            'uniqueID' => $uniqueID,
                                        ])
                                    </div>
                                @endif
                            @endif
                        @endforeach

                        @php
                            $init += 2;
                            $end += 2;
                        @endphp
                    @endfor
                </div>
            </div>
        </section>
    </div>
    <div class="sections-between-space"></div>
@endif
