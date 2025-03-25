@extends('backend.layouts.app')
@section('style')
    <style>
        .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
            border-bottom: solid black !important;
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
                    {{ translate('Today') }} <br> {{ $sort_date }}
                @break

                @case('yesterday')
                    @php
                        $startDate = date('Y-m-d', strtotime('yesterday'));
                        $endDate = date('Y-m-d', strtotime('yesterday'));
                        $sort_date =
                            date('d-m-Y', strtotime('yesterday')) . ' to ' . date('d-m-Y', strtotime('yesterday'));
                    @endphp
                    {{ translate('Yesterday') }} <br> {{ $sort_date }}
                @break

                @case('week')
                    @php
                        $startDate = date('Y-m-d', strtotime('last monday'));
                        $endDate = date('Y-m-d', strtotime('next sunday'));
                        $sort_date =
                            date('d-m-Y', strtotime('last monday')) . ' to ' . date('d-m-Y', strtotime('next sunday'));
                    @endphp
                    {{ translate('Last Week') }} <br> {{ $sort_date }}
                @break

                @case('month')
                    @php
                        $startDate = date('Y-m-01');
                        $endDate = date('Y-m-t');
                        $sort_date = date('01-m-Y') . ' to ' . date('t-m-Y');
                    @endphp
                    {{ translate('Last Month') }} <br> {{ $sort_date }}
                @break

                @case('year')
                    @php
                        $startDate = date('Y-01-01');
                        $endDate = date('Y-12-31');
                        $sort_date = date('01-01-Y') . ' to ' . date('12-31-Y');
                    @endphp
                    {{ translate('Last Year') }} <br> {{ $sort_date }}
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
                <form class="" action="{{ route('reports.home') }}" id="sort_orders" method="GET">
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
    <div class="row  mb-3">
        <div class="col-md-12">
            <div class="row">
                <div class="col-3 border p-0   ">
                    <div class="bg-white text-dark rounded-lg overflow-hidden">
                        <div class="px-3 pt-3">
                            <div class="opacity-50 fs-18 fw-bold mb-3">
                                <span class="fs-12 d-block">{{ translate('Total Sales') }}
                            </div>
                            <div class="h3 fw-700 mb-3">
                                @isset($tPrices)
                                    {{ $tPrices }}
                                @else
                                    0
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3 border p-0   ">
                    <div class="bg-white text-dark rounded-lg overflow-hidden">
                        <div class="px-3 pt-3">
                            <div class="opacity-50 fs-18 fw-bold mb-3">
                                <span class="fs-12 d-block">{{ translate('Net Sales') }}
                            </div>
                            <div class="h3 fw-700 mb-3">
                                @isset($shippingCost)
                                    {{ $tPrices - $shippingCost->ShippingCost }}
                                @else
                                    0
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3 border p-0   ">
                    <div class="bg-white text-dark rounded-lg overflow-hidden">
                        <div class="px-3 pt-3">
                            <div class="opacity-50 fs-18 fw-bold mb-3">
                                <span class="fs-12 d-block">{{ translate('Total Orders') }}
                            </div>
                            <div class="h3 fw-700 mb-3">
                                @isset($numberOrders)
                                    {{ $numberOrders }}
                                @else
                                    0
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3 border p-0   ">
                    <div class="bg-white text-dark rounded-lg overflow-hidden">
                        <div class="px-3 pt-3">
                            <div class="opacity-50 fs-18 fw-bold mb-3">
                                <span class="fs-12 d-block">{{ translate('Total Users') }}
                            </div>
                            <div class="h3 fw-700 mb-3">
                                @isset($numberUsers)
                                    {{ count($numberUsers) }}
                                @else
                                    0
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 border p-0 d-none">
                    <div class="bg-white text-dark rounded-lg overflow-hidden">
                        <div class="px-3 pt-3">
                            <div class="opacity-50 fs-18 fw-bold mb-3">
                                <span class="fs-12 d-block">{{ translate('Total Views') }}
                            </div>
                            <div class="h3 fw-700 mb-3">
                                @isset($numberofviews[0]->numberViews)
                                    {{ $numberofviews[0]->numberViews }}
                                @else
                                    0
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 border p-0 d-none">
                    <div class="bg-white text-dark rounded-lg overflow-hidden">
                        <div class="px-3 pt-3">
                            <div class="opacity-50 fs-18 fw-bold mb-3">
                                <span class="fs-12 d-block">{{ translate('Total Checkout Views') }}
                            </div>
                            <div class="h3 fw-700 mb-3">
                                @isset($resultviews['/checkout']['numberViews'])
                                    {{ $resultviews['/checkout']['numberViews'] }}
                                @else
                                    0
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 border p-0 d-none">
                    <div class="bg-white text-dark rounded-lg overflow-hidden">
                        <div class="px-3 pt-3">
                            <div class="opacity-50 fs-18 fw-bold mb-3">
                                <span class="fs-12 d-block">{{ translate('Total Shop Views') }}
                            </div>
                            <div class="h3 fw-700 mb-3">
                                @isset($resultviews['/search']['numberViews'])
                                    {{ $resultviews['/search']['numberViews'] }}
                                @else
                                    0
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 border p-0 d-none">
                    <div class="bg-white text-dark rounded-lg overflow-hidden">
                        <div class="px-3 pt-3">
                            <div class="opacity-50 fs-18 fw-bold mb-3">
                                <span class="fs-12 d-block">{{ translate('Total Products Views') }}
                            </div>
                            <div class="h3 fw-700 mb-3">
                                @isset($countProductUrls)
                                    {{ $countProductUrls }}
                                @else
                                    0
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 p-0 d-none">
            <div class=" ">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('reports.pagesview', ['date' => isset($sort_date) ? $sort_date : 0]) }}">
                                <h6 class="mb-0 fs-14">{{ translate('Views') }}</h6>
                            </a>
                        </div>
                        <div class="card-body py-5">
                            <canvas id="pie-1" class="w-100" height="305"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 p-0">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('category_reports.home') }}">
                        <h6 class="mb-0 fs-14">{{ translate('Category Orders') }}</h6>
                    </a>
                </div>
                <div class="card-body">
                    <canvas id="graph-2" class="w-100" height="350"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 p-0">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('products_reports.home') }}">
                        <h6 class="mb-0 fs-14">{{ translate('Products Orders') }}</h6>
                    </a>
                </div>
                <div class="card-body">
                    <canvas id="graph-3" class="w-100" height="350"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        AIZ.plugins.chart('#graph-1', {
            type: 'bar',
            data: {
                labels: [
                    'Category 1',
                    'Category 2',
                    'Category 3',
                    'Category 4',
                ],
                datasets: [{
                    label: 'Number of sale',
                    data: [30, 40, 25, 35],
                    backgroundColor: [
                        'rgba(55, 125, 255, 0.4)',
                        'rgba(55, 125, 255, 0.4)',
                        'rgba(55, 125, 255, 0.4)',
                        'rgba(55, 125, 255, 0.4)',
                    ],
                    borderColor: [
                        'rgba(55, 125, 255, 1)',
                        'rgba(55, 125, 255, 1)',
                        'rgba(55, 125, 255, 1)',
                        'rgba(55, 125, 255, 1)',
                        // Add more border colors if needed
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#f2f3f8',
                            zeroLineColor: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10,
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontFamily: 'Poppins',
                        boxWidth: 10,
                        usePointStyle: true
                    },
                    onClick: function() {
                        return '';
                    },
                }
            }
        });
        AIZ.plugins.chart('#graph-2', {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($categoriespric as $item)
                        '{{ $item['name'] }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Number of sale',
                    data: [
                        {{ isset($categoriespric[0]['totalPrice']) ? json_encode($categoriespric[0]['totalPrice']) : null }},
                        {{ isset($categoriespric[1]['totalPrice']) ? json_encode($categoriespric[1]['totalPrice']) : null }},
                        {{ isset($categoriespric[2]['totalPrice']) ? json_encode($categoriespric[2]['totalPrice']) : null }},
                        {{ isset($categoriespric[3]['totalPrice']) ? json_encode($categoriespric[3]['totalPrice']) : null }},
                    ],
                    backgroundColor: [
                        'rgba(55, 125, 255, 0.4)',
                        'rgba(55, 125, 255, 0.4)',
                        'rgba(55, 125, 255, 0.4)',
                        'rgba(55, 125, 255, 0.4)',
                    ],
                    borderColor: [
                        'rgba(55, 125, 255, 1)',
                        'rgba(55, 125, 255, 1)',
                        'rgba(55, 125, 255, 1)',
                        'rgba(55, 125, 255, 1)',
                        // Add more border colors if needed
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#f2f3f8',
                            zeroLineColor: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10,
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontFamily: 'Poppins',
                        boxWidth: 10,
                        usePointStyle: true
                    },
                    onClick: function() {
                        return '';
                    },
                }
            }
        });
        AIZ.plugins.chart('#graph-3', {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($categoriespric as $item)
                        '{{ $item['name'] }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Number of sale',
                    data: [
                        {{ isset($productspric[0]['totalPrice']) ? json_encode($productspric[0]['totalPrice']) : null }},
                        {{ isset($productspric[1]['totalPrice']) ? json_encode($productspric[1]['totalPrice']) : null }},
                        {{ isset($productspric[2]['totalPrice']) ? json_encode($productspric[2]['totalPrice']) : null }},
                        {{ isset($productspric[3]['totalPrice']) ? json_encode($productspric[3]['totalPrice']) : null }}
                    ],
                    backgroundColor: [
                        'rgba(55, 125, 255, 0.4)',
                        'rgba(55, 125, 255, 0.4)',
                        'rgba(55, 125, 255, 0.4)',
                        'rgba(55, 125, 255, 0.4)',
                    ],
                    borderColor: [
                        'rgba(55, 125, 255, 1)',
                        'rgba(55, 125, 255, 1)',
                        'rgba(55, 125, 255, 1)',
                        'rgba(55, 125, 255, 1)',
                        // Add more border colors if needed
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#f2f3f8',
                            zeroLineColor: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10,
                            beginAtZero: true,
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontFamily: 'Poppins',
                        boxWidth: 10,
                        usePointStyle: true
                    },
                    onClick: function() {
                        return '';
                    },
                }
            }
        });
        AIZ.plugins.chart('#pie-1', {
            type: 'doughnut',
            data: {
                labels: [
                    '{{ translate('Product Details') }}',
                    '{{ translate('Shop') }}',
                    '{{ translate('checkout') }}'
                ],
                datasets: [{
                    data: [
                        {{ is_null($countProductUrls) ? 0 : json_encode($countProductUrls) }},
                        {{ isset($resultviews['/search']['numberViews']) ? json_encode($resultviews['/search']['numberViews']) : 0 }},
                        {{ isset($resultviews['/checkout']['numberViews']) ? json_encode($resultviews['/checkout']['numberViews']) : 0 }},
                    ],
                    backgroundColor: [
                        "#fd3995",
                        "#34bfa3",
                        "#5d78ff"
                    ]
                }]
            },
            options: {
                cutoutPercentage: 70,
                legend: {
                    labels: {
                        fontFamily: 'Poppins',
                        boxWidth: 10,
                        usePointStyle: true
                    },
                    onClick: function() {
                        return '';
                    },
                    position: 'bottom'
                }
            }
        });
    </script>
@endsection
