@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center d-flex justify-content-between">
			<h1 class="h3">{{translate('Product Reviews')}}</h1>
            <div class="col text-right">
                @can('product_add_custom_review')
                {{-- <div class="col"> --}}
                    <a href="{{route('reviews.create_custom')}}" class="btn btn-circle btn-primary">
                        <span>{{ translate('Add Custom review') }}</span>
                    </a>
                {{-- </div> --}}
                @endcan
                @can('product_add_custom_review')
                        <a href="javascript:void(0)" class="btn btn-circle btn-danger" onclick="$('#remove-custom-review-modal').modal('show');">
                            <span>{{ translate('remove Custom review') }}</span>
                        </a>
                @endcan
            </div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row flex-grow-1">
            <div class="col">
                <h5 class="mb-0 h6">{{translate('Product Reviews')}}</h5>

            </div>
            <div class="">
                <form class="" id="filter_reviews" action="{{ route('reviews.index') }}" method="GET">
                    <div class="d-flex justify-content-between" >
                        <div style="width: 200px" class="mx-2">
                            <select value='null' class="form-control mx-2 aiz-selectpicker"  name="rating" id="rating" onchange="filter_reviews()">
                                <option >{{translate('Filter by Rating')}}</option>
                                <option value="rating,desc">{{translate('Rating (High > Low)')}}</option>
                                <option value="rating,asc">{{translate('Rating (Low > High)')}}</option>
                            </select>
                        </div>
                        <div style="width: 200px" class="mx-2">
                            <select class="form-control  aiz-selectpicker"   name="reveiws_type" onchange="filter_reviews()">
                                <option value='null'>{{ translate('Filter by type') }}</option>
                                <option value="1">{{ translate('custom reviews') }}</option>
                                <option value="0">{{ translate('customers  reveiws') }}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Product')}}</th>
                    <th data-breakpoints="lg">{{translate('Product Owner')}}</th>
                    <th data-breakpoints="lg">{{translate('Customer')}}</th>
                    <th>{{translate('Rating')}}</th>
                    <th data-breakpoints="lg">{{translate('Comment')}}</th>
                    <th data-breakpoints="lg">{{translate('Custom Serial')}}</th>
                    <th data-breakpoints="lg">{{translate('Published')}}</th>
                    <th data-breakpoints="lg">{{translate('Action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $key => $review)
                    @if ($review->product != null && $review->user != null)
                        <tr>
                            <td>{{ ($key+1) + ($reviews->currentPage() - 1)*$reviews->perPage() }}</td>
                            <td>
                                <a href="{{ route('product', $review->product->slug) }}" target="_blank" class="text-reset text-truncate-2">{{ $review->product->getTranslation('name') }}</a>
                            </td>
                            <td>{{ $review->product->added_by }}</td>
                            <td>{{ $review->user->name }} ({{ $review->user->email }})</td>
                            <td>{{ $review->rating }}</td>
                            <td>{{ $review->comment }}</td>
                            <td>__</td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input
                                        @can('publish_product_review') onchange="update_published(this)" @endcan
                                        value="{{ $review->id }}" type="checkbox"
                                        @if($review->status == 1) checked @endif
                                        @cannot('publish_product_review') disabled @endcan
                                    >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ ($key+1) + ($reviews->currentPage() - 1)*$reviews->perPage() }}</td>
                            <td>
                                @if (isset($review->product->slug))
                                    <a href="{{route('product', $review->product->slug)}}" target="_blank" class="text-reset text-truncate-2">{{ $review->product->getTranslation('name') }}</a>
                                @else
                                    <span class="text-danger">{{translate('no product found')}}</span>
                                @endif
                            </td>
                            <td>{{ $review->product->added_by ?? 'no product found' }}</td>
                            <td>{{ $review->username }}</td>
                            <td>{{ $review->rating }}</td>
                            <td>{{ $review->comment }}</td>
                            <td>{{ $review->custom_serial_code ?? '__' }}</td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input
                                    @can('publish_product_review') onchange="update_published(this)" @endcan
                                    value="{{ $review->id }}" type="checkbox"
                                    @if($review->status == 1) checked @endif
                                    @cannot('publish_product_review') disabled @endcan
                                    >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            {{-- @can('delete_review') --}}
                            <td>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('reviews.destroy.by_admin', ['id'=>$review->id])}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                            {{-- @endcan --}}
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $reviews->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')

    <div id="remove-custom-review-modal" class="modal fade ">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="overflow-y: auto; max-height: 80vh;">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{translate('Remove custom review')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form method="POST" id="add_review_form" action="{{route('reviews.remove_custom_review')}}" >
                    @csrf
                    <div class="m-3" id="categories">
                        <label class="form-label">{{ translate('Categories') }}</label>

                            <select class="select2 form-control aiz-selectpicker" name="categories[]" multiple
                                data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                <option value="0">{{ translate('categories') }}</option>
                                @foreach (App\Models\Category::get() as $category)
                                    <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                                @endforeach
                            </select>

                        @error('categories')
                        <span class="text-danger">{{translate($message)}}</span>
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
                        <span class="text-danger">{{translate($message)}}</span>
                        @enderror
                    </div>
                    <div class="m-3">
                        <label for="serial_code" class="form-label">{{ translate('Serial Code') }} <span class="text-danger">*</span></label>
                        <input type="text" name="serial_code" value="{{old('serial_code')}}" class="form-control" >
                        @error('serial_code')
                            <span class="text-danger">{{translate($message)}}</span>
                        @enderror
                    </div>

                    <div class="modal-body text-center">
                        <button type="button"  class="btn btn-secondary rounded-0 mt-2" id="cancel_custom-review-modal" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary rounded-0 mt-2" id="add-reveiw">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('reviews.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published reviews updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
        function filter_reviews(){
            $('#filter_reviews').submit();
        }
    </script>
@endsection