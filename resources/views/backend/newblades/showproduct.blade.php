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
     @if (auth()->user()->can('smtp_settings') && env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
         <div class="">
             <div class="alert alert-danger d-flex align-items-center">
                 {{ translate('Please Configure SMTP Setting to work all email sending functionality') }},
                 <a class="alert-link ml-2" href="{{ route('smtp_settings.index') }}">{{ translate('Configure Now') }}</a>
             </div>
         </div>
     @endif
     @can('admin_dashboard')
         <div class="d-flex align-items-center justify-content-start mb-3 md-gap">
             <img class="rounded" width="100px" src="{{ uploaded_asset($products->thumbnail_img) }}" alt="">
             <a href="{{ route('product', $products->slug) }}">
                 <h5>{{ $products->getTranslation('name') }}</h5>
             </a>
         </div>
         <div class="row">
             <div class="col-lg-12">
                 @isset($orders)
                     <table id="myTable" class="table table-striped  w-100">
                         <thead>
                             <tr>
                                 <th scope="col"> # </th>
                                 <th scope="col">{{ translate('Order id') }}</th>
                                 <th scope="col">{{ translate('Prise') }}</th>
                                 <th scope="col">{{ translate('variation') }}</th>
                                 <th scope="col">{{ translate('payment Status') }}</th>
                             </tr>
                         </thead>
                         <tbody>
                             @foreach ($orders as $item)
                                 <tr>
                                     <td scope="row">{{ $loop->index + 1 }}</td>
                                     <td> {{ $item->order_id }}</td>
                                     <td>{{ $item->price }}</td>
                                     <td>{{ $item->variation }}</td>
                                     <td> {{ $item->payment_status }}</td>
                                 </tr>
                             @endforeach
                         </tbody>
                     </table>
                 @endisset
             </div>
         </div>
     @endcan
 @endsection

 @section('script')
     <script>
         let table = new DataTable('#myTable');
         $('#myTable_wrapper .dt-search input').attr('placeholder', '{{ translate('Search') }}');
     </script>
 @endsection
