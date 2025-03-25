@extends('backend.layouts.app')

@section('content')

@php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Best selling')}}</h1>
        </div>
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_products" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Best selling') }}</h5>
            </div>


            @if($type == 'Seller')
            <div class="col-md-2 ml-auto">
                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id" name="user_id" onchange="sort_products()">
                    <option value="">{{ translate('All Sellers') }}</option>
                    @foreach (App\Models\User::where('user_type', '=', 'seller')->get() as $key => $seller)
                        <option value="{{ $seller->id }}" @if ($seller->id == $seller_id) selected @endif>
                            {{ $seller->shop->name }} ({{ $seller->name }})
                        </option>
                    @endforeach
                </select>
            </div>
            @endif


            <div class="col-md-2 ml-auto">

                    <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id" name="category_id" onchange="sort_products()">
                        <option value="">{{ translate('All Categories') }}</option>

                        @foreach (App\Models\Category::get() as $key => $category)
                             @if(isset($category_id))
                                <option value="{{ $category->id }}" @if ($category->id == $category_id) selected @endif>
                                    ({{ $category->name }})
                                </option>
                                @else
                                <option value="{{ $category->id }}">
                                    ({{ $category->name }})
                                </option>
                                @endif
                         @endforeach
                    </select>

            </div>
            @if($type == 'All')
            <div class="col-md-2 ml-auto">
                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id" name="user_id" onchange="sort_products()">
                    <option value="">{{ translate('All Sellers') }}</option>
                        @foreach (App\Models\User::where('user_type', '=', 'admin')->orWhere('user_type', '=', 'seller')->get() as $key => $seller)
                            <option value="{{ $seller->id }}" @if ($seller->id == $seller_id) selected @endif>{{ $seller->name }}</option>
                        @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-2 ml-auto">
                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="type" id="type" onchange="sort_products()">
                    <option value="">{{ translate('Sort By') }}</option>
                    <option value="rating,desc" @isset($col_name , $query) @if($col_name == 'rating' && $query == 'desc') selected @endif @endisset>{{translate('Rating (High > Low)')}}</option>
                    <option value="rating,asc" @isset($col_name , $query) @if($col_name == 'rating' && $query == 'asc') selected @endif @endisset>{{translate('Rating (Low > High)')}}</option>
                    <option value="num_of_sale,desc"@isset($col_name , $query) @if($col_name == 'num_of_sale' && $query == 'desc') selected @endif @endisset>{{translate('Num of Sale (High > Low)')}}</option>
                    <option value="num_of_sale,asc"@isset($col_name , $query) @if($col_name == 'num_of_sale' && $query == 'asc') selected @endif @endisset>{{translate('Num of Sale (Low > High)')}}</option>
                    <option value="unit_price,desc"@isset($col_name , $query) @if($col_name == 'unit_price' && $query == 'desc') selected @endif @endisset>{{translate('Base Price (High > Low)')}}</option>
                    <option value="unit_price,asc"@isset($col_name , $query) @if($col_name == 'unit_price' && $query == 'asc') selected @endif @endisset>{{translate('Base Price (Low > High)')}}</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control form-control-sm" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>{{translate('Name')}}</th>
                        <th data-breakpoints="lg">{{translate('Best selling')}}</th>
                        <th data-breakpoints="lg">{{translate('Best selling index')}}</th>
                        <th data-breakpoints="lg" >{{translate('Edit')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $key => $product)
                    <tr>

                        <td>{{ ($key+1) + ($products->currentPage() - 1)*$products->perPage() }}</td>
                        <td>
                            <div class="row gutters-5 w-200px w-md-300px mw-100">
                                <div class="col-auto">
                                    <img src="{{ uploaded_asset($product->thumbnail_img)}}" alt="Image" class="size-50px img-fit">
                                </div>
                                <div class="col">
                                    <span class="text-muted text-truncate-2">{{ $product->getTranslation('name') }}</span>
                                </div>
                            </div>
                        </td>

                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_best_selling(this)" name="best_selling" value="{{ $product->id }}" type="checkbox" {{ $product->best_selling == 1 ? 'checked' : '' }} >
                                <span class="slider round"></span>
                            </label>
                        </td>

                        <td> <span class="{{ $product->best_selling_index ? 'text-danger' : 'text-primary' }} h6">{{ $product->best_selling_index ?? 'not spacified' }}</span></td>


                        @can('edit_best_selling_index')
                        <td>
                            <button class="btn btn-soft-warning btn-icon btn-circle btn-sm update-product-index" type="button"   onclick="updateBestSellingIndex({{$product->id}})"  title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </button>
                        </td>
                        @endcan

                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $products->appends(request()->input())->links() }}
            </div>
        </div>
    </form>
</div>
<img  class="barcode">
@endsection

@section('modal')
    <div id="update-modal" class="modal fade">
        <div class="modal-dialog modal-m modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{translate('Update')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="product_index_form" method="POST">
                    @csrf
                    @method('put')
                    <div class="m-3">
                        <label for="best_selling_index" class="form-label">{{ translate('Selling Index') }} <span class="text-danger">*</span></label>
                        <input type="number" name="best_selling_index" class="form-control" >
                    </div>
                    <div class="modal-body text-center">
                        <button type="button"  class="btn btn-secondary rounded-0 mt-2" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary rounded-0 mt-2">{{translate('update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')

    @if($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                 AIZ.plugins.notify('danger', "{{ translate("$error") }}");
            </script>
        @endforeach
    @endif

    <script type="text/javascript">


        $(document).ready(function(){
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });


        function updateBestSellingIndex(product_id){
            var url = "{{route('products.best_selling_index.update',['product'=>':product_id'])}}".replace(':product_id',product_id)
            $('#update-modal').modal('show');
            $('#product_index_form').attr('action',url);
        }


        function update_best_selling(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.best_selling') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Best selling status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }


        function sort_products(el){
            $('#sort_products').submit();
        }


    </script>
@endsection
