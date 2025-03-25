<?php

// $decodedProductDetails = json_decode($detailedProduct->product_details, true);
// $query = \App\Models\Product::query();
// if ($decodedProductDetails != '' ) {
//     foreach ($decodedProductDetails as $keyy => $detail) {
//         $query->whereJsonContains('product_details->' . $keyy, $detail);
//     }
//     $query->where('id', '!=', $detailedProduct->id);
//     $query->limit(6);
//     $relatedPRO = filter_products($query->get());
// } else {
    $relatedPRO = [];
// }

?>
@if (count($relatedPRO) > 0)
    <div class="container">
        <div class="p-3 p-sm-4">
            <h3 class="fs-16 fw-700 mb-0">
                <span class="mr-4">{{ translate('Related products') }}</span>
            </h3>
        </div>
        <div class="px-4">
            <div class="aiz-carousel sm-gutters-16  slides-margin arrow-none" data-steps="3" data-slides-to-scroll="3"
                data-dots='true' data-items="3" data-xl-items="3" data-lg-items="3" data-md-items="2" data-sm-items="2"
                data-autoplay="true" data-xs-items="2" data-arrows='false' data-infinite='true'>
                @foreach ($relatedPRO as $key => $related_product)
                    <?php $uniqueID = uniqid('prefix_' . $related_product->id . '_'); ?>
                    <div class="carousel-box">
                        @include('frontend.partials.product_box_1', [
                            'product' => $related_product,
                            'uniqueID' => $uniqueID,
                        ])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
