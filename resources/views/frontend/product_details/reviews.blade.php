<div id="product-review-modal-content-section">

</div>
<div class="py-3 reviews-area">
    <ul class="list-group list-group-flush">
        @foreach ($reviews as $key => $review)
            @if ($review->user != null)
                <li class="media list-group-item d-flex p-0 border-0">
                    <!-- Review User Image -->
                    @if ($review->user->avatar_original != null)
                        <span class="avatar avatar-md mr-3">
                            <img class="lazyload" src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                @if ($review->user->avatar_original != null) data-src="{{ uploaded_asset($review->user->avatar_original) }}"
                            @else
                                data-src="{{ static_asset('assets/img/placeholder.jpg') }}" @endif>
                        </span>
                    @else
                        <span class="avatar avatar-md d-flex align-items-center justify-content-center "
                            style="background-color:{{ '#' . str_pad(dechex(rand(0x000000, 0xffffff)), 6, '0', STR_PAD_LEFT) }}; color:white; font-size: 30px; font-weight: bold;">
                            {{ $review->user->name[0] ?? '' }}
                        </span>
                    @endif
                    <div class="media-body text-left mx-3">
                        <!-- Review User Name -->
                        <h3 class="fs-15 fw-600 mb-0 d-flex align-items-center mb-2">
                            <span>{{ $review->user->name }}</span>
                        </h3>
                        <div class="rating rating-mr-1 d-flex align-items-center md-gap">
                            <h3 class="fs-15 fw-400 ">
                                <span>{{ translate('rate') }}</span>
                            </h3>
                            @switch(round($review->rating))
                                @case(1)
                                    <i class="las la-star active"></i>
                                    <i class="las la-star"></i>
                                    <i class="las la-star"></i>
                                    <i class="las la-star"></i>
                                    <i class="las la-star"></i>
                                @break

                                @case(2)
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star"></i>
                                    <i class="las la-star"></i>
                                    <i class="las la-star"></i>
                                @break

                                @case(3)
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star"></i>
                                    <i class="las la-star"></i>
                                @break

                                @case(4)
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star"></i>
                                @break

                                @case(5)
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                @break

                                @default
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                            @endswitch
                        </div>
                        <!-- Review Comment -->
                        <p class="comment-text mt-2 fs-14">
                            {{ $review->comment }}
                        </p>
                        <!-- Review Images -->
                        <div class="spotlight-group d-flex flex-wrap">
                            @if ($review->photos != null)
                                @foreach (explode(',', $review->photos) as $photo)
                                    <a class="spotlight mr-2 mr-md-3 mb-2 mb-md-3 size-60px size-md-90px border overflow-hidden has-transition hov-scale-img hov-border-primary"
                                        href="{{ uploaded_asset($photo) }}">
                                        <img class="img-fit h-100 lazyload has-transition"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($photo) }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </a>
                                @endforeach
                            @endif
                        </div>
                        <!-- Variation -->
                        @php
                            $OrderDetail = \App\Models\OrderDetail::with([
                                'order' => function ($q) use ($review) {
                                    $q->where('user_id', $review->user_id);
                                },
                            ])
                                ->where('product_id', $review->product_id)
                                ->where('delivery_status', 'delivered')
                                ->first();
                        @endphp
                        @if ($OrderDetail && $OrderDetail->variation)
                            <small class="text-secondary fs-12">{{ translate('Variation :') }}
                                {{ $OrderDetail->variation }}</small>
                        @endif
                    </div>
                </li>
            @elseif($review->product != null)
                <li class="media list-group-item d-flex p-0 border-0">
                    <!-- Review User Image -->
                    <span class="avatar avatar-md d-flex align-items-center justify-content-center "
                        style="background-color:{{ '#' . str_pad(dechex(rand(0x000000, 0xffffff)), 6, '0', STR_PAD_LEFT) }}; color:white; font-size: 30px; font-weight: bold;">
                        {{ $review->username[0] ?? '' }}
                    </span>


                    <div class="media-body text-left mx-3">
                        <!-- Review User Name -->
                        <h3 class="fs-15 fw-600 mb-0 d-flex align-items-center mb-2">
                            <span>
                                {{ $review->username }}
                            </span>
                        </h3>
                        <div class="rating rating-mr-1 d-flex align-items-center md-gap">
                            <h3 class="fs-15 fw-400 ">
                                <span>{{ translate('rate') }}</span>
                            </h3>
                            <span class="rating rating-mr-1">
                                @switch(round($review->rating))
                                    @case(1)
                                        <i class="las la-star active"></i>
                                        <i class="las la-star"></i>
                                        <i class="las la-star"></i>
                                        <i class="las la-star"></i>
                                        <i class="las la-star"></i>
                                    @break

                                    @case(2)
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star"></i>
                                        <i class="las la-star"></i>
                                        <i class="las la-star"></i>
                                    @break

                                    @case(3)
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star"></i>
                                        <i class="las la-star"></i>
                                    @break

                                    @case(4)
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star"></i>
                                    @break

                                    @case(5)
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                    @break

                                    @default
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                        <i class="las la-star active"></i>
                                @endswitch


                            </span>
                        </div>
                        <!-- Review Comment -->
                        <p class="comment-text mt-2 fs-14">
                            {{ $review->comment }}
                        </p>
                        <!-- Review Images -->
                        <div class="spotlight-group d-flex flex-wrap">
                            @if ($review->photos != null)
                                @foreach (explode(',', $review->photos) as $photo)
                                    <a class="spotlight mr-2 mr-md-3 mb-2 mb-md-3 size-60px size-md-90px border overflow-hidden has-transition hov-scale-img hov-border-primary"
                                        href="{{ uploaded_asset($photo) }}">
                                        <img class="img-fit h-100 lazyload has-transition"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($photo) }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </li>
            @endif
        @endforeach
    </ul>

    @if (count($reviews) <= 0)
        <div class="text-center fs-18 opacity-70">
            {{ translate('There have been no reviews for this product yet.') }}
        </div>
    @endif

    <!-- Pagination -->
    <div class="aiz-pagination product-reviews-pagination py-2 px-4 d-flex justify-content-end">
        {{ $reviews->links() }}
    </div>
</div>
