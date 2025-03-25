@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add Custom Reviews')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            {{-- @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
            <form method="POST" id="add_review_form" action="{{route('reviews.custom_review')}}" >
                @csrf
                <div class="m-3" id="categories">
                    <label class="form-label">{{ translate('Categories') }}</label>

                        <select class="select2 form-control aiz-selectpicker" name="categories[]" multiple
                            data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                            <option value="0">{{ translate('Categories') }}</option>
                            @foreach (App\Models\Category::get() as $category)
                                <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                            @endforeach
                        </select>

                    @error('categories')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="m-3">
                    <label class="form-label">{{ translate('products') }}</label>
                        <select class="select2 form-control aiz-selectpicker" name="products[]" multiple
                            data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                            <option value="0">{{ translate('products') }}</option>
                            @foreach (App\Models\Product::where('published',1)->get() as $product)
                                <option value="{{ $product->id }}">{{ $product->getTranslation('name') }}
                                </option>
                            @endforeach
                        </select>
                    @error('products')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="m-3">
                    <label for="name" class="form-label ">{{ translate('Users names') }} <span class="text-danger">*</span></label>
                    <input type="text" name="names" value="{{old('names')}}" class="form-control aiz-tag-input" >
                    @error('names')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="m-3">
                    <label for="comment" class="form-label ">{{ translate('Comments') }} <span class="text-danger">*</span></label>
                    <input type="text" name="comments" value="{{old('comments')}}" class="form-control aiz-tag-input" >
                    @error('comments')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                    <span class="text-muted">{{translate('comments should be equal or more than names count')}}</span>
                </div>
                {{-- <div class="m-3">
                    <label for="rate" class="form-label">{{ translate('Rate') }} <span class="text-danger">*</span></label>
                    <input type="number" value="{{old('rate')}}" name="rate" class="form-control" >
                    @error('rate')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div> --}}
                <div class="m-3">
                    <label for="limit" class="form-label">{{ translate('limit') }}</label>
                    <input type="number" value="{{old('limit')}}" name="limit" class="form-control" >
                    @error('limit')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                    <span class="text-muted">{{translate('limit should be less or equal the names by default it will use all names to make comments to the products')}}</span>
                </div>
                    {{--
                    <div class="m-3">
                        <label class="form-label">{{ translate('Image') }}</label>
                        <div class="col">
                            <div class=" input-group " data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="image" class="selected-files">
                            </div>
                            <div class="file-preview"></div>
                        </div>
                    </div> --}}

                <div class="m-3">
                    <label class="form-label"
                        for="date_rang">{{ translate('Date Rang') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control aiz-date-range" name="date_rang"
                            placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                            data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                </div>



                <div class="modal-body text-end">
                    {{-- <button type="button"  class="btn btn-secondary rounded-0 mt-2" id="cancel_custom-review-modal" data-dismiss="modal">{{translate('Cancel')}}</button> --}}
                    <button type="submit" class="btn btn-primary rounded-0 mt-2" id="add-reveiw">{{translate('update')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection



