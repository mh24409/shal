<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading</title>
</head>

<body>
    <div
        style="width: 100%; height: 100%;display: flex; justify-content: center; align-items: center;overflow: hidden;">
        
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            style="margin: auto; background: none; display: block; shape-rendering: auto;" width="345px" height="345px"
            viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <g transform="rotate(0 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s"
                        begin="-1.478494623655914s" repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(30 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s"
                        begin="-1.3440860215053765s" repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(60 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s"
                        begin="-1.2096774193548387s" repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(90 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s"
                        begin="-1.0752688172043012s" repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(120 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s"
                        begin="-0.9408602150537635s" repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(150 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s"
                        begin="-0.8064516129032259s" repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(180 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s"
                        begin="-0.6720430107526882s" repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(210 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s"
                        begin="-0.5376344086021506s" repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(240 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s"
                        begin="-0.40322580645161293s" repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(270 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s"
                        begin="-0.2688172043010753s" repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(300 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s"
                        begin="-0.13440860215053765s" repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(330 50 50)">
                <rect x="47" y="22" rx="3" ry="7" width="6" height="14" fill="#1d3f72">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1.6129032258064517s" begin="0s"
                        repeatCount="indefinite"></animate>
                </rect>
            </g> 
        </svg>
    </div>
</body>

@if($order)
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
        <script>
            $(document).ready(function() {
                Pusher.logToConsole = true;
                var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {cluster: '{{ env('PUSHER_APP_CLUSTER') }}'});
                var channel = pusher.subscribe('order{{ $order->id }}');
                channel.bind('App\\Events\\OrderEdfaStatus', function(data) {
                    var paymentStatus = data.payment_status;
                    var order_id = data.order_id;
                    console.log("Payment Status:", paymentStatus);
                    if (paymentStatus == "paid") {
                        var routeUrl = "{{ route('order_confirmed', ['code' => ':code']) }}";
                        routeUrl = routeUrl.replace(':code', '{{ $order->code }}');
                        window.location.href = routeUrl;
                    }else{
                        var routeUrl = "{{ route('order_confirmed', ['code' => ':code']) }}";
                        routeUrl = routeUrl.replace(':code', '{{ $order->code }}');
                        window.location.href = routeUrl;
                    }
                });
            });
        </script>
        
        @endif
</html>