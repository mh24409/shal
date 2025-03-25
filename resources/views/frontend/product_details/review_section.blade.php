<div class="bg-white py-3">
    <div class="fs-18 fw-700 mb-3">
        {{-- <span>{{ translate('Based On') }}</span> <span>{{ count($reviews) }}</span>
        <span>{{ translate('Review') }}</span> --}}
        <span >
            <a href="javascript:void(0);" onclick="product_review('{{ $detailedProduct->id }}')"
                class="btn btn-primary fw-400 rounded-0 text-white">
                <span class="d-md-inline-block"> {{ translate('Rate this Product') }}</span>
            </a>
        </span>

    </div>
    {{-- <div class="fs-18 fw-700 mb-3  d-flex align-items-center sm-gap">
        <span class="fs-36 text-success">{{ $detailedProduct->rating }}</span><span
            class="fs-15">{{ translate('Total Reviews') }}</span>
    </div> --}}
    <?php
    $totalReviews = count($reviews);
    $five = 0;
    $four = 0;
    $three = 0;
    $two = 0;
    $one = 0;
    $percentageFive = 0;
    $percentageFour = 0;
    $percentageThree = 0;
    $percentageTwo = 0;
    $percentageOne = 0;
    if ($totalReviews > 0) {
        foreach ($reviews as $review) {
            switch ($review->rating) {
                case 5:
                    $five++;
                    break;
                case 4:
                    $four++;
                    break;
                case 3:
                    $three++;
                    break;
                case 2:
                    $two++;
                    break;
                case 1:
                    $one++;
                    break;
            }
        }
        $percentageFive = round(($five / $totalReviews) * 100);
        $percentageFour = round(($four / $totalReviews) * 100);
        $percentageThree = round(($three / $totalReviews) * 100);
        $percentageTwo = round(($two / $totalReviews) * 100);
        $percentageOne = round(($one / $totalReviews) * 100);
    }

    ?>
    {{-- <div class="fs-18 fw-700 mb-3  d-flex align-items-center sm-gap">
        <span class="fs-15 rating rating-mr-1">{{ renderStarRating(5) }}</span>
        <span class="fs-15 w-100px h-10px d-flex align-items-center"
            style="background-color: #e4e9e4;
                display: inline-block;
                border-radius: 5px;">
            <span
                style="    height: 100%;
            width: {{ $percentageFive }}%;
            background-color: green;
            display: inline-block;
            border-radius: 5px;"></span>
        </span>
        <span class="fs-15 fw-700 opacity-80">{{ $percentageFive }}%</span>
    </div>
    <div class="fs-18 fw-700 mb-3  d-flex align-items-center sm-gap">
        <span class="fs-15 rating rating-mr-1">{{ renderStarRating(4) }}</span>
        <span class="fs-15 w-100px h-10px d-flex align-items-center"
            style="background-color: #e4e9e4;
                display: inline-block;
                border-radius: 5px;">
            <span
                style="    height: 100%;
            width: {{ $percentageFour }}%;
            background-color: green;
            display: inline-block;
            border-radius: 5px;"></span>
        </span>
        <span class="fs-15 fw-700 opacity-80">{{ $percentageFour }}%</span>
    </div>
    <div class="fs-18 fw-700 mb-3  d-flex align-items-center sm-gap">
        <span class="fs-15 rating rating-mr-1">{{ renderStarRating(3) }}</span>
        <span class="fs-15 w-100px h-10px d-flex align-items-center"
            style="background-color: #e4e9e4;
                display: inline-block;
                border-radius: 5px;">
            <span
                style="    height: 100%;
            width: {{ $percentageThree }}%;
            background-color: green;
            display: inline-block;
            border-radius: 5px;"></span>
        </span>
        <span class="fs-15 fw-700 opacity-80">{{ $percentageThree }}%</span>
    </div>
    <div class="fs-18 fw-700 mb-3  d-flex align-items-center sm-gap">
        <span class="fs-15 rating rating-mr-1">{{ renderStarRating(2) }}</span>
        <span class="fs-15 w-100px h-10px d-flex align-items-center"
            style="background-color: #e4e9e4;
                display: inline-block;
                border-radius: 5px;">
            <span
                style="    height: 100%;
            width: {{ $percentageTwo }}%;
            background-color: green;
            display: inline-block;
            border-radius: 5px;"></span>
        </span>
        <span class="fs-15 fw-700 opacity-80">{{ $percentageTwo }}%</span>
    </div>
    <div class="fs-18 fw-700 mb-3  d-flex align-items-center sm-gap">
        <span class="fs-15 rating rating-mr-1">{{ renderStarRating(1) }}</span>
        <span class="fs-15 w-100px h-10px d-flex align-items-center"
            style="background-color: #e4e9e4;
                display: inline-block;
                border-radius: 5px;">
            <span
                style="    height: 100%;
            width: {{ $percentageOne }}%;
            background-color: green;
            display: inline-block;
            border-radius: 5px;"></span>
        </span>
        <span class="fs-15 fw-700 opacity-80">{{ $percentageOne }}%</span>
    </div> --}}
    <!-- Reviews -->
    @include('frontend.product_details.reviews')
</div>
