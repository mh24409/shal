@extends('frontend.layouts.app')

@if (isset($category_id))
    @php
        $meta_title = \App\Models\Category::find($category_id)->meta_title;
        $meta_description = \App\Models\Category::find($category_id)->meta_description;
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = \App\Models\Brand::find($brand_id)->meta_title;
        $meta_description = \App\Models\Brand::find($brand_id)->meta_description;
    @endphp
@else
    @php
        $meta_title = get_setting('meta_title');
        $meta_description = get_setting('meta_description');
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')
    <section class="mb-4 pt-4">
        <div class="container">
            <div class="row ">
                <div class="col-md-4 category-img d-flex align-items-center justify-content-center position-relative">
                    <img class="w-400px" src="{{ uploaded_asset(get_setting('offers_image')) }}" alt=""> 
                </div>
                <div class="col-md-6 d-flex flex-column align-items-center justify-content-center mt-5">
                    <div class="title h5 fs-25 fw-700 text-dark mb-0 text-capitalize mb-3  w-100 ">
                        {!! get_setting('offers__slug') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row ">
                <div class="col-md-6 d-flex flex-column align-items-end justify-content-center mt-5">
                    <div class="title h5 fs-25 fw-700 text-dark mb-0 text-capitalize mb-3  w-100 ">
                        {!! get_setting('offers_description_to_store') !!}
                    </div>
                </div>
                 <div class="col-md-6 category-img d-flex align-items-center justify-content-center position-relative offer_featured_products_card">
                    <div style="width: 88%;">
                      
                        <div class="aiz-carousel sm-gutters-16 arrow-none" data-dots='true'
                            data-items="1" data-arrows='false' data-infinite='true'>
                            @foreach ($featuredProducts as $key => $product)
                                <?php $uniqueID = uniqid('prefix_' . $product->id . '_'); ?>
                                @include('frontend.partials.offer_featured_product', [
                                    'product' => $product,
                                    'uniqueID' => $uniqueID,
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- categories slider section  --}}
        <section class="container mt-5 ">
            @if (count(json_decode(get_setting('home_categories'), true)) > 0)
                <div class="aiz-carousel categories-owl-carousel" data-dots="true" data-items="4" data-xl-items="4" data-lg-items="4"
                    data-md-items="2" data-sm-items="1" data-xs-items="1" data-arrows='false' data-infinite='false'>
                    @if (get_setting('home_categories') != null)
                        @foreach (json_decode(get_setting('home_categories'), true) as $key => $value)
                            @php
                                $category = \App\Models\Category::find($value);
                            @endphp
                            <a href="{{ route('offers.category', $category->slug) }}"
                                class="item d-flex justify-content-center align-items-center flex-column md-gap position-relative">
                                <div style="top: 50%;left: 50%; transform: translate(-50%,-50%);z-index: 5;top: 50%;font-size: 22px;text-wrap: nowrap;"
                                    class=" absolute-top-left cat-name text-white text-center"> <strong>
                                        {{ $category->getTranslation('name') }} </strong> </div>
                                <div class="cat-img overflow-hidden" style="border-radius: 0px">
                                    <div class="overlay"></div>
                                    <img class="rounded-50 h-100 w-100" src="{{ uploaded_asset($category->icon) }}"
                                        alt="">
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            @endif
        </section>

        <div class="container sm-px-0 pt-2" style="margin-top: 50px;overflow: visible !important;">

            <div class="row ">
                <!-- Sidebar Filters -->
                <div id="sidebar-filter-container" class="col-xl-3 mt-3">
                    <form class="" id="search-form" action="" method="GET">
                        <!-- Top Filters -->
                        <div class="w-100 ">
                            <input type="hidden" name="keyword" value="{{ $query }}"> 
                        </div>
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle"
                                data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                            <div class="collapse-sidebar c-scrollbar-light text-left sidebar-filter" >
                                <!-- Categories -->
                                <div class="border-0 mb-3">
                                    <div class="fs-16 fw-700 py-1 px-2 bg-white">
                                        <a href="#collapse_1" style="color: #6a6a6a !important;"
                                            class="dropdown-toggle filter-section text-dark d-flex align-items-center justify-content-between"
                                            data-toggle="collapse">
                                            {{ translate('Category') }}
                                        </a>
                                    </div>
                                    <div class="collapse show" id="collapse_1">
                                        <ul class="p-3 mb-0 list-unstyled">
                                            {{-- @if (!isset($category_id)) --}}
                                            @foreach (\App\Models\Category::where('level', 0)->get() as $category)
                                                <li class="mb-3 text-dark">
                                                    <a class="text-reset fs-14 hov-text-primary {{ isset($category_id) && $category_id == $category->id ? 'text-danger' : '' }}  d-flex sm-gap align-items-center"
                                                        href="{{ route('offers.category', $category->slug) }}"> <span
                                                            class="category_filter_box"></span>
                                                        {{ $category->getTranslation('name') }}</a>
                                                </li>
                                            @endforeach
                                            {{-- @else
                                                <li class="mb-3">
                                                    <a class="text-reset fs-14 fw-600 hov-text-primary"
                                                        href="{{ route('search') }}">
                                                        <i class="las la-angle-left"></i>
                                                        {{ translate('All Categories') }}
                                                    </a>
                                                </li>
                                                @if (\App\Models\Category::find($category_id)->parent_id != 0)
                                                    <li class="mb-3">
                                                        <a class="text-reset fs-14 fw-600 hov-text-primary"
                                                            href="{{ route('offers.category', \App\Models\Category::find(\App\Models\Category::find($category_id)->parent_id)->slug) }}">
                                                            <i class="las la-angle-left"></i>
                                                            {{ \App\Models\Category::find(\App\Models\Category::find($category_id)->parent_id)->getTranslation('name') }}
                                                        </a>
                                                    </li>
                                                @endif
                                                <li class="mb-3">
                                                    <a class="text-reset fs-14 fw-600 hov-text-primary"
                                                        href="{{ route('offers.category', \App\Models\Category::find($category_id)->slug) }}">
                                                        <i class="las la-angle-left"></i>
                                                        {{ \App\Models\Category::find($category_id)->getTranslation('name') }}
                                                    </a>
                                                </li>
                                                @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category_id) as $key => $id)
                                                    <li class="ml-4 mb-3">
                                                        <a class="text-reset fs-14 hov-text-primary"
                                                            href="{{ route('offers.category', \App\Models\Category::find($id)->slug) }}">{{ \App\Models\Category::find($id)->getTranslation('name') }}</a>
                                                    </li>
                                                @endforeach
                                            @endif --}}

                                        </ul>
                                    </div>
                                </div>
                                <!-- Price range -->
                                 <div class="border-0 mb-3">
                                     <div class="fs-16 fw-700 py-1 px-2 bg-white">
                                         <a href="#collapse_price" style="color: #6a6a6a !important;"
                                             class="dropdown-toggle filter-section collapsed text-dark d-flex align-items-center justify-content-between"
                                             data-toggle="collapse">
                                             {{ translate('Price range') }}
                                         </a>
                                     </div>
                                     <div class="collapse" id="collapse_price">
                                         <div class="aiz-range-slider py-3">
                                             <div id="input-slider-range"
                                                 data-range-value-min="@if (\App\Models\Product::where('published', 1)->count() < 1) 0 @else {{ \App\Models\Product::where('published', 1)->min('unit_price') }} @endif"
                                                 data-range-value-max="@if (\App\Models\Product::where('published', 1)->count() < 1) 0 @else {{ \App\Models\Product::where('published', 1)->max('unit_price') }} @endif">
                                             </div>
                                
                                             <div class="row mt-2">
                                                 <div class="col-6">
                                                     <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                                                         @if (isset($min_price)) data-range-value-low="{{ $min_price }}"
                                                        @elseif($products->min('unit_price') > 0)
                                                            data-range-value-low="{{ $products->min('unit_price') }}"
                                                        @else
                                                            data-range-value-low="0" @endif
                                                         id="input-slider-range-value-low"></span>
                                                 </div>
                                                 <div class="col-6 text-right">
                                                     <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                                                         @if (isset($max_price)) data-range-value-high="{{ $max_price }}"
                                                        @elseif($products->max('unit_price') > 0)
                                                            data-range-value-high="{{ $products->max('unit_price') }}"
                                                        @else
                                                            data-range-value-high="0" @endif
                                                         id="input-slider-range-value-high"></span>
                                                 </div>
                                             </div>
                                         </div>
                                         <input type="hidden" name="min_price" value="">
                                         <input type="hidden" name="max_price" value="">
     </div>
                                </div>

                                <!-- Attributes -->
                                @foreach ($attributes as $attribute)
                                    <div class=" border-0 mb-3">
                                        <div class="fs-16 fw-700 py-1 px-2 bg-white">
                                            <a href="#" style="color: #6a6a6a !important;"
                                                style="color: #6a6a6a !important;"
                                                class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                                                data-toggle="collapse"
                                                data-target="#collapse_{{ str_replace(' ', '_', $attribute->name) }}"
                                                style="white-space: normal;">
                                                {{ $attribute->getTranslation('name') }}
                                            </a>
                                        </div>
                                        @php
                                            $show = '';
                                            foreach ($attribute->attribute_values as $attribute_value) {
                                                if (in_array($attribute_value->value, $selected_attribute_values)) {
                                                    $show = 'show';
                                                }
                                            }
                                        @endphp
                                        <div class="collapse {{ $show }}"
                                            id="collapse_{{ str_replace(' ', '_', $attribute->name) }}">
                                            <div class="p-3 aiz-checkbox-list">
                                                @foreach ($attribute->attribute_values as $attribute_value)
                                                    <label class="aiz-checkbox mb-3">
                                                        <input type="checkbox" name="selected_attribute_values[]"
                                                            value="{{ $attribute_value->value }}"
                                                            @if (in_array($attribute_value->value, $selected_attribute_values)) checked @endif
                                                            onchange="filter()">
                                                        <span class="aiz-square-check"></span>
                                                        <span
                                                            class="fs-14 fw-400 text-dark">{{ $attribute_value->value }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- Color -->
                                @if (get_setting('color_filter_activation'))
                                    <div class="bg-white border-0 mb-3">
                                        <div class="fs-16 fw-700 py-1 px-2">
                                            <a href="#" style="color: #6a6a6a !important;"
                                                class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                                                data-toggle="collapse" data-target="#collapse_color">
                                                {{ translate('Filter by color') }}
                                            </a>
                                        </div>
                                        @php
                                            $show = '';
                                            foreach ($colors as $key => $color) {
                                                if (isset($selected_color) && $selected_color == $color->code) {
                                                    $show = 'show';
                                                }
                                            }
                                        @endphp
                                        <div class="collapse {{ $show }}" id="collapse_color">
                                            <div class="p-3 aiz-radio-inline">
                                                @foreach ($colors as $key => $color)
                                                    <label class="aiz-megabox pl-0 mr-2" data-toggle="tooltip"
                                                        data-title="{{ $color->name }}">
                                                        <input type="radio" name="color" value="{{ $color->code }}"
                                                            onchange="filter()"
                                                            @if (isset($selected_color) && $selected_color == $color->code) checked @endif>
                                                        <span
                                                            class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center p-1 mb-2">
                                                            <span class="size-30px d-inline-block rounded"
                                                                style="background: {{ $color->code }};"></span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <!-- Tag -->
                                @if (get_setting('tag_filter_activation') && get_setting('tags') != null)
                                    <div class="bg-white border-0 mb-3">
                                        <div class="fs-16 fw-700 py-1 px-2">
                                            <a href="#"
                                                class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                                                data-toggle="collapse" data-target="#collapse_tag">
                                                {{ translate('Filter by tag') }}
                                            </a>
                                        </div>
                                        @php
                                            $show = '';
                                            foreach (json_decode(get_setting('tags'), true) as $key => $tag) {
                                                if (isset($selected_tag) && $selected_tag == $tag['value']) {
                                                    $show = 'show';
                                                }
                                            }
                                        @endphp
                                        <div class="collapse {{ $show }}" id="collapse_tag">
                                            <div class="p-3 aiz-radio-inline">
                                                <ul class="p-3 mb-0 list-unstyled">
                                                    @foreach (json_decode(get_setting('tags'), true) as $key => $tag)
                                                        <li class="mb-3 text-dark">
                                                            <div class="form-check">
                                                                <input type="radio"
                                                                    class="form-check-input text-reset fs-14 hov-text-primary "
                                                                    {{ isset($selected_tag) && $tag['value'] == $selected_tag ? 'checked' : '' }}
                                                                    value="{{ $tag['value'] }}" name="tag"
                                                                    onchange="filter()">
                                                                <label
                                                                    class="form-check-label {{ isset($selected_tag) && $tag['value'] == $selected_tag ? 'text-danger' : '' }}"
                                                                    for="tag_{{ $tag['value'] }}">
                                                                    {{ $tag['value'] }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                 @if (get_setting('Designes') != null)
                                <div class="border-0 mb-3">
                                    <div class="fs-16 fw-700 py-1 px-2 bg-white">
                                        <a href="#collapse_design" style="color: #6a6a6a !important;"
                                            class="dropdown-toggle filter-section collapsed text-dark d-flex align-items-center justify-content-between"
                                            data-toggle="collapse">
                                            {{ translate('Designes') }}
                                        </a>
                                    </div>
                                    <div class="collapse  " id="collapse_design">
                                        <ul class="p-3 mb-0 list-unstyled">
                                            @php
                                                $showDesign = '';
                                                foreach (json_decode(get_setting('Designes'), true) as $key => $tag) {
                                                    if (isset($selected_design) && $selected_design == $tag['value']) {
                                                        $showDesign = 'show';
                                                    }
                                                }
                                            @endphp
                                            @foreach (json_decode(get_setting('Designes'), true) as $key => $design)
                                                    <li class="mb-3 text-dark">
                                                        <div class="form-check">
                                                            <input type="radio"
                                                                class="form-check-input text-reset fs-14 hov-text-primary "
                                                                {{ isset($selected_design) && $design['value'] == $selected_design ? 'checked' : '' }}
                                                                value="{{ $design['value'] }}" name="design"
                                                                onchange="filter()">
                                                            <label
                                                                class="form-check-label {{ isset($selected_design) && $design['value'] == $selected_design ? 'text-danger' : '' }}"
                                                                for="tag_{{ $design['value'] }}">
                                                                {{ $design['value'] }}
                                                            </label>
                                                        </div>
                                                    </li>
                                                    </li>
                                            @endforeach 
                                        </ul>
                                    </div>
                                </div> 
                                  @endif
                                @if (get_setting('Events') != null)
                                <div class="border-0 mb-3">
                                    <div class="fs-16 fw-700 py-1 px-2 bg-white">
                                        <a href="#collapse_events" style="color: #6a6a6a !important;"
                                            class="dropdown-toggle filter-section collapsed text-dark d-flex align-items-center justify-content-between"
                                            data-toggle="collapse">
                                            {{ translate('Events') }}
                                        </a>
                                    </div>
                                    <div class="collapse  " id="collapse_events">
                                        <ul class="p-3 mb-0 list-unstyled">
                                           @php
                                            $showEvents = '';
                                            foreach (json_decode(get_setting('Events'), true) as $key => $event) {
                                                if (isset($selected_event) && $selected_event == $event['value']) {
                                                    $showEvents = 'show';
                                                }
                                            }
                                        @endphp
                                        @foreach (json_decode(get_setting('Events'), true) as $key => $event)
                                                <li class="mb-3 text-dark">
                                                    <div class="form-check">
                                                        <input type="radio"
                                                            class="form-check-input text-reset fs-14 hov-text-primary "
                                                            {{ isset($selected_event) && $event['value'] == $selected_event ? 'checked' : '' }}
                                                            value="{{ $event['value'] }}" name="event"
                                                            onchange="filter()">
                                                        <label
                                                            class="form-check-label {{ isset($selected_event) && $event['value'] == $selected_event ? 'text-danger' : '' }}"
                                                            for="tag_{{ $event['value'] }}">
                                                            {{ $event['value'] }}
                                                        </label>
                                                    </div>
                                                </li>
                                                </li>
                                        @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @endif
                                @if (get_setting('close_type') != null)
                                    <div class="border-0 mb-3">
                                        <div class="fs-16 fw-700 py-1 px-2 bg-white">
                                            <a href="#collapse_close" style="color: #6a6a6a !important;" class="dropdown-toggle filter-section collapsed text-dark d-flex align-items-center justify-content-between" data-toggle="collapse">
                                                {{ translate('close_type') }}
                                            </a>
                                        </div>
                                        <div class="collapse  " id="collapse_close">
                                            <ul class="p-3 mb-0 list-unstyled">
                                                @php
                                                $showEvents = '';
                                                foreach (json_decode(get_setting('close_type'), true) as $key => $close_type) {
                                                if (isset($selected_close_type) && $selected_close_type == $close_type['value']) {
                                                $showEvents = 'show';
                                                }
                                                }
                                                @endphp
                                                @foreach (json_decode(get_setting('close_type'), true) as $key => $close_type)
                                                <li class="mb-3 text-dark">
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input text-reset fs-14 hov-text-primary " {{ isset($selected_close_type) && $close_type['value'] == $selected_close_type ? 'checked' : '' }} value="{{ $close_type['value'] }}" name="close_type" onchange="filter()">
                                                        <label class="form-check-label {{ isset($selected_close_type) && $close_type['value'] == $selected_close_type ? 'text-danger' : '' }}" for="tag_{{ $close_type['value'] }}">
                                                            {{ $close_type['value'] }}
                                                        </label>
                                                    </div>
                                                </li>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                @if (get_setting('hand_type') != null)
                                    <div class="border-0 mb-3">
                                        <div class="fs-16 fw-700 py-1 px-2 bg-white">
                                            <a href="#collapse_hands" style="color: #6a6a6a !important;" class="dropdown-toggle filter-section collapsed text-dark d-flex align-items-center justify-content-between" data-toggle="collapse">
                                                {{ translate('hand_type') }}
                                            </a>
                                        </div>
                                        <div class="collapse  " id="collapse_hands">
                                            <ul class="p-3 mb-0 list-unstyled">
                                                @php
                                                $showEvents = '';
                                                foreach (json_decode(get_setting('hand_type'), true) as $key => $hand_type) {
                                                if (isset($selected_hand_type) && $selected_hand_type == $hand_type['value']) {
                                                $showEvents = 'show';
                                                }
                                                }
                                                @endphp
                                                @foreach (json_decode(get_setting('hand_type'), true) as $key => $hand_type)
                                                <li class="mb-3 text-dark">
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input text-reset fs-14 hov-text-primary " {{ isset($selected_hand_type) && $hand_type['value'] == $selected_hand_type ? 'checked' : '' }} value="{{ $hand_type['value'] }}" name="hand_type" onchange="filter()">
                                                        <label class="form-check-label {{ isset($selected_hand_type) && $hand_type['value'] == $selected_hand_type ? 'text-danger' : '' }}" for="tag_{{ $hand_type['value'] }}">
                                                            {{ $hand_type['value'] }}
                                                        </label>
                                                    </div>
                                                </li>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                @if (get_setting('fabric_type') != null)
                                    <div class="border-0 mb-3">
                                        <div class="fs-16 fw-700 py-1 px-2 bg-white">
                                            <a href="#collapse_fabric" style="color: #6a6a6a !important;" class="dropdown-toggle filter-section collapsed text-dark d-flex align-items-center justify-content-between" data-toggle="collapse">
                                                {{ translate('fabric_type') }}
                                            </a>
                                        </div>
                                        <div class="collapse  " id="collapse_fabric">
                                            <ul class="p-3 mb-0 list-unstyled">
                                                @php
                                                $showEvents = '';
                                                foreach (json_decode(get_setting('fabric_type'), true) as $key => $fabric_type) {
                                                if (isset($selected_fabric_type) && $selected_fabric_type == $fabric_type['value']) {
                                                $showEvents = 'show';
                                                }
                                                }
                                                @endphp
                                                @foreach (json_decode(get_setting('fabric_type'), true) as $key => $fabric_type)
                                                <li class="mb-3 text-dark">
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input text-reset fs-14 hov-text-primary " {{ isset($selected_fabric_type) && $fabric_type['value'] == $selected_fabric_type ? 'checked' : '' }} value="{{ $fabric_type['value'] }}" name="fabric_type" onchange="filter()">
                                                        <label class="form-check-label {{ isset($selected_fabric_type) && $fabric_type['value'] == $selected_fabric_type ? 'text-danger' : '' }}" for="tag_{{ $fabric_type['value'] }}">
                                                            {{ $fabric_type['value'] }}
                                                        </label>
                                                    </div>
                                                </li>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                 @if (get_setting('seasons') != null)
                                    <div class="border-0 mb-3">
                                        <div class="fs-16 fw-700 py-1 px-2 bg-white">
                                            <a href="#collapse_seasons" style="color: #6a6a6a !important;"
                                                class="dropdown-toggle filter-section collapsed text-dark d-flex align-items-center justify-content-between"
                                                data-toggle="collapse">
                                                {{ translate('seasons') }}
                                            </a>
                                        </div>
                                        <div class="collapse  " id="collapse_seasons">
                                            <ul class="p-3 mb-0 list-unstyled">
                                               @php
                                                $showseasons = '';
                                                foreach (json_decode(get_setting('seasons'), true) as $key => $season) {
                                                    if (isset($selected_season) && $selected_season == $season['value']) {
                                                        $showseasons = 'show';
                                                    }
                                                }
                                            @endphp
                                            @foreach (json_decode(get_setting('seasons'), true) as $key => $season)
                                                    <li class="mb-3 text-dark">
                                                        <div class="form-check">
                                                            <input type="radio"
                                                                class="form-check-input text-reset fs-14 hov-text-primary "
                                                                {{ isset($selected_season) && $season['value'] == $selected_season ? 'checked' : '' }}
                                                                value="{{ $season['value'] }}" name="season"
                                                                onchange="filter()">
                                                            <label
                                                                class="form-check-label {{ isset($selected_season) && $season['value'] == $selected_season ? 'text-danger' : '' }}"
                                                                for="tag_{{ $season['value'] }}">
                                                                {{ $season['value'] }}
                                                            </label>
                                                        </div>
                                                    </li>
                                                    </li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Contents -->
                <div id="products-container" class="col-xl-9 mt-3"> 
                        <button type="button" class="btn btn-icon p-0 d-lg-none d-block " data-toggle="class-toggle"
                            data-target=".aiz-filter-sidebar">
                            <i class="la la-filter la-2x"></i>
                         </button> 
                    @if (count($products) > 0)
                        <!-- Products -->
                        <div
                            class="row gutters-16 row-cols-xxl-3 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-2  ">
                            @foreach ($products as $key => $product)
                                <?php $uniqueID = uniqid('prefix_' . $product->id . '_'); ?>
                                <div class="col" style="margin-bottom: 30px !important">
                                    @include('frontend.partials.product_box_1', [
                                        'product' => $product,
                                        'uniqueID' => $uniqueID,
                                    ])
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-6 d-flex justify-content-start align-items*center">
                                <div class="aiz-pagination">
                                    {{ $products->appends(request()->input())->links() }}
                                </div>
                            </div>
                            <div class="col-6 d-none d-lg-block shop-result"> </div>
                            
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>
    @include('frontend.partials.tips')
     <div style="display: none" id="getDataLoaingGif">
        <div class="d-flex align-items-center justify-content-center w-100 h-100">
            <img class="size-150px" src="{{ static_asset('assets/img/getDataLoding.gif') }}" alt="">
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function filter() {
            $('#search-form').submit();
        }

        function rangefilter(arg) {
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
    </script>
         <script type="text/javascript">
        $(window).on('hashchange', function() {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                } else {
                    getData(page);
                }
            }
        });
        $(document).ready(function() {
            $('#getDataLoaingGif').hide().fadeOut('slow') ;
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                $('li').removeClass('active');
                $(this).parent('li').addClass('active');
                var myurl = $(this).attr('href');
                var page = $(this).attr('href').split('page=')[1];
                getData(page);
            });
        });

        function getData(page) {
            $('#getDataLoaingGif').show().fadeIn('slow');

            $.ajax({
                url: '?page=' + page,
                type: "get",
                datatype: "html"
            }).done(function(data) {
            $('#getDataLoaingGif').hide().fadeOut('slow') ;
                $("#products-container").empty().html(data);
                console.log(location.hash)
                location.hash = page;
                window.scrollTo(0, 0);
                history.pushState(null, null, '?page=' + page);
            }).fail(function(jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        }
    </script>
@endsection
