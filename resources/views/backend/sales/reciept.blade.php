@extends('backend.layouts.app')

@section('content')
    <section >
        <div id="reciept_content" style="border: solid #d0caca 0.9px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                     <img style="width: 200px" src="https://cdn.turbo-eg.com/main%20logo.svg" alt=" ">
                 <div style="display: flex; flex-direction: column; justify-content: center; align-items: center">
                    <img class="barcode reciept_barcode" alt="Barcode" ></svg>
                    <span style="margin-top: 10px" class="font-weight-bold"  id="code" data-value="{{ $data['code'] }}"> {{translate('Code')}} {{ $data['code'] }}</span>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center; align-items: start">
                    <span class="font-weight-bold" > {{translate('City')}} : {{ $data['city'] }} </span>
                    <span class="font-weight-bold mt-2">{{translate('State')}} : {{ $data['state'] }} </span>
                </div>
            </div>
            <div>
                <table style="border: solid #d0caca 0.9px; width:100%; ">
                    <thead>
                        <tr>
                            <th scope="col"> {{translate('Sender')}}: {{ $data['sender'] }}</th>
                            <th scope="col"> {{translate('Receiver')}}: {{ $data['reciever'] }}</th>
                            <th scope="col"> {{translate('Total')}}: {{ $data['total'] }} </th>
                        </tr>
                    </thead> 
                </table>
            </div>
            <div style="display: flex; flex-direction: row; width:100%; padding: 10px">
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: start">
                    <span class="font-weight-bold"> {{translate('Address')}} : {{ $data['address'] }} </span>
                    <span class="font-weight-bold mt-2"> {{translate('Content')}} : {{ $data['content'] }} </span>
                </div>
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: start">
                    <span class="font-weight-bold"> {{translate('Phone')}} : {{ $data['phone'] }} </span>
                    <span class="font-weight-bold mt-2" >{{translate('Notes')}} : {{ $data['notes'] }} </span>
                </div>
            </div>
        </div>
    </section>
   <div class=" mt-4 d-flex justify-content-center" >  <button class="btn btn-lg btn-warning"  onclick="printContent()" > {{translate('Print Reciept')}} </button> </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
        JsBarcode(".reciept_barcode", {{$data['code']}}, {
                    format: "CODE128",
                    displayValue: true,
                    fontSize: 16,
                    lineColor: "#000"
                })
        }); 
      function printContent( ) {
        var printContents = document.getElementById('reciept_content').outerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        } 
    </script>
@endsection
