    {{-- section one  --}}
    @if (get_setting('section_three_category') != null)
        @foreach (json_decode(get_setting('section_three_category'), true) as $key => $value)
            <div class="container">
                <section class="row">
                    <div class="col-md-3 section-from-db p-2 order1">
                        <div class="title h5 fs-25 mb-2 text-capitalize">{!! get_setting('section_three_title') !!}
                        </div>
                        <div>{!! get_setting('section_three_text') !!}</div>
                        <div class="button"><a class="btn btn-md section-btn w-150px py-1 text-white"
                                href="{{ get_setting('section_three_link') }}">{{ translate('view all') }}</a>
                        </div>
                    </div>
                    <div class="col-md-9 order2">
                        @php
                        $ThreeProducts = \App\Models\Product::where('unit_price', '<=', 150)->take(9)->get();
                        @endphp
                        <div class="aiz-carousel sm-gutters-16 arrow-none" data-dots='true' data-slides-to-scroll="3" data-items="3" data-xl-items="3"
                            data-lg-items="3" data-md-items="2" data-sm-items="2" data-autoplay="true" data-xs-items="2" data-arrows='false'
                            data-infinite='true'>
                            @foreach ($ThreeProducts as $key => $product)
                                <?php $uniqueID = uniqid('prefix_' . $product->id . '_'); ?>
                                <div class="carousel-box px-3 position-relative has-transition hov-animate-outline ">
                                    @include('frontend.partials.product_box_1', [
                                        'product' => $product,
                                        'uniqueID' => $uniqueID,
                                    ])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>
        @endforeach
        <div class="sections-between-space"></div>
    @endif
