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
            <form method="POST" id="add_review_form" action="{{route('catalog.update',$catalog->id)}}" >
                @csrf
                @method('PUT')
                <div class="container my-2">
                <div class="row">
                    <div class="col-md-8">
                        <label for="catalog_name" class="form-label ">{{ translate('Catalog Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="catalog_name" value="{{$catalog->catalog_name}}" class="form-control">
                        @error('catalog_name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="catalog_name" class="form-label ">{{ translate('Your Password') }} <span class="text-danger">*</span></label>
                        <input type="text" name="password" value="{{$catalog->password}}" class="form-control">
                        @error('catalog_name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            </div>
               
                <div class="m-3" id="categories">
                    <label class="form-label">{{ translate('Categories') }}</label>

                        <select class="select2 form-control aiz-selectpicker" name="categories[]" multiple
                            data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                            @foreach (App\Models\Category::get() as $category)
                                <option value="{{ $category->id }}" @if(in_array($category->id,$catalog->categories ?? [])) selected @endif >{{ $category->getTranslation('name') }}</option>
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
                                <option value="{{ $product->id }}" @if(in_array($product->id,$catalog->products)) selected @endif >{{ $product->getTranslation('name') }}
                                </option>
                            @endforeach
                        </select>
                    @error('products')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
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



