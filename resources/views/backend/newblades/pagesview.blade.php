@extends('backend.layouts.app')
@section('style')
    <style>
        #myTable_wrapper .dt-search input {
            border-radius: 0px;
            border: #8080801f solid 0.1px;
            background-color: white
        }

        #myTable_wrapper .dt-search label {
            display: none
        }

        #myTable_wrapper .dt-length label {
            display: none
        }

        #myTable_wrapper .dt-length select {
            border-radius: 0px;
            border: #8080801f solid 0.1px;
            background-color: white;
            padding: 4px 15px
        }

        .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
            border-bottom: solid black !important;
        }

        .dt-layout-row {
            display: flex !important;
            align-items: center;
            justify-content: space-between
        }

        .dt-search {
            margin-top: 0px !important;
        }

        .dt-layout-cell {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-primary rounded-0" data-toggle="modal" data-target="#exampleModal">

        @switch ($dateRange)
            @case('today')
                @php
                    $startDate = date('Y-m-d');
                    $endDate = date('Y-m-d');
                    $sort_date = date('d-m-Y') . ' to ' . date('d-m-Y');
                @endphp
                {{ translate('Today') }}  <br> {{ $sort_date  }}
            @break

            @case('yesterday')
                @php
                    $startDate = date('Y-m-d', strtotime('yesterday'));
                    $endDate = date('Y-m-d', strtotime('yesterday'));
                    $sort_date =
                        date('d-m-Y', strtotime('yesterday')) .
                        ' to ' .
                        date('d-m-Y', strtotime('yesterday'));
                @endphp
                {{ translate('Yesterday') }}  <br> {{ $sort_date  }}
            @break

            @case('week')
                @php
                    $startDate = date('Y-m-d', strtotime('last monday'));
                    $endDate = date('Y-m-d', strtotime('next sunday'));
                    $sort_date =
                        date('d-m-Y', strtotime('last monday')) .
                        ' to ' .
                        date('d-m-Y', strtotime('next sunday'));
                @endphp
                {{ translate('Last Week') }} <br> {{ $sort_date  }}
            @break

            @case('month')
                @php
                    $startDate = date('Y-m-01');
                    $endDate = date('Y-m-t');
                    $sort_date = date('01-m-Y') . ' to ' . date('t-m-Y');
                @endphp
                {{ translate('Last Month') }}  <br> {{ $sort_date  }}
            @break

            @case('year')
                @php
                    $startDate = date('Y-01-01');
                    $endDate = date('Y-12-31');
                    $sort_date = date('01-01-Y') . ' to ' . date('12-31-Y');
                @endphp
                {{ translate('Last Year') }}  <br> {{ $sort_date  }}
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
            <form class=""
            action="{{ route('reports.pagesview', ['date' => isset($sort_date) ? $sort_date : 0]) }}"
            id="sort_orders" method="GET">                <div>
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
        <div class="col-md-12 mx-auto">
            <table id="myTable" class="table table-bordered mb-0 w-100">
                <thead>
                    <tr>
                        <th>{{ translate('page Name') }}</th>
                        <th>{{ translate('number of views') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @if(isset($totalProViews))
                            <td>{{ 'المنتجات' }}</td>
                            <td>{{ $totalProViews }}</td>
                        @endif
                    </tr>
                    @foreach ($views as $key => $product)
                        @if ($product->url == '/search')
                        <tr>
                            <td>{{ 'المتجر' }}</td>
                            <td>{{ $product->numberViews }}</td>
                        </tr>

                        @elseif($product->url == '/')
                        <tr>
                            <td>{{ 'الصفحة الرئيسية' }}</td>
                            <td>{{ $product->numberViews }}</td>
                        </tr>
                        @elseif($product->url == '/checkout')
                        <tr>
                            <td>{{ 'الدفع' }}</td>
                            <td>{{ $product->numberViews }}</td>
                        </tr>
                        @elseif($product->url == '/cart')
                        <tr>
                            <td>{{ 'عربة التسوق' }}</td>
                            <td>{{ $product->numberViews }}</td>
                        </tr>
                        @elseif($product->url == '/checkout/order-confirmed')
                        <tr>
                            <td>{{ 'تأكيد الطلب' }}</td>
                            <td>{{ $product->numberViews }}</td>
                        </tr>
                        @elseif($product->url == '/categories')
                        <tr>
                            <td>{{ ' الأقسام' }}</td>
                            <td>{{ $product->numberViews }}</td>
                        </tr>
                        @else
                            @continue
                        @endif
                @endforeach
                
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let table = new DataTable('#myTable');
        $('#myTable_wrapper .dt-search input').attr('placeholder', '{{ translate('Search') }}');
    </script>
@endsection
