@extends('backend.layouts.app')
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h1 class="mb-0 h6">{{ translate('Edit Product') }}</h5>
    </div>
    <div class="">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="form form-horizontal mar-top" action="{{ route('products.update', $product->id) }}" method="POST"
            enctype="multipart/form-data" id="choice_form">
            <div class="row">
                <div class="col-lg-12">
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    <input  type="hidden"  name="item_group_id" value="{{ $product->item_group_id == null ? Str::random(15) : $product->item_group_id }}">

                    @csrf
                    <div class="card">
                        <ul class="nav nav-tabs nav-fill border-light">
                            @foreach (\App\Models\Language::all() as $key => $language)
                                <li class="nav-item">
                                    <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                                        href="{{ route('products.admin.edit', ['id' => $product->id, 'lang' => $language->code]) }}">
                                        <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                            height="11" class="mr-1">
                                        <span>{{ $language->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Product Name') }} <i
                                        class="las la-language text-danger"
                                        title="{{ translate('Translatable') }}"></i></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name"
                                        placeholder="{{ translate('Product Name') }}"
                                        value="{{ $product->getTranslation('name', $lang) }}" required>
                                </div>
                            </div>
                            <div class="form-group row" id="category">
                                <label class="col-lg-3 col-from-label">{{ translate('Category') }}</label>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                        data-selected="{{ $product->category_id }}" data-live-search="true" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}
                                            </option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                                @include('categories.child_category', [
                                                    'child_category' => $childCategory,
                                                ])
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="sub_category">
                                <label class="col-md-3 col-from-label">{{ translate('Sub Categories') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="sub_category_id[]"
                                        id="sub_category_id" data-live-search="true" multiple required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @if (in_array($category->id, $defaultSelectedCategoryIds)) selected @endif>
                                                {{ $category->getTranslation('name') }}
                                            </option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                                @php
                                                    $value = null;
                                                    for ($i = 0; $i < $childCategory->level; $i++) {
                                                        $value .= '--';
                                                    }
                                                @endphp
                                                <option value="{{ $childCategory->id }}"
                                                    @if (in_array($childCategory->id, $defaultSelectedCategoryIds)) selected @endif>
                                                    {{ $value . ' ' . $childCategory->getTranslation('name') }}</option>
                                                @if ($childCategory->categories)
                                                    @foreach ($childCategory->categories as $subChildCategory)
                                                        @php
                                                            $subValue = null;
                                                            for ($i = 0; $i < $subChildCategory->level; $i++) {
                                                                $subValue .= '--';
                                                            }
                                                        @endphp
                                                        <option value="{{ $subChildCategory->id }}">
                                                            {{ $subValue . ' ' . $subChildCategory->getTranslation('name') }}
                                                        </option>
                                                        @if ($subChildCategory->categories)
                                                            @foreach ($subChildCategory->categories as $subSubChildCategory)
                                                                @include('categories.child_category', [
                                                                    'child_category' => $subSubChildCategory,
                                                                ])
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="brand">
                                <label class="col-lg-3 col-from-label">{{ translate('Brand') }}</label>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                        data-live-search="true">
                                        <option value="">{{ translate('Select Brand') }}</option>
                                        @foreach (\App\Models\Brand::all() as $brand)
                                            <option value="{{ $brand->id }}"
                                                @if ($product->brand_id == $brand->id) selected @endif>
                                                {{ $brand->getTranslation('name') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Unit') }} <i
                                        class="las la-language text-danger" title="{{ translate('Translatable') }}"></i>
                                </label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="unit"
                                        placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}"
                                        value="{{ $product->getTranslation('unit', $lang) }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Weight') }}
                                    <small>({{ translate('In Kg') }})</small></label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="weight"
                                        value="{{ $product->weight }}" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Views') }}</label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="views" step="1"
                                        value="{{ $product->views }}" placeholder="1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Minimum Purchase Qty') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" lang="en" class="form-control" name="min_qty"
                                        value="{{ $product->min_qty > 0 ? $product->min_qty : 1 }} " min="1"
                                        required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Tags') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                        id="tags" value="{{ $product->tags }}"
                                        placeholder="{{ translate('Type to add a tag') }}" data-role="tagsinput">
                                </div>
                            </div>
                            <div class="product_details_details">

                                @if ((is_array(old('details_keys')) && is_array(old('details_values'))) || ($product->product_details ?? false))
                                    @php
                                        $details_keys = old('details_keys');
                                        $details_values = old('details_values');
                                        if (is_array($details_keys) && is_array($details_values)) {
                                            $product_details = array_combine($details_keys, $details_values);
                                        }
                                    @endphp

                                    @foreach ($product_details ?? json_decode($product->product_details ?? []) as $key => $value)
                                        <div class="form-group row">
                                            <label class="col-md-3 col-from-label">{{ translate('Product Details') }}
                                                <span class="text-danger">*</span></label>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control " name="details_keys[]"
                                                    value="{{ $key }}" placeholder="{{ translate('Name') }}">

                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control " name="details_values[]"
                                                    value="{{ $value }}" placeholder="{{ translate('Value') }}">

                                            </div>
                                            <div class="col-md-auto">
                                                <div class="form-group">
                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".row">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ translate('Product Details') }} <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control " name="details_keys[]"
                                                placeholder="{{ translate('Name') }}">

                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control " name="details_values[]"
                                                placeholder="{{ translate('Value') }}">

                                        </div>
                                        <div class="col-md-auto">
                                            <div class="form-group">
                                                <button type="button"
                                                    class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                    data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='
                            <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Product Details') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control " name="details_keys[]"
                                    placeholder="{{ translate('Name') }}">

                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control " name="details_values[]"
                                    placeholder="{{ translate('Value') }}">

                            </div>
                            <div class="col-md-auto">
                                <div class="form-group">
                                    <button type="button"
                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                        data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>
                            </div>
                            '
                                data-target=".product_details_details">
                                {{ translate('Add New') }}
                            </button>
                            @if (addon_is_activated('pos_system'))
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ translate('Barcode') }}</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="barcode"
                                            placeholder="{{ translate('Barcode') }}" value="{{ $product->barcode }}">
                                    </div>
                                </div>
                            @endif

                            @if (addon_is_activated('refund_request'))
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ translate('Refundable') }}</label>
                                    <div class="col-lg-8">
                                        <label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">
                                            <input type="checkbox" name="refundable"
                                                @if ($product->refundable == 1) checked @endif value="1">
                                            <span class="slider round"></span></label>
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Images') }}</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('Gallery Images') }} (w 522 x h 800)</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image"
                                        data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="photos" value="{{ $product->photos }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('Thumbnail Image') }}
                                    <small>(w 522 x h 800)</small></label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="thumbnail_img" value="{{ $product->thumbnail_img }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                                    <label class="col-lg-3 col-from-label">{{translate('Gallery Images')}}</label>
                        <div class="col-lg-8">
                            <div id="photos">
                                @if (is_array(json_decode($product->photos)))
                                @foreach (json_decode($product->photos) as $key => $photo)
                                <div class="col-md-4 col-sm-4 col-xs-6">
                                    <div class="img-upload-preview">
                                        <img loading="lazy"  src="{{ uploaded_asset($photo) }}" alt="" class="img-responsive">
                                            <input type="hidden" name="previous_photos[]" value="{{ $photo }}">
                                            <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                            </div> --}}
                            {{-- <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Thumbnail Image')}} <small>(290x300)</small></label>
                            <div class="col-lg-8">
                                <div id="thumbnail_img">
                                    @if ($product->thumbnail_img != null)
                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                        <div class="img-upload-preview">
                                            <img loading="lazy"  src="{{ uploaded_asset($product->thumbnail_img) }}" alt="" class="img-responsive">
                                            <input type="hidden" name="previous_thumbnail_img" value="{{ $product->thumbnail_img }}">
                                            <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div> --}}
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Product Video Name') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" value="{{ $product->video_name }}"
                                        name="video_name" placeholder="{{ translate('Product Video Name') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Product Design') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" multiple data-live-search="true"
                                        name="design[]" id="design">
                                        <option>
                                            {{ translate('select design') }}
                                        </option>
                                        @if (get_setting('Designes') != null)
                                            @foreach (json_decode(get_setting('Designes'), true) as $key => $design)
                                                <option value="{{ $design['value'] }}"
                                                    {{ $product->design && in_array($design['value'], json_decode($product->design, true)) ? 'selected' : '' }}>
                                                    {{ $design['value'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Product Event') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" multiple data-live-search="true"
                                        name="event[]" id="event">
                                        <option>
                                            {{ translate('select event') }}
                                        </option>

                                        @if (get_setting('Events') != null)
                                            @foreach (json_decode(get_setting('Events'), true) as $event)
                                                <option value="{{ $event['value'] }}"
                                                    {{ $product->event && in_array($event['value'], json_decode($product->event, true)) ? 'selected' : '' }}>
                                                    {{ $event['value'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('fabric_type') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        name="fabric_type[]" id="fabric_type" multiple size="20">
                                        <option>
                                            {{ translate('select Fabric Type') }}
                                        </option>
                                        @if (get_setting('fabric_type') != null)
                                            @foreach (json_decode(get_setting('fabric_type'), true) as $key => $fabric)
                                                <option value=" {{ $fabric['value'] }}"
                                                    {{ $product->fabric_type && in_array($fabric['value'], json_decode($product->fabric_type, true)) ? 'selected' : '' }}>
                                                    {{ $fabric['value'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('close_type') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        name="close_type[]" id="close_type" multiple size="20">
                                        <option>
                                            {{ translate('select Close Type') }}
                                        </option>
                                        @if (get_setting('close_type') != null)
                                            @foreach (json_decode(get_setting('close_type'), true) as $key => $close)
                                                <option value=" {{ $close['value'] }}"
                                                    {{ $product->close_type && in_array($close['value'], json_decode($product->close_type, true)) ? 'selected' : '' }}>
                                                    {{ $close['value'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('hand_type') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        name="hand_type[]" id="hand_type" multiple size="20">
                                        <option>
                                            {{ translate('select Hands Type') }}
                                        </option>
                                        @if (get_setting('hand_type') != null)
                                            @foreach (json_decode(get_setting('hand_type'), true) as $key => $hand)
                                                <option value=" {{ $hand['value'] }}"
                                                    {{ $product->hand_type && in_array($hand['value'], json_decode($product->hand_type, true)) ? 'selected' : '' }}>
                                                    {{ $hand['value'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Seasons') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        name="seasons[]" id="seasons" multiple size="20">
                                        <option>
                                            {{ translate('select Season') }}
                                        </option>
                                        @if (get_setting('seasons') != null)
                                            @foreach (json_decode(get_setting('seasons'), true) as $key => $season)
                                                <option value=" {{ $season['value'] }}"
                                                    {{ $product->seasons != null && in_array($season['value'], json_decode($product->seasons, true)) ? 'selected' : '' }}>
                                                    {{ $season['value'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Question') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" multiple data-live-search="true"
                                        name="questions_answer[]" id="questions_answer">
                                        <option>
                                            {{ translate('select questions') }}
                                        </option>

                                        @foreach (\App\Models\QuestionAnswer::get() as $key => $q)
                                            <option value="{{ $q->id }}"
                                                {{ in_array($q->id, $product->questions->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $q->getTranslation('question') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>{{ translate('Custom Questions') }}</label>
                                <div class="custom-questions-target">
                                    @php
                                    $quests = \App\Models\CustomQuestion::where('product_id',$product->id)->get() ;
                                    @endphp
                                    @if ($quests)
                                    @foreach ($quests as $key => $quest)
                                    <div class="row gutters-5">
                                        <div class="col-md-5 form-group row">
                                            <label class="col-lg-3 col-from-label">{{ translate('Question') }} <i class="las la-language text-danger" title="{{ translate('Translatable') }}"></i></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="custom_question[]" placeholder="{{ translate('Question') }}" value="{{ $quest->question}}">
                                            </div>
                                        </div>
                                        <div class="col-md-5 form-group row">
                                            <label class="col-lg-3 col-from-label">{{ translate('Answer') }} <i class="las la-language text-danger" title="{{ translate('Translatable') }}"></i></label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="custom_answer[]" placeholder="{{ translate('Answer') }}" value="{{ $quest->answer }}">
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="form-group">
                                                <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more" data-content='
                          <div class="row gutters-5">
                                  <div class="col-md-5 form-group row">
                                      <label class="col-lg-3 col-from-label">{{ translate("Question") }} <i class="las la-language text-danger" title="{{ translate("Translatable") }}"></i></label>
                                      <div class="col-lg-8">
                                          <input type="text" class="form-control" name="custom_question[]" placeholder="{{ translate("Question") }}" value="">
                                      </div>
                                  </div>
                                  <div class="col-md-5 form-group row">
                                      <label class="col-lg-3 col-from-label">{{ translate("Answer") }} <i class="las la-language text-danger" title="{{ translate("Translatable") }}"></i></label>
                                      <div class="col-lg-8">
                                          <input type="text" class="form-control" name="custom_answer[]" placeholder="{{ translate("Answer") }}" value="">
                                      </div>
                                  </div>
                                  <div class="col-md-auto">
                                      <div class="form-group">
                                          <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                              <i class="las la-times"></i>
                                          </button>
                                      </div>
                                  </div>
                              </div>
                                                    ' data-target=".custom-questions-target">
                                    {{ translate('Add New') }}
                                </button>
                            </div>
                    
                    
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Videos') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Video Provider') }}</label>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" name="video_provider"
                                        id="video_provider">
                                        <option value="youtube" <?php if ($product->video_provider == 'youtube') {
                                            echo 'selected';
                                        } ?>>{{ translate('Youtube') }}</option>
                                        <option value="dailymotion" <?php if ($product->video_provider == 'dailymotion') {
                                            echo 'selected';
                                        } ?>>{{ translate('Dailymotion') }}
                                        </option>
                                        <option value="vimeo" <?php if ($product->video_provider == 'vimeo') {
                                            echo 'selected';
                                        } ?>>{{ translate('Vimeo') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Video Link') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="video_link"
                                        value="{{ $product->video_link }}"
                                        placeholder="{{ translate('Video Link') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Variation') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row gutters-5">
                                <div class="col-lg-3">
                                    <input type="text" class="form-control" value="{{ translate('Colors') }}"
                                        disabled>
                                </div>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        data-selected-text-format="count" name="colors[]" id="colors" multiple>
                                        @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                            <option value="{{ $color->code }}" data-color-name="{{ $color->name }}"
                                                data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"
                                                <?php if (in_array($color->code, json_decode($product->colors))) {
                                                    echo 'selected';
                                                } ?>></option>
                                        @endforeach
                                    </select>
                                    <span id="selected_colors" class="m-2 mt-4 p-2"></span>
                                </div>
                                <div class="col-lg-1">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" name="colors_active" <?php if (count(json_decode($product->colors)) > 0) {
                                            echo 'checked';
                                        } ?>>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row gutters-5">
                                <div class="col-lg-3">
                                    <input type="text" class="form-control" value="{{ translate('Attributes') }}"
                                        disabled>
                                </div>
                                <div class="col-lg-8">
                                    <select name="choice_attributes[]" id="choice_attributes"
                                        data-selected-text-format="count" data-live-search="true"
                                        class="form-control aiz-selectpicker" multiple
                                        data-placeholder="{{ translate('Choose Attributes') }}">
                                        @foreach (\App\Models\Attribute::all() as $key => $attribute)
                                            <option value="{{ $attribute->id }}"
                                                @if ($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))) selected @endif>
                                                {{ $attribute->getTranslation('name') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="">
                                <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}
                                </p>
                                <br>
                            </div>

                            <div class="customer_choice_options" id="customer_choice_options">
                                @foreach (json_decode($product->choice_options) as $key => $choice_option)
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <input type="hidden" name="choice_no[]"
                                                value="{{ $choice_option->attribute_id }}">
                                            <input type="text" class="form-control" name="choice[]"
                                                value="{{ optional(\App\Models\Attribute::find($choice_option->attribute_id))->getTranslation('name') }}"
                                                placeholder="{{ translate('Choice Title') }}" disabled>
                                        </div>
                                        <div class="col-lg-8">
                                            @php
                                                $attrs = '';
                                            @endphp
                                            <select class="form-control aiz-selectpicker attribute_choice"
                                                data-live-search="true"
                                                name="choice_options_{{ $choice_option->attribute_id }}[]" multiple>
                                                @foreach (\App\Models\AttributeValue::where('attribute_id', $choice_option->attribute_id)->get() as $row)
                                                    <option value="{{ $row->value }}"
                                                        @if (in_array($row->value, $choice_option->values)) selected @endif>
                                                        {{ $row->value }}
                                                    </option>
                                                    @php
                                                        if (in_array($row->value, $choice_option->values)) {
                                                            $attrs .= $row->value . ' , ';
                                                        }
                                                    @endphp
                                                @endforeach
                                            </select>
                                            <span id="choice_options__{{ $choice_option->attribute_id }}"
                                                class="m-2 p-2">{{ substr($attrs, 0, -2) }}</span>
                                            {{-- <input type="text" class="form-control aiz-tag-input" name="choice_options_{{ $choice_option->attribute_id }}[]" placeholder="{{ translate('Enter choice values') }}" value="{{ implode(',', $choice_option->values) }}" data-on-change="update_sku"> --}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product price + stock') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Unit price') }}</label>
                                <div class="col-lg-6">
                                    <input type="text" placeholder="{{ translate('Unit price') }}" name="unit_price"
                                        id="unit_price" class="form-control" value="{{ $product->unit_price }}"
                                        required>
                                </div>

                                <div class="col-lg-1">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" name="unit_price_checkbox">
                                        <span></span>
                                    </label>
                                </div>

                            </div>
                            <div class="form-group row d-none ">
                                <label class="col-lg-3 col-from-label">{{ translate('WholeSale price') }}</label>
                                <div class="col-lg-6">
                                    <input type="text" placeholder="{{ translate('WholeSale price') }}"
                                        name="wholesale_price" class="form-control" id="wholesale_price"
                                        value="{{ $product->wholesale_price }}" required>
                                </div>

                                <div class="col-lg-1">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" name="wholesale_price_checkbox">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row d-none">
                                <label class="col-lg-3 col-from-label">{{ translate('WholeSale Variant Price') }}</label>
                                <div class="col-lg-6">
                                    <input type="text" placeholder="{{ translate('WholeSale Variant Price') }}"
                                        name="wholesale_price_variant" id="wholesale_price_variant" class="form-control"
                                        value="{{ $product->wholesale_price_variant }}" required>
                                </div>
                                <div class="col-lg-1">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" name="wholesale_price_variant_checkbox">
                                        <span></span>
                                    </label>
                                </div>

                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-3 col-from-label">{{ translate('Cost Price') }}</label>
                                <div class="col-lg-6">
                                    <input type="text" placeholder="{{ translate('Cost Price') }}" name="cost_price"
                                        id="cost_price" class="form-control" value="{{ $product->cost_price }}"
                                        required>
                                </div>
                                <div class="col-lg-1">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" name="cost_price_checkbox">
                                        <span></span>
                                    </label>
                                </div>

                            </div>

                            @php
                                $start_date = date('d-m-Y H:i:s', $product->discount_start_date);
                                $end_date = date('d-m-Y H:i:s', $product->discount_end_date);
                            @endphp

                            <div class="form-group row">
                                <label class="col-sm-3 col-from-label"
                                    for="start_date">{{ translate('Discount Date Range') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control aiz-date-range"
                                        @if ($product->discount_start_date && $product->discount_end_date) value="{{ $start_date . ' to ' . $end_date }}" @endif
                                        name="date_range" placeholder="{{ translate('Select Date') }}"
                                        data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to "
                                        autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Discount') }}</label>
                                <div class="col-lg-6">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Discount') }}" name="discount" class="form-control"
                                        value="{{ $product->discount }}" required>
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control aiz-selectpicker" name="discount_type" required>
                                        <option value="amount" <?php if ($product->discount_type == 'amount') {
                                            echo 'selected';
                                        } ?>>{{ translate('Flat') }}</option>
                                        <option value="percent" <?php if ($product->discount_type == 'percent') {
                                            echo 'selected';
                                        } ?>>{{ translate('Percent') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Guarantee') }} </label>
                                <div class="col-md-3">
                                    <select class="form-control aiz-selectpicker" name="guarantee_type">
                                        <option value="normal" <?php if ($product->guarantee_type == 'normal') {
                                            echo 'selected';
                                        } ?>>{{ translate('Normal') }}</option>
                                        <option value="golden" <?php if ($product->guarantee_type == 'golden') {
                                            echo 'selected';
                                        } ?>>{{ translate('Golden') }}</option>
                                    </select>
                                </div>
                            </div>
                            @if (addon_is_activated('club_point'))
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">
                                        {{ translate('Set Point') }}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0"
                                            value="{{ $product->earn_point }}" step="1"
                                            placeholder="{{ translate('1') }}" name="earn_point" class="form-control">
                                    </div>
                                </div>
                            @endif

                            <div>
                                <div class="form-group row" id="quantity">
                                    <label class="col-lg-3 col-from-label">{{ translate('Quantity') }}</label>
                                    <div class="col-lg-6">
                                        <input type="number" lang="en"
                                            value="{{ optional($product->stocks->first())->qty }}" step="1"
                                            placeholder="{{ translate('Quantity') }}" name="current_stock"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">
                                        {{ translate('SKU') }}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" placeholder="{{ translate('SKU') }}"
                                            value="{{ optional($product->stocks->first())->sku }}" name="sku"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">
                                    {{ translate('External link') }}
                                </label>
                                <div class="col-md-9">
                                    <input type="text" placeholder="{{ translate('External link') }}"
                                        name="external_link" value="{{ $product->external_link }}"
                                        class="form-control">
                                    <small
                                        class="text-muted">{{ translate('Leave it blank if you do not use external site link') }}</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">
                                    {{ translate('External link button text') }}
                                </label>
                                <div class="col-md-9">
                                    <input type="text" placeholder="{{ translate('External link button text') }}"
                                        name="external_link_btn" value="{{ $product->external_link_btn }}"
                                        class="form-control">
                                    <small
                                        class="text-muted">{{ translate('Leave it blank if you do not use external site link') }}</small>
                                </div>
                            </div>
                            <br>
                            <div class="sku_combination" id="sku_combination">

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Description') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Description') }} <i
                                        class="las la-language text-danger"
                                        title="{{ translate('Translatable') }}"></i></label>
                                <div class="col-lg-9">
                                    <textarea class="aiz-text-editor" name="description">{{ $product->getTranslation('description', $lang) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Long Description') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Long Description') }} <i
                                        class="las la-language text-danger"
                                        title="{{ translate('Translatable') }}"></i></label>
                                <div class="col-lg-9">
                                    <textarea class="aiz-text-editor" name="long_description">{{ $product->getTranslation('long_description', $lang) }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!--                <div class="card">
                                                        <div class="card-header">
                                                            <h5 class="mb-0 h6">{{ translate('Product Shipping Cost') }}</h5>
                                                        </div>
                                                        <div class="card-body">

                                                        </div>
                                                    </div>-->

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('PDF Specification') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('PDF Specification') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="pdf" value="{{ $product->pdf }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Meta Title') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="meta_title"
                                        value="{{ $product->meta_title }}"
                                        placeholder="{{ translate('Meta Title') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Description') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('Meta Images') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image"
                                        data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="meta_img" value="{{ $product->meta_img }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ translate('Slug') }}</label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="{{ translate('Slug') }}" id="slug"
                                        name="slug" value="{{ $product->slug }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6" class="dropdown-toggle" data-toggle="collapse" data-target="#collapse_2">
                            {{ translate('Shipping Configuration') }}
                        </h5>
                    </div>
                    <div class="card-body collapse show" id="collapse_2">
                        @if (get_setting('shipping_type') == 'product_wise_shipping')
                            <div class="form-group row">
                                <label class="col-lg-6 col-from-label">{{ translate('Free Shipping') }}</label>
                                <div class="col-lg-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="shipping_type" value="free"
                                            @if ($product->shipping_type == 'free') checked @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-6 col-from-label">{{ translate('Flat Rate') }}</label>
                                <div class="col-lg-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="shipping_type" value="flat_rate"
                                            @if ($product->shipping_type == 'flat_rate') checked @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="flat_rate_shipping_div" style="display: none">
                                <div class="form-group row">
                                    <label class="col-lg-6 col-from-label">{{ translate('Shipping cost') }}</label>
                                    <div class="col-lg-6">
                                        <input type="number" lang="en" min="0"
                                            value="{{ $product->shipping_cost }}" step="0.01"
                                            placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    class="col-md-6 col-from-label">{{ translate('Is Product Quantity Mulitiply') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="is_quantity_multiplied" value="1"
                                            @if ($product->is_quantity_multiplied == 1) checked @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        @else
                            <p>
                                {{ translate('Product wise shipping cost is disable. Shipping cost is configured from here') }}
                                <a href="{{ route('shipping_configuration.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index', 'shipping_configuration.edit', 'shipping_configuration.update']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Shipping Configuration') }}</span>
                                </a>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Low Stock Quantity Warning') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{ translate('Quantity') }}
                            </label>
                            <input type="number" name="low_stock_quantity" value="{{ $product->low_stock_quantity }}"
                                step="1" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">
                            {{ translate('Stock Visibility State') }}
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{ translate('Show Stock Quantity') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="quantity"
                                        @if ($product->stock_visibility_state == 'quantity') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{ translate('Show Stock With Text Only') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="text"
                                        @if ($product->stock_visibility_state == 'text') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{ translate('Hide Stock') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="hide"
                                        @if ($product->stock_visibility_state == 'hide') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Cash On Delivery') }}</h5>
                    </div>
                    <div class="card-body">
                        @if (get_setting('cash_payment') == '1')
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                        <div class="col-md-6">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="checkbox" name="cash_on_delivery" value="1"
                                                    @if ($product->cash_on_delivery == 1) checked @endif>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p>
                                {{ translate('Cash On Delivery option is disabled. Activate this feature from here') }}
                                <a href="{{ route('activation.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index', 'shipping_configuration.edit', 'shipping_configuration.update']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Cash Payment Activation') }}</span>
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Featured') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="featured" value="1"
                                                @if ($product->featured == 1) checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Back Order') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="back_order" value="1"
                                                @if ($product->back_order == 1) checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Counter Down') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="counter_down" value="1"
                                                @if ($product->counter_down == 1) checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('trending') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="trending" value="1"
                                        {{ old('trending') == 1 || $product->trending == 1 ? 'checked' : '' }}>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('best_selling') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="best_selling"
                                        {{ old('best_selling') == 1 || $product->best_selling == 1 ? 'checked' : '' }}
                                        value="1">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Todays Deal') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="todays_deal" value="1"
                                                @if ($product->todays_deal == 1) checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Flash Deal') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{ translate('Add To Flash') }}
                            </label>
                            <select class="form-control aiz-selectpicker" name="flash_deal_id" id="video_provider">
                                <option value="">{{ translate('Choose Flash Title') }}</option>
                                @foreach (\App\Models\FlashDeal::where('status', 1)->get() as $flash_deal)
                                    <option value="{{ $flash_deal->id }}"
                                        @if ($product->flash_deal_product && $product->flash_deal_product->flash_deal_id == $flash_deal->id) selected @endif>
                                        {{ $flash_deal->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">
                                {{ translate('Discount') }}
                            </label>
                            <input type="number" name="flash_discount" value="{{ $product->discount }}"
                                min="0" step="0.01" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">
                                {{ translate('Discount Type') }}
                            </label>
                            <select class="form-control aiz-selectpicker" name="flash_discount_type" id="">
                                <option value="">{{ translate('Choose Discount Type') }}</option>
                                <option value="amount" @if ($product->discount_type == 'amount') selected @endif>
                                    {{ translate('Flat') }}
                                </option>
                                <option value="percent" @if ($product->discount_type == 'percent') selected @endif>
                                    {{ translate('Percent') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class=" col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Estimate Shipping Time') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{ translate('Shipping Days') }}
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="est_shipping_days"
                                    value="{{ $product->est_shipping_days }}" min="1" step="1"
                                    placeholder="{{ translate('Shipping Days') }}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">{{ translate('Days') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('VAT & Tax') }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach (\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                            <label for="name">
                                {{ $tax->name }}
                                <input type="hidden" value="{{ $tax->id }}" name="tax_id[]">
                            </label>
                            @php
                                $tax_amount = 0;
                                $tax_type = '';
                                foreach ($tax->product_taxes as $row) {
                                    if ($product->id == $row->product_id) {
                                        $tax_amount = $row->tax;
                                        $tax_type = $row->tax_type;
                                    }
                                }
                            @endphp
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="number" lang="en" min="0" value="{{ $tax_amount }}"
                                        step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]"
                                        class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <select class="form-control aiz-selectpicker" name="tax_type[]">
                                        <option value="amount" @if ($tax_type == 'amount') selected @endif>
                                            {{ translate('Flat') }}
                                        </option>
                                        <option value="percent" @if ($tax_type == 'percent') selected @endif>
                                            {{ translate('Percent') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            <div class="col-12">
                <div class="mb-3 d-flex justify-content-end" id="update_btn">

                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            show_hide_shipping_div();
        });

        $("[name=shipping_type]").on("change", function() {
            show_hide_shipping_div();
        });

        function show_hide_shipping_div() {
            var shipping_val = $("[name=shipping_type]:checked").val();

            $(".flat_rate_shipping_div").hide();

            if (shipping_val == 'flat_rate') {
                $(".flat_rate_shipping_div").show();
            }
        }

        function add_more_customer_choice_option(i, name) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route('products.add-more-choice-option') }}',
                data: {
                    attribute_id: i
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    $('#customer_choice_options').append('\
                                            <div class="form-group row">\
                                                <div class="col-md-3">\
                                                    <input type="hidden" name="choice_no[]" value="' + i + '">\
                                                    <input type="text" class="form-control" name="choice[]" value="' +
                        name +
                        '" placeholder="{{ translate('Choice Title') }}" readonly>\
                                                </div>\
                                                <div class="col-md-8">\
                                                    <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_' +
                        i + '[]" multiple>\
                                                        ' + obj + '\
                                                    </select>\
                                                    <span id="choice_options_' + i + '" class="m-2 p-2"></span>\
                                                </div>\
                                            </div>');
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });


        }
        $('input[name="colors_active"]').on('change', function() {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors').prop('disabled', true);
                AIZ.plugins.bootstrapSelect('refresh');
            } else {
                $('#colors').prop('disabled', false);
                AIZ.plugins.bootstrapSelect('refresh');
            }
            update_sku();
        });

        $(document).on("change", ".attribute_choice", function() {
            update_sku();
        });

        $('#colors').on('change', function() {
            update_sku();
        });

        function delete_row(em) {
            $(em).closest('.form-group').remove();
            update_sku();
        }

        function delete_variant(em) {
            $(em).closest('.variant').remove();
        }

        function update_sku() {
            $('#update_btn').empty().html(`
                    <div class="c-preloader text-center p-3" style="margin-top:20%">
                        <i class="las la-spinner la-spin la-3x"></i>
                    </div>
            `);
            $.ajax({
                type: "POST",
                url: '{{ route('products.sku_combination_edit') }}',
                data: $('#choice_form').serialize(),
                success: function(data) {
                    $('#sku_combination').html(data);
                    setTimeout(() => {
                        AIZ.uploader.previewGenerate();
                    }, "500");
                    AIZ.plugins.fooTable();
                    if (data.length > 1) {
                        $('#show-hide-div').hide();
                    } else {
                        $('#show-hide-div').show();
                    }
                    $('#update_btn').empty().html(`
                        <button type="submit" name="button"
                        class="btn btn-info">{{ translate('Update Product') }}</button>
                    `);
                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors
                    console.error('AJAX Error: ' + status + ' ' + error);
                    console.log(xhr);
                }

            });
        }

        AIZ.plugins.tagify();

        $(document).ready(function() {
            update_sku();

            $('.remove-files').on('click', function() {
                $(this).parents(".col-md-4").remove();
            });
        });

        $('#choice_attributes').on('change', function() {
            $.each($("#choice_attributes option:selected"), function(j, attribute) {
                flag = false;
                $('input[name="choice_no[]"]').each(function(i, choice_no) {
                    if ($(attribute).val() == $(choice_no).val()) {
                        flag = true;
                    }
                });
                if (!flag) {
                    add_more_customer_choice_option($(attribute).val(), $(attribute).text());
                }
            });

            var str = @php echo $product->attributes @endphp;

            $.each(str, function(index, value) {
                flag = false;
                $.each($("#choice_attributes option:selected"), function(j, attribute) {
                    if (value == $(attribute).val()) {
                        flag = true;
                    }
                });
                if (!flag) {
                    $('input[name="choice_no[]"][value="' + value + '"]').parent().parent().remove();
                }
            });

            update_sku();
        });


        function showSelected(attr) {
            var name = $(attr).attr('name');
            name = name.slice(0, -2);

            // Get the selected values from the select element
            var selectedValues = $(attr).val();

            // Create a string by joining selected values with ','
            var selectedString = selectedValues.join(' , ');

            // Find the span by ID and update its content
            var span = $('#' + name);
            span.text(selectedString);
        }


        $(document).ready(function() {
            $('#colors').on('change', function() {
                showProductColors();
            });
        });

        showProductColors();

        function showProductColors() {
            var span = $('#selected_colors');
            // Get the selected options from the select element
            var selectedOptions = $('#colors option:selected');

            // Clear the content of the span
            span.empty();

            // Create a span for each selected color with its name
            selectedOptions.each(function() {
                var colorOption = $(this);
                var colorCode = colorOption.val();
                var colorName = colorOption.data('color-name');

                var colorSpan = $('<span class="size-15px d-inline-block mr-2 rounded border"></span>');
                colorSpan.css('background-color', colorCode);

                // Append the color span to the selected colors span
                span.append(colorSpan);

                // Append the color name to the selected colors span
                span.append('<span>' + colorName + '</span>');
            });
        }



        document.getElementById('sub_category_id').addEventListener('change', function() {
            var selectedValues = Array.from(this.selectedOptions).map(option => '"' + option.value + '"');
            document.getElementById('sub_category_ids').value = selectedValues.join(',\n    ');
        });
    </script>
@endsection
