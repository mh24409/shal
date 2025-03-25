@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class=" align-items-center">
            <h1 class="h3">{{ translate('Profit Report') }}</h1>
        </div>
    </div>
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary rounded-0" data-toggle="modal" data-target="#exampleModal">

            @switch ($dateRange)
                @case('today')
                    @php
                        $startDate = date('Y-m-d');
                        $endDate = date('Y-m-d');
                        $sortDate = date('d-m-Y') . ' to ' . date('d-m-Y');
                    @endphp
                    {{ translate('Today') }} <br> {{ $sortDate }}
                @break

                @case('yesterday')
                    @php
                        $startDate = date('Y-m-d', strtotime('yesterday'));
                        $endDate = date('Y-m-d', strtotime('yesterday'));
                        $sortDate =
                            date('d-m-Y', strtotime('yesterday')) . ' to ' . date('d-m-Y', strtotime('yesterday'));
                    @endphp
                    {{ translate('Yesterday') }} <br> {{ $sortDate }}
                @break

                @case('week')
                    @php
                        $startDate = date('Y-m-d', strtotime('last monday'));
                        $endDate = date('Y-m-d', strtotime('next sunday'));
                        $sortDate =
                            date('d-m-Y', strtotime('last monday')) . ' to ' . date('d-m-Y', strtotime('next sunday'));
                    @endphp
                    {{ translate('Last Week') }} <br> {{ $sortDate }}
                @break

                @case('month')
                    @php
                        $startDate = date('Y-m-01');
                        $endDate = date('Y-m-t');
                        $sortDate = date('01-m-Y') . ' to ' . date('t-m-Y');
                    @endphp
                    {{ translate('Last Month') }} <br> {{ $sortDate }}
                @break

                @case('year')
                    @php
                        $startDate = date('Y-01-01');
                        $endDate = date('Y-12-31');
                        $sortDate = date('01-01-Y') . ' to ' . date('12-31-Y');
                    @endphp
                    {{ translate('Last Year') }} <br> {{ $sortDate }}
                @break

                @default
                    {{ translate('filter By Date') }}
                @break
            @endswitch
        </button>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content pb-4">
                <div class="w-100 p-3 border d-flex align-items-center justify-content-center ">
                    {{ translate('Select A Date Range') }}
                </div>
                <form class="" action="{{ route('profitReport.index') }}" id="sort_orders" method="GET">
                    <div>
                        <ul class="nav nav-tabs w-100" id="myTab" role="tablist">
                            <li class="nav-item w-50" role="presentation">
                                <button class="w-100 border-0 rounded-0 py-3 nav-link active" id="home-tab"
                                    data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">{{ translate('Presets') }}</button>
                            </li>
                            <li class="nav-item w-50" role="presentation">
                                <button class="w-100 border-0 rounded-0 py-3 nav-link" id="profile-tab" data-toggle="tab"
                                    data-target="#profile" type="button" role="tab" aria-controls="profile"
                                    aria-selected="false">{{ translate('Custom') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="w-100 mt-3">
                                    <div class="d-flex align-items-center">
                                        <button type="submit"
                                            style="background: {{ $dateRange == 'today' ? 'unset' : '#F0F0F0' }}"
                                            class="btn w-50 rounded-0 py-3 border" name="date_range" value="today"><span
                                                style="width: 10px ; height: 10px;"
                                                class="bg-success mx-3 rounded  {{ $dateRange == 'today' ? 'd-inline-block' : 'd-none' }}"></span>{{ translate('Today') }}</button>
                                        <button type="submit"
                                            style="background: {{ $dateRange == 'yesterday' ? 'unset' : '#F0F0F0' }}"
                                            class="btn  rounded-0 w-50  py-3 border" name="date_range"
                                            value="yesterday"><span style="width: 10px ; height: 10px;"
                                                class="bg-success mx-3 rounded  {{ $dateRange == 'yesterday' ? 'd-inline-block' : 'd-none' }}"></span>{{ translate('Yesterday') }}</button>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <button type="submit"
                                            style="background: {{ $dateRange == 'week' ? 'unset' : '#F0F0F0' }}"
                                            class="btn  rounded-0 w-50  py-3 border" name="date_range" value="week"><span
                                                style="width: 10px ; height: 10px;"
                                                class="bg-success mx-3 rounded  {{ $dateRange == 'week' ? 'd-inline-block' : 'd-none' }}"></span>{{ translate('Last Week') }}</button>
                                        <button type="submit"
                                            style="background: {{ $dateRange == 'month' ? 'unset' : '#F0F0F0' }}"
                                            class="btn  rounded-0 w-50  py-3 border" name="date_range" value="month"><span
                                                style="width: 10px ; height: 10px;"
                                                class="bg-success mx-3 rounded  {{ $dateRange == 'month' ? 'd-inline-block' : 'd-none' }}"></span>{{ translate('Last Month') }}</button>
                                    </div>
                                    <div class="w-100">
                                        <button type="submit"
                                            style="background: {{ $dateRange == 'year' ? 'unset' : '#F0F0F0' }}"
                                            class="btn  rounded-0 w-100  py-3 border" name="date_range" value="year">
                                            <span style="width: 10px ; height: 10px;"
                                                class="bg-success mx-3 rounded  {{ $dateRange == 'year' ? 'd-inline-block' : 'd-none' }}"></span>
                                            {{ translate('Last Year') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="form-group m-5">
                                    <input type="text" class="form-control aiz-date-range"
                                        placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off"
                                        id="date" name="date"
                                        @isset($sort_date) value="{{ $sort_date }}" @endisset>
                                </div>

                            </div>
                            <div class="mt-3 w-100 d-flex align-items-center justify-content-center">
                                <button type="submit"
                                    class="btn btn-primary rounded-0">{{ translate('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">




        <div class="col-4 border p-0">
            <div class="bg-white text-dark rounded-lg overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50 fs-18 fw-bold mb-3">
                        <span class="fs-12 d-block">{{ translate('Total Sales') }}
                    </div>
                    <div class="h3 fw-700 mb-3">
                        @isset($total_grand)
                            {{ $total_grand }}
                        @else
                            0
                        @endisset
                    </div>
                </div>
            </div>
        </div>


        <div class="col-4 border p-0">
            <div class="bg-white text-dark rounded-lg overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50 fs-18 fw-bold mb-3">
                        <span class="fs-12 d-block">{{ translate('Total Cost Price') }}
                    </div>
                    <div class="h3 fw-700 mb-3">
                        @isset($total_cost_price)
                            {{ $total_cost_price }}
                        @else
                            0
                        @endisset
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 border p-0">
            <div class="bg-white text-dark rounded-lg overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50 fs-18 fw-bold mb-3">
                        <span class="fs-12 d-block">{{ translate('Total Profit') }}
                    </div>
                    <div class="h3 fw-700 mb-3">
                        @isset($total_profit)
                            {{ $total_profit }}
                        @else
                            0
                        @endisset
                    </div>
                </div>
            </div>
        </div>




        <div class="col-md-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>{{ translate('date') }}</th>
                                <th>{{ translate('Order Code') }}</th>
                                <th>{{ translate('net sales') }}</th>
                                <th>{{ translate('tax') }}</th>
                                <th>{{ translate('cod tax') }}</th>
                                <th>{{ translate('shipping cost') }}</th>
                                <th>{{ translate('total cost price') }}</th>
                                <th>{{ translate('coupon discount') }}</th>

                                <th>{{ translate('grand total') }}</th>
                                <th>{{ translate('total profit') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromTimestamp($order->date)->toDateTimeString() }}</td>
                                    <td><a href="{{ route('orders.show', encrypt($order->id)) }}">{{ $order->code }}</a>
                                    </td>
                                    <td>{{ $order->net_sales }}</td>
                                    <td>{{ $order->tax }}</td>
                                    <td>{{ $order->COD_tax / $order->order_count }}</td>
                                    <td>{{ $order->shipping_cost }}</td>
                                    <td>{{ $order->total_cost_price }}</td>
                                    <td>{{ $order->coupon_discount / $order->order_count  }}</td>

                                    <td>{{ $order->grand_total / $order->order_count }}</td>
                                    <td>{{ ($order->grand_total / $order->order_count )- ($order->coupon_discount + $order->total_cost_price + $order->tax + $order->shipping_fees) }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
