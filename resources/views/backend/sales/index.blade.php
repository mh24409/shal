@extends('backend.layouts.app')

@section('content')

<div class="card">
    <form class="" action="" id="sort_orders" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Orders') }}</h5>
            </div>
            @can('delete_order')
                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Action')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="bulk_delete()"> {{translate('Delete selection')}}</a>
                    </div>
                </div>
            @endcan
            <div class="col-lg-2 ml-auto">
                <select class="form-control aiz-selectpicker" name="delivery_status" id="delivery_status">
                    <option value="">{{translate('Filter by Delivery Status')}}</option>
                    <option value="pending" @if ($delivery_status == 'pending') selected @endif>{{translate('Pending')}}</option>
                    <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>{{translate('Confirmed')}}</option>
                    <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>{{translate('Picked Up')}}</option>
                    <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>{{translate('On The Way')}}</option>
                    <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>{{translate('Delivered')}}</option>
                    <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>{{translate('Cancel')}}</option>
                </select>
            </div>
            <div class="col-lg-2 ml-auto">
                <select class="form-control aiz-selectpicker" name="payment_status" id="payment_status">
                    <option value="">{{translate('Filter by Payment Status')}}</option>
                    <option value="paid"  @isset($payment_status) @if($payment_status == 'paid') selected @endif @endisset>{{translate('Paid')}}</option>
                    <option value="unpaid"  @isset($payment_status) @if($payment_status == 'unpaid') selected @endif @endisset>{{translate('Un-Paid')}}</option>
                </select>
              </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="aiz-date-range form-control" value="{{ $date }}" name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type Order code & hit Enter') }}">
                </div>
            </div>
            <div class="col-auto">
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                </div>
                {{-- <div class="form-group mb-0">
                    <a href="{{ route('all_orders.update_shipping_status') }}" class="btn btn-primary">{{ translate('update Shipping Company Status') }}</a>
                </div> --}}
            </div>
        </div>
            <div class="col text-right d-none" style="padding-top: 30px !important;">
                <a href="{{ route('new_order_by_admin') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New Order')}}</span>
                </a>
            </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        @if(auth()->user()->can('delete_order'))
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

                        <th>{{ translate('Order Code') }}</th>
                        <th data-breakpoints="md">{{ translate('Tracking Code') }}</th>
                        <th data-breakpoints="md">{{ translate('company Status') }}</th>
                        <th data-breakpoints="md">{{ translate('Customer') }}</th>
                        <th data-breakpoints="md">{{ translate('Amount') }}</th>
                        <th data-breakpoints="md">{{ translate('Delivery Status') }}</th>
                        <th data-breakpoints="md">{{ translate('Payment method') }}</th>
                        <th data-breakpoints="md">{{ translate('Payment Status') }}</th>
                        <th data-breakpoints="md">{{ translate('Latest Notes') }}</th>
                        @if (addon_is_activated('refund_request'))
                        <th>{{ translate('Refund') }}</th>
                        @endif
                        <th class="text-right" width="15%">{{translate('options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                    <tr>

                        @if(auth()->user()->can('delete_order'))
                            <td>
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" name="id[]" value="{{$order->id}}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        @else
                            <td>{{ ($key+1) + ($orders->currentPage() - 1)*$orders->perPage() }}</td>
                        @endif
                                                <td>
                            {{ $order->code }}@if($order->viewed == 0) <span class="badge badge-inline badge-info">{{translate('New')}}</span>@endif
                        </td>

                        <td>
                            {{ $order->tracking_code }}
                        </td>
                        @if($order->carrier_id ==3)
                        @php
                        $trackingInfo = json_decode($order->tracking_info);
                        @endphp

                            <td>
                            {{  $trackingInfo[0]->description  ?? '' }}
                        </td>
                        @else
                        <td>
                            {{$order->shipping_company_status}}
                        </td>
                        @endif

                        <td>
                            @if ($order->user != null)
                                {{ $order->user->name }}
                            @else
                                Guest ({{ $order->guest_id }})
                            @endif
                        </td>
                        <td>
                            {{ single_price($order->grand_total) }}
                        </td>
                        <td>
                            {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
                        </td>
                        <td>
                            {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                        </td>
                        <td>
                            @if ($order->payment_status == 'paid')
                            <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                            @else
                            <span class="badge badge-inline badge-danger">{{translate('Unpaid')}}</span>
                            @endif
                        </td>

                        <td style="max-width:100px" class="position-relative" >
                            @php
                               $order_note= DB::table('order_notes')->where('order_id',$order->id)->latest()->first();
                            @endphp
                             <span style="width:100%;overflow:hidden" >{{$order_note->notes ?? "__"}} <br> <span class="text-gray" >{{ $order_note->created_at ?? ''}}</span> </span>
                            <div class="modal fade" id="addNoteModal-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel-{{ $order->id }}" aria-hidden="true">
                                   <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addNoteModalLabel">{{ translate('Add New Note') }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>


                                            <div class="modal-body">
                                                <div>
                                                    <div >
                                                     @php $order_notes= DB::table('order_notes')->where('order_id',$order->id)->get(); @endphp
                                                     @foreach( $order_notes as $i => $note)
                                                      <div style="background-color:#EFEFEF" class=" w-100 p-3" >
                                                            <span> {{$note->notes}} </span>
                                                         </div>
                                                         <div class="mb-2 mt-1" >{{ \Carbon\Carbon::parse($note->created_at)->format('F d, Y \a\t g:i a') }}
                                                        </div>
                                                     @endforeach
                                                </div>
                                                </div>
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <div class="form-group">
                                                    <label for="note">{{ translate('Add Notes') }}</label>
                                                    <textarea class="form-control" id="note-{{ $order->id }}" name="note" rows="3" required></textarea>
                                                </div>
                                                 <a href="javascript:void(0);" class="btn btn-primary add-note-btn" onclick="addOrderNote({{ $order->id }}, $('#note-{{ $order->id }}').val())">{{ translate('Add Order Note') }}</a>
                                            </div>

                                    </div>
                                </div>
                                </div>
                            </div>
                        </td>

                        @if (addon_is_activated('refund_request'))
                        <td>
                            @if (count($order->refund_requests) > 0)
                                {{ count($order->refund_requests) }} {{ translate('Refund') }}
                            @else
                                {{ translate('No Refund') }}
                            @endif
                        </td>
                        @endif
                        <td class="text-right">
                            <button type="button" class="btn btn-soft-primary btn-icon btn-circle btn-sm" data-toggle="modal" data-target="#addNoteModal-{{ $order->id }}">
                               <i class="las la-file-alt"></i>
                            </button>
                            @can('view_order_details')
                                @php
                                    $order_detail_route = route('orders.show', encrypt($order->id));
                                    if(Route::currentRouteName() == 'seller_orders.index') {
                                        $order_detail_route = route('seller_orders.show', encrypt($order->id));
                                    }
                                    else if(Route::currentRouteName() == 'pick_up_point.index') {
                                        $order_detail_route = route('pick_up_point.order_show', encrypt($order->id));
                                    }
                                    if(Route::currentRouteName() == 'inhouse_orders.index') {
                                        $order_detail_route = route('inhouse_orders.show', encrypt($order->id));
                                    }
                                @endphp
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ $order_detail_route }}" title="{{ translate('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            @endcan
                            <a class="btn btn-soft-warning btn-icon btn-circle btn-sm "  href="{{ route('all_orders.update_shipping_status',$order->id) }}" title="{{ translate('update status') }}">
                                <i class="las la-arrow-up"></i>
                            </a>
                             <a class="btn btn-soft-warning btn-icon btn-circle btn-sm " target="_blank" href="{{ $order->shipping_barcode }}" title="{{ translate('Print Barcode') }}">
                                <i class="las la-barcode"></i>
                            </a>
                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{ route('invoice_download', $order->id) }}" title="{{ translate('Download Invoice') }}">
                                <i class="las la-download"></i>
                            </a>
                            <a class="btn btn-soft-warning btn-icon btn-circle btn-sm d-none" href="{{ route('products.reciept',$order->id) }}" title="{{ translate('Print Invoice') }}">
                                <i class="las la-print"></i>
                            </a>
                            <a href="{{ route('edit_order_by_admin', ['order'=>$order->id]) }}" class="btn btn-soft-success btn-icon btn-circle btn-sm d-none"
                                            title="{{ translate('edit') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z"/></svg>
                            </a>
                            @can('delete_order')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('orders.destroy', $order->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="aiz-pagination">
                {{ $orders->appends(request()->input())->links() }}
            </div>

        </div>
    </form>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
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

//        function change_status() {
//            var data = new FormData($('#order_form')[0]);
//            $.ajax({
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
//                url: "{{route('bulk-order-status')}}",
//                type: 'POST',
//                data: data,
//                cache: false,
//                contentType: false,
//                processData: false,
//                success: function (response) {
//                    if(response == 1) {
//                        location.reload();
//                    }
//                }
//            });
//        }

        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-order-delete')}}",
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



  function addOrderNote(orderId, note) {
            $.ajax({
                type: "POST",
                url: "{{ route('add.notes') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'order_id': orderId,
                    'note': note,
                },
                success: function (data) {
                    $('#addNoteModal-' + orderId).modal('hide');
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
@endsection
