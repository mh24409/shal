@extends('backend.layouts.app')

@section('content')

@php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center d-flex justify-content-between">
        <div class="col-auto">
            <h1 class="h3">{{translate('All products')}}</h1>
        </div>
        @if($type != 'Seller' && auth()->user()->can('add_new_product'))
        <div class="col text-right">
            {{-- <div class="col text-right"> --}}
                <a href="{{ route('products.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New Product')}}</span>
                </a>
            {{-- </div> --}}
        </div>
        @endif
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_products" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Product') }}</h5>
            </div>

            @can('product_delete')
                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Action')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="bulk_delete()"> {{translate('Delete selection')}}</a>
                    </div>
                </div>
            @endcan

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
                        @if(auth()->user()->can('product_delete'))
                            <th>
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                        @else
                            <th data-breakpoints="lg">#</th>
                        @endif
                        <th>{{translate('Name')}}</th>
                        <th data-breakpoints="sm">{{translate('Info')}}</th>
                        <th data-breakpoints="md">{{translate('Total Stock')}}</th>
                        <th data-breakpoints="md">{{translate('SKU')}}</th>
                        <th data-breakpoints="md">{{translate('Price -- Discounted_Price')}}</th>
                        <th data-breakpoints="lg">{{translate('Todays Deal')}}</th>
                        <th data-breakpoints="lg">{{translate('Published')}}</th>
                        @if(get_setting('product_approve_by_admin') == 1 && $type == 'Seller')
                            <th data-breakpoints="lg">{{translate('Approved')}}</th>
                        @endif
                        <th data-breakpoints="lg">{{translate('Featured')}}</th>



                        <th data-breakpoints="lg">{{translate('Trending')}}</th>
                        <th data-breakpoints="lg">{{translate('Best selling')}}</th>


                        <th data-breakpoints="sm" class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $key => $product)
                    <tr>
                        @if(auth()->user()->can('product_delete'))
                            <td>
                                <div class="form-group d-inline-block">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-one" name="id[]" value="{{$product->id}}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </td>
                        @else
                            <td>{{ ($key+1) + ($products->currentPage() - 1)*$products->perPage() }}</td>
                        @endif
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
                            <strong>{{translate('Num of Sale')}}:</strong> {{ $product->num_of_sale }} {{translate('times')}} </br>
                            <strong>{{translate('Base Price')}}:</strong> {{ single_price($product->unit_price) }} </br>
                            <strong>{{translate('Rating')}}:</strong> {{ $product->rating }} </br>
                        </td>
                        <td>
                            @php
                                $qty = 0;
                                if($product->variant_product) {
                                    foreach ($product->stocks as $key => $stock) {
                                        $qty += $stock->qty;
                                        echo $stock->variant.' - '.$stock->qty.'<br>';
                                    }
                                }
                                else {
                                    //$qty = $product->current_stock;
                                    $qty = optional($product->stocks->first())->qty;
                                    echo $qty;
                                }
                            @endphp
                            @if($qty <= $product->low_stock_quantity)
                                <span class="badge badge-inline badge-danger">Low</span>
                            @endif
                        </td>
                        
                        
                         <td>
                            @php
                                $sku = 0;
                                if($product->variant_product) {
                                   foreach ($product->stocks as $key => $stock) {
                                        echo $stock->variant.' - '.$stock->sku.'<br>';
                                    }
                                }
                                else {
                                    //$sku = $product->sku;
                                    $sku = optional($product->stocks->first())->$sku;
                                    echo $sku;
                                }
                            @endphp
                        </td>
                        
                        
                         <td>
                            @php
                                $price = 0;
                                $discounted_price = 0;
                                if($product->variant_product) {
                                   foreach ($product->stocks as $key => $stock) {
                                   $price = home_base_price_by_stock_id($stock->id) ;
                                   $discounted_price=home_discounted_base_price_by_stock_id($stock->id);
                                        echo $stock->variant . ' - ' . $price . ' . ' . $discounted_price . '<br>';
                                    }
                                }
                                else {
                                    //$sku = $product->sku;
                                    $sku = optional($product->stocks->first())->$sku;
                                    echo $sku;
                                }
                            @endphp
                        </td>

                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_todays_deal(this)" value="{{ $product->id }}" type="checkbox" <?php if ($product->todays_deal == 1) echo "checked"; ?> >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_published(this)" value="{{ $product->id }}" type="checkbox" <?php if ($product->published == 1) echo "checked"; ?> >
                                <span class="slider round"></span>
                            </label>
                        </td>





                        @if(get_setting('product_approve_by_admin') == 1 && $type == 'Seller')
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_approved(this)" value="{{ $product->id }}" type="checkbox" <?php if ($product->approved == 1) echo "checked"; ?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                        @endif
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_featured(this)" value="{{ $product->id }}" type="checkbox" <?php if ($product->featured == 1) echo "checked"; ?> >
                                <span class="slider round"></span>
                            </label>
                        </td>




                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_trending(this)" name="trending" value="{{ $product->id }}" type="checkbox" {{ $product->trending == 1 ? 'checked' : '' }} >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="updateBestSellingIndex(this,{{$product->id}})" name="best_selling" value="{{ $product->id }}" type="checkbox" {{ $product->best_selling == 1 ? 'checked' : '' }} >
                                <span class="slider round"></span>
                            </label>
                        </td>


                        <td class="text-right">
                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm"  href="{{ route('product', $product->slug) }}" target="_blank" title="{{ translate('View') }}">
                                <i class="las la-eye"></i>
                            </a>
                            @can('product_edit')
                                @if ($type == 'Seller')
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('products.seller.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                @else
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('products.admin.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                @endif
                            @endcan
                            @can('add_new_product')
                               <button onclick="printContent(this)" data-values='{{ json_encode(["city" => "egypt", "state" => "menufia","code" => "123456","total" => "1458","sender" => "mohamed","reciever" => "mohamed","phone" => "01279783447","address" => "address","content" => "contennnnnnt","notes" => "nooooooooooooooootes","alt"=>"BrideHome" ,"logo" => static_asset("assets/img/logo.png")]) }}' class="btn btn-soft-warning btn-icon btn-circle btn-sm" title="{{ translate('Print Receipt') }}">
                                    <i class="las la-print"></i>
                                </button>

                            @endcan

                            @can('product_duplicate')
                                <a class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="{{route('products.duplicate', ['id'=>$product->id, 'type'=>$type]  )}}" title="{{ translate('Duplicate') }}">
                                    <i class="las la-copy"></i>
                                </a>
                            @endcan


                            @can('product_delete')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('products.destroy', $product->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endcan
                        </td>
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
    @include('modals.delete_modal')

    <div id="update-modal" class="modal fade">
        <div class="modal-dialog modal-m modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{translate('Update')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                    <div class="m-3">
                        <label for="best_selling_index" class="form-label">{{ translate('Selling Index') }} <span class="text-danger">*</span></label>
                        <input type="number" name="best_selling_index" class="form-control" >
                    </div>
                    <div class="modal-body text-center">
                        <button type="button"  class="btn btn-secondary rounded-0 mt-2" id="cancel_best_selling_modal" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary rounded-0 mt-2" id="update_product_index">{{translate('update')}}</button>
                    </div>
            </div>
        </div>
    </div>




@endsection


@section('script')

    <script type="text/javascript">



        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        $(document).ready(function(){
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function updateBestSellingIndex(el,product_id){
            var url = "{{route('products.best_selling',['product'=>':product_id'])}}".replace(':product_id',product_id)
            if(el.checked){
                $('#update-modal').modal('show');
                $('#update_product_index').click(function (){
                update_best_selling(el,url)
                });
            }
            else{
                update_best_selling(el,url);
            }
            $('#cancel_best_selling_modal').click(function (){
                $(el).prop('checked',false);
            })
        }

        function update_best_selling(el,url){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }

            var index = $('input[name="best_selling_index"][type="number"]').val();

            $.post(url, {_token:'{{ csrf_token() }}', id:el.value, status:status , best_selling_index:index}, function(data){
                if(data.code == 200){
                    if(data.status){
                        $(el).prop('checked',true);
                    }
                    $('#update-modal').modal('hide');
                    AIZ.plugins.notify('success', '{{ translate('Best selling status updated successfully') }}');
                }
                else if(data.code == 403){
                    var errors = [];

                    for(key in data.errors){
                        if(data.errors.hasOwnProperty(key)){
                            errors.push(data.errors[key]);
                        }
                    }
                    errors.forEach(error => {
                        AIZ.plugins.notify('danger', error);
                    })
                    $(el).prop('checked',false);
                }else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    $(el).prop('checked',false);
                }
            });
        }

        function update_trending(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.trending') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Trending status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }



        function update_todays_deal(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.todays_deal') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Todays Deal updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }





        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_approved(el){
            if(el.checked){
                var approved = 1;
            }
            else{
                var approved = 0;
            }
            $.post('{{ route('products.approved') }}', {
                _token      :   '{{ csrf_token() }}',
                id          :   el.value,
                approved    :   approved
            }, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Product approval update successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function sort_products(el){
            $('#sort_products').submit();
        }

        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-product-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }

    </script>
@endsection
