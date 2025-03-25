@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class=" align-items-center">
        <h1 class="h3">{{translate('Facebook Catalogs')}}</h1>
    </div>
</div>
<div class="col text-right mb-2">
    <div class="col text-right">
        <a href="{{ route('catalog.create') }}" class="btn btn-circle btn-info">
            <span>{{translate('Add New Facebook Catalog')}}</span>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-body">


                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Catalog Name') }}</th>
                            <th>{{ translate('Products Names') }}</th>
                            <th style="width: 100px;">{{ translate('') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($catalogs as $catalog)
                        <tr>
                            <td>{{$catalog->catalog_name}}</td>
                            <td>
                                @foreach(App\Models\Product::whereIn('id', $catalog->products)->get() as $product)
                                {{ $product->name }}
                                @if (!$loop->last)
                                ,
                                @endif
                                @endforeach
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('catalog.edit',$catalog->id)}}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('delete_catalog', $catalog->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    {{$catalogs->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')

    <div id="update-modal" class="modal fade">
        <div class="modal-dialog modal-m modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{translate('Delete')}}</h4>
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
