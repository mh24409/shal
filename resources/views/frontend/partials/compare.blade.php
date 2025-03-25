{{-- <a href="{{ route('compare') }}" class="d-flex position-relative sm-gap align-items-center px-2  dyna-color" data-toggle="tooltip"
    data-title="{{ translate('Compare') }}" data-placement="top">
    <i class="fa-solid fa-down-left-and-up-right-to-center"></i>
    <span class="h5 fs-14 fw-700 mb-0 text-capitalize ">{{ translate('compare') }}</span>
    <span
        class="badge badge-third badge-inline badge-pill absolute-top-right--10px">{{ Session::has('compare') && count(Session::get('compare')) > 0 ? count(Session::get('compare')) : '0' }}</span>
</a> --}}
<div class="aiz-top-menu-sidebar-compare collapse-sidebar-wrap sidebar-all sidebar-left z-1035">
    <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle"
        data-target=".aiz-top-menu-sidebar-compare" data-same=".hide-top-menu-bar"></div>
    <div style="overflow: visible;"
        class="collapse-sidebar c-scrollbar-light text-left d-flex justify-content-start align-items-start flex-column p-4">
        <button type="button" class=" hide-top-menu-bar close-sidebar" data-toggle="class-toggle"
            data-target=".aiz-top-menu-sidebar-compare">
            <i class="fa-solid fa-angle-left"></i>
        </button>
        <div class="h-100 w-100 d-flex justify-content-between align-items-center flex-column">
            <div>
                <h3 class="w-100 h5 mb-5 fs-20 fw-700  mb-0 text-capitalize text-center">
                    {{ translate('Your compare list') }}
                </h3>
                @if (Session::has('compare'))
                    @if (count(Session::get('compare')) > 0)

                        <ul class=" overflow-auto c-scrollbar-light list-group list-group-flush mx-1">
                            @foreach (Session::get('compare') as $key => $item)
                                @php
                                    $product = \App\Models\Product::find($item);
                                @endphp
                                @if ($product != null)
                                    <li class="list-group-item border-0 hov-scale-img p-0">
                                        <span class="d-flex align-items-center">
                                            <a href="{{ route('product', $product->slug) }}"
                                                class="text-reset d-flex align-items-center flex-grow-1">
                                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                    class="img-fit lazyload size-60px has-transition"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                <span class="minw-0 pl-2 flex-grow-1">
                                                    <span class="fw-700 fs-13 text-dark mb-2 text-truncate-2"
                                                        title="{{ $product->getTranslation('name') }}">
                                                        {{ $product->getTranslation('name') }}
                                                    </span>
                                                    <span class="fs-14 fw-400 text-secondary">
                                                        @if (home_price($product) != home_discounted_price($product))
                                                            {{ home_discounted_price($product) }}
                                                            <del class="fs-14 font-weight-bold ml-2">
                                                                {{ home_price($product) }}
                                                            </del>
                                                        @else
                                                            {{ home_discounted_price($product) }}
                                                        @endif
                                                    </span>
                                                </span>
                                            </a>
                                        </span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <div
                            class=" h-100 w-100 text-center p-3 align-items-center justify-content-center d-flex flex-column">
                            <img width="200px" src="{{ static_asset('assets/img/noData.png') }}" alt="">
                            <h3 class="h5 mt-3 fs-14 fw-700  mb-0 text-capitalize">
                                {{ translate('Your compare list is empty') }}
                            </h3>
                        </div>
                    @endif
                @else
                    <div
                        class=" h-100 w-100 text-center p-3 align-items-center justify-content-center d-flex flex-column">
                        <img width="200px" src="{{ static_asset('assets/img/noData.png') }}" alt="">
                        <h3 class="h5 mt-3 fs-14 fw-700  mb-0 text-capitalize">
                            {{ translate('Your compare list is empty') }}
                        </h3>
                    </div>
                @endif
            </div>
            <div class="w-100 px-4" >
                <a href="{{ route('compare') }}"
                    class="fs-14 place-order-button w-100 d-flex justify-content-center align-items-center">
                    <span>{{ translate('Compare') }}</span> </a>
            </div>
        </div>
    </div>
</div>
