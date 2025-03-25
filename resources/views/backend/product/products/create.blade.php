@extends('backend.layouts.app')
@section('content')
    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Add New Product') }}</h5>
    </div>
    <div class="">
        <!-- Error Meassages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="form form-horizontal mar-top" action="{{ route('products.store') }}" method="POST"
            enctype="multipart/form-data" id="choice_form">
            <div class="row">
                <div class="col-lg-12">
                    @csrf
                    <input type="hidden" name="added_by" value="admin">
                    <input type="hidden" name="item_group_id" value="{{ Str::random(15) }}">

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">اسم المنتج<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name" placeholder="اسم المنتج"
                                        onchange="update_sku()" required>
                                </div>
                            </div>
                            <div class="form-group row" id="category">
                                <label class="col-md-3 col-from-label">{{ translate('Category') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                        data-live-search="true" required>
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
                                            <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}
                                            </option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                                @include('categories.child_category', [
                                                    'child_category' => $childCategory,
                                                ])
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <!-- Hidden input to store comma-separated values -->
                                    <input type="hidden" name="sub_category_ids" id="sub_category_ids" value="">
                                </div>
                            </div>
                            <div class="form-group row" id="brand">
                                <label class="col-md-3 col-from-label">{{ translate('Brand') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                        data-live-search="true">
                                        <option value="">{{ translate('Select Brand') }}</option>
                                        @foreach (\App\Models\Brand::all() as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Unit') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="unit"
                                        placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" required value="قطعة">
                                </div>
                            </div>

                            <!--<div class="form-group row">-->
                            <!--    <label class="col-md-3 col-from-label">{{ translate('Unit') }}</label>-->
                            <!--    <div class="col-md-8">-->
                            <!--        <input type="text" class="form-control" name="unit"-->
                            <!--            placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" required>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Weight') }}
                                    <small>({{ translate('In Kg') }})</small></label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="weight" step="0.01" value="0.00"
                                        placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Views') }}</label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="views" step="1" value="1"
                                        placeholder="1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Minimum Purchase Qty') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" class="form-control" name="min_qty"
                                        value="1" min="1" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">الوسوم <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                        placeholder="{{ translate('Type and hit enter to add a tag') }}">
                                    <small
                                        class="text-muted">{{ translate('This is used for search. Input those words by which customer can find this product.') }}</small>
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
                                        <label class="col-md-3 col-from-label">تفاصيل المنتج<span
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
                                    <label class="col-md-3 col-from-label">{{ translate('Barcode') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="barcode"
                                            placeholder="{{ translate('Barcode') }}">
                                    </div>
                                </div>
                            @endif

                            @if (addon_is_activated('refund_request'))
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Refundable') }}</label>
                                    <div class="col-md-8">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="refundable" checked value="1">
                                            <span></span>
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
                                    for="signinSrEmail">{{ translate('Gallery Images') }}
                                    <small>(w 522 x h 800)</small></label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image"
                                        data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="photos" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small
                                        class="text-muted">{{ translate('These images are visible in product details page gallery.') }}</small>
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
                                        <input type="hidden" name="thumbnail_img" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small
                                        class="text-muted">{{ translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.') }}</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Product Video Name') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="video_name"
                                        placeholder="{{ translate('Product Video Name') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">التصميم<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" multiple
                                        name="design[]" id="design" size="20">
                                        <option>
                                            {{ translate('select design') }}
                                        </option>
                                        @if (get_setting('Designes') != null)
                                            @foreach (json_decode(get_setting('Designes'), true) as $key => $design)
                                                <option value=" {{ $design['value'] }}">
                                                    {{ $design['value'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">المناسبة<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" multiple
                                        name="event[]" id="event" size="20">
                                        <option>
                                            {{ translate('select event') }}
                                        </option>
                                        @if (get_setting('Events') != null)
                                            @foreach (json_decode(get_setting('Events')) as $key => $event)
                                                <option value="{{ $event->value }}">
                                                    {{ $event->value }}
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
                                    <select class="form-control aiz-selectpicker" data-live-search="true" multiple
                                        name="fabric_type[]" id="fabric_type" size="20">
                                        <option>
                                            {{ translate('select Fabric Type') }}
                                        </option>
                                        @if (get_setting('fabric_type') != null)
                                            @foreach (json_decode(get_setting('fabric_type'), true) as $key => $fabric)
                                                <option value=" {{ $fabric['value'] }}">
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
                                    <select class="form-control aiz-selectpicker" data-live-search="true" multiple
                                        name="close_type[]" id="close_type" size="20">
                                        <option>
                                            {{ translate('select Close Type') }}
                                        </option>
                                        @if (get_setting('close_type') != null)
                                            @foreach (json_decode(get_setting('close_type'), true) as $key => $close)
                                                <option value=" {{ $close['value'] }}">
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
                                                <option value=" {{ $hand['value'] }}">
                                                    {{ $hand['value'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Season') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" multiple
                                        name="seasons[]" id="seasons" size="20">
                                        <option>
                                            {{ translate('select Season') }}
                                        </option>
                                        @if (get_setting('seasons') != null)
                                            @foreach (json_decode(get_setting('seasons'), true) as $key => $season)
                                                <option value=" {{ $season['value'] }}">
                                                    {{ $season['value'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!--<div class="form-group row">-->
                            <!--        <label class="col-md-3 col-from-label">المناسبة<span-->
                            <!--                class="text-danger">*</span></label>-->
                            <!--        <div class="col-md-8">-->
                            <!--             <select class="form-control aiz-selectpicker" data-live-search="true" name="event" id="event" >-->
                            <!--                 <option >-->
                            <!--                        {{ translate('select event') }}-->
                            <!--                    </option>-->
                            <!--                    @if (get_setting('Events') != null)
    -->
                            <!--               @foreach (json_decode(get_setting('Events')) as $key => $event)
    -->
                            <!--                    <option value=" {{ $event->value }}" >-->
                            <!--                        {{ $event->value }}-->
                            <!--                    </option>-->
                            <!--
    @endforeach-->
                            <!--
    @endif-->
                            <!--            </select> -->
                            <!--        </div>-->
                            <!--</div>-->
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>{{ translate('Custom Questions') }}</label>
                                <div class="custom-questions-target"> 
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
                                <label class="col-md-3 col-from-label">{{ translate('Video Provider') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="video_provider"
                                        id="video_provider">
                                        <option value="youtube">{{ translate('Youtube') }}</option>
                                        <option value="dailymotion">{{ translate('Dailymotion') }}</option>
                                        <option value="vimeo">{{ translate('Vimeo') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Video Link') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="video_link"
                                        placeholder="{{ translate('Video Link') }}">
                                    <small
                                        class="text-muted">{{ translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.") }}</small>
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
                                <div class="col-md-3">
                                    <input type="text" class="form-control" value="{{ translate('Colors') }}"
                                        disabled>
                                </div>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        data-selected-text-format="count" name="colors[]" id="colors" multiple
                                        disabled>
                                        @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                            <option value="{{ $color->code }}" data-color-name="{{ $color->name }}"
                                                data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>">
                                            </option>
                                        @endforeach
                                    </select>
                                    <span id="selected_colors" class="m-2 mt-4 p-2"></span>
                                </div>
                                <div class="col-md-1">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" name="colors_active">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row gutters-5">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" value="{{ translate('Attributes') }}"
                                        disabled>
                                </div>
                                <div class="col-md-8">
                                    <select name="choice_attributes[]" id="choice_attributes"
                                        class="form-control aiz-selectpicker" data-selected-text-format="count"
                                        data-live-search="true" multiple
                                        data-placeholder="{{ translate('Choose Attributes') }}">
                                        @foreach (\App\Models\Attribute::all() as $key => $attribute)
                                            <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}
                                </p>
                                <br>
                            </div>

                            <div class="customer_choice_options" id="customer_choice_options">

                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product price + stock') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Unit price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('Unit price') }}" name="unit_price"
                                        class="form-control" required>
                                </div>
                            </div>


                            <div class="form-group row d-none">
                                <label class="col-md-3 col-from-label">{{ translate('WholeSale price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('WholeSale price') }}" name="wholesale_price"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row d-none">
                                <label class="col-md-3 col-from-label">{{ translate('WholeSale Variant Price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('WholeSale Variant Price') }}"
                                        name="wholesale_price_variant" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label class="col-md-3 col-from-label">{{ translate('Cost Price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('Cost Price') }}" name="cost_price"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 control-label"
                                    for="start_date">{{ translate('Discount Date Range') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control aiz-date-range" name="date_range"
                                        placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Discount') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('Discount') }}" name="discount" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control aiz-selectpicker" name="discount_type">
                                        <option value="amount">{{ translate('Flat') }}</option>
                                        <option value="percent">{{ translate('Percent') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Guarantee') }} </label>
                                <div class="col-md-3">
                                    <select class="form-control aiz-selectpicker" name="guarantee_type">
                                        <option value="normal">{{ translate('Normal') }}</option>
                                        <option value="golden">{{ translate('Golden') }}</option>
                                    </select>
                                </div>
                            </div>
                            @if (addon_is_activated('club_point'))
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">
                                        {{ translate('Set Point') }}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="0"
                                            step="1" placeholder="{{ translate('1') }}" name="earn_point"
                                            class="form-control">
                                    </div>
                                </div>
                            @endif

                            <div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Quantity') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="0"
                                            step="1" placeholder="{{ translate('Quantity') }}"
                                            name="current_stock" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">
                                        {{ translate('SKU') }}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" placeholder="{{ translate('SKU') }}" name="sku"
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
                                        name="external_link" class="form-control">
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
                                        name="external_link_btn" class="form-control">
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
                                <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                                <div class="col-md-8">
                                    <textarea class="aiz-text-editor" name="description"></textarea>
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
                                <label class="col-md-3 col-from-label">{{ translate('Long Description') }}</label>
                                <div class="col-md-8">
                                    <textarea class="aiz-text-editor" name="long_description"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- <div class="card">
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
                                    <div class="input-group" data-toggle="aizuploader" data-type="document">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="pdf" class="selected-files">
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
                                <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="meta_title"
                                        placeholder="{{ translate('Meta Title') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                                <div class="col-md-8">
                                    <textarea name="meta_description" rows="8" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('Meta Image') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="meta_img" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">
                            {{ translate('Shipping Configuration') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (get_setting('shipping_type') == 'product_wise_shipping')
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Free Shipping') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="shipping_type" value="free" checked>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Flat Rate') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="shipping_type" value="flat_rate">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="flat_rate_shipping_div" style="display: none">
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Shipping cost') }}</label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="0"
                                            step="0.01" placeholder="{{ translate('Shipping cost') }}"
                                            name="flat_shipping_cost" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    class="col-md-6 col-from-label">{{ translate('Is Product Quantity Mulitiply') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="is_quantity_multiplied" value="1">
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
                            <input type="number" name="low_stock_quantity" value="1" min="0"
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
                                    <input type="radio" name="stock_visibility_state" value="quantity" checked>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{ translate('Show Stock With Text Only') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="text">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{ translate('Hide Stock') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="hide">
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
                                <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="cash_on_delivery" value="1" checked="">
                                        <span></span>
                                    </label>
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
                            <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="featured" value="1"
                                        {{ old('feature') == 1 ? 'checked' : '' }}>
                                    <span></span>
                                </label>
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
                            <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="back_order" value="1"
                                        {{ old('back_order') == 1 ? 'checked' : '' }}>
                                    <span></span>
                                </label>
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
                                        {{ old('trending') == 1 ? 'checked' : '' }}>
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
                                    <input type="checkbox" name="best_selling" value="1"
                                        {{ old('best_selling') == 1 ? 'checked' : '' }}>
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
                            <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="todays_deal" value="1">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('VAT & Tax') }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach (\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                            <label for="name">
                                {{ $tax->name }}
                                <input type="hidden" value="{{ $tax->id }}" name="tax_id[]">
                            </label>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('Tax') }}" name="tax[]" class="form-control"
                                        required>
                                </div>
                                <div class="form-group col-md-6">
                                    <select class="form-control aiz-selectpicker" name="tax_type[]">
                                        <option value="amount">{{ translate('Flat') }}</option>
                                        <option value="percent">{{ translate('Percent') }}</option>
                                    </select>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Estimate Shipping Time') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{ translate('Shipping Days') }}
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="est_shipping_days" min="1"
                                    step="1" placeholder="{{ translate('Shipping Days') }}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">{{ translate('Days') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Flash Deal') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{ translate('Add To Flash') }}
                            </label>
                            <select class="form-control aiz-selectpicker" name="flash_deal_id" id="flash_deal">
                                <option value="">{{ translate('Choose Flash Title') }}</option>
                                @foreach (\App\Models\FlashDeal::where('status', 1)->get() as $flash_deal)
                                    <option value="{{ $flash_deal->id }}">
                                        {{ $flash_deal->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">
                                {{ translate('Discount') }}
                            </label>
                            <input type="number" name="flash_discount" value="0" min="0" step="0.01"
                                class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">
                                {{ translate('Discount Type') }}
                            </label>
                            <select class="form-control aiz-selectpicker" name="flash_discount_type"
                                id="flash_discount_type">
                                <option value="">{{ translate('Choose Discount Type') }}</option>
                                <option value="amount">{{ translate('Flat') }}</option>
                                <option value="percent">{{ translate('Percent') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group mr-2" role="group" aria-label="Third group">
                        <button type="submit" name="button" value="unpublish"
                            class="btn btn-primary action-btn">{{ translate('Save & Unpublish') }}</button>
                    </div>
                    <div class="btn-group" role="group" aria-label="Second group">
                        <button type="submit" name="button" value="publish"
                            class="btn btn-success action-btn">{{ translate('Save & Publish') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $('form').bind('submit', function(e) {
            if ($(".action-btn").attr('attempted') == 'true') {
                e.preventDefault();
            } else {
                $(".action-btn").attr("attempted", 'true');
            }
        });

        $("[name=shipping_type]").on("change", function() {
            $(".flat_rate_shipping_div").hide();

            if ($(this).val() == 'flat_rate') {
                $(".flat_rate_shipping_div").show();
            }

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

                    var colorSpan = $(
                        '<span class="size-15px d-inline-block mr-2 rounded border"></span>');
                    colorSpan.css('background-color', colorCode);

                    // Append the color span to the selected colors span
                    span.append(colorSpan);

                    // Append the color name to the selected colors span
                    span.append('<span>' + colorName + '</span>');
                });
            });
        });

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
                                                        <select class="form-control aiz-selectpicker attribute_choice" onchange="showSelected(this)"  data-live-search="true" name="choice_options_' +
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

        $('input[name="unit_price"]').on('keyup', function() {
            update_sku();
        });

        $('input[name="name"]').on('keyup', function() {
            update_sku();
        });

        function delete_row(em) {
            $(em).closest('.form-group row').remove();
            update_sku();
        }

        function delete_variant(em) {
            $(em).closest('.variant').remove();
        }

        function update_sku() {
            $.ajax({
                type: "POST",
                url: '{{ route('products.sku_combination') }}',
                data: $('#choice_form').serialize(),
                success: function(data) {
                    $('#sku_combination').html(data);
                    console.log(data);
                    AIZ.uploader.previewGenerate();
                    AIZ.plugins.fooTable();
                    if (data.length > 1) {
                        $('#show-hide-div').hide();
                    } else {
                        $('#show-hide-div').show();
                    }
                }
            });
        }

        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function() {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });

            update_sku();
        });


        document.getElementById('sub_category_id').addEventListener('change', function() {
            var selectedValues = Array.from(this.selectedOptions).map(option => '"' + option.value + '"');
            document.getElementById('sub_category_ids').value = selectedValues.join(',\n    ');
        });
    </script>
@endsection
