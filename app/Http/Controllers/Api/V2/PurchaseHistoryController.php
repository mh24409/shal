<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\PurchasedResource;
use App\Http\Resources\V2\PurchaseHistoryMiniCollection;
use App\Http\Resources\V2\PurchaseHistoryCollection;
use App\Http\Resources\V2\PurchaseHistoryItemsCollection;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Upload;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PurchaseHistoryController extends Controller
{
    public function index(Request $request)
    {
        $order_query = Order::query();
        if ($request->payment_status != "" || $request->payment_status != null) {
            $order_query->where('payment_status', $request->payment_status);
        }
        if ($request->delivery_status != "" || $request->delivery_status != null) {
            $delivery_status = $request->delivery_status;
            $order_query->whereIn("id", function ($query) use ($delivery_status) {
                $query->select('order_id')
                    ->from('order_details')
                    ->where('delivery_status', $delivery_status);
            });
        }
        return new PurchaseHistoryMiniCollection($order_query->where('user_id', auth()->user()->id)->latest()->paginate(5));
    }

    public function details($id)
    {
        $order_detail = Order::where('id', $id)->where('user_id', auth()->user()->id)->get();
        // $order_query = auth()->user()->orders->where('id', $id);

        // return new PurchaseHistoryCollection($order_query->get());
        return new PurchaseHistoryCollection($order_detail);
    }
public function items($id)
{
 $order_id = Order::select('id')->where('id', $id)->first();
    $order_query = OrderDetail::where('order_id', $order_id->id)->get();

    $data = $order_query->map(function($order_detail) {
        $refund_section = false;
        $refund_button = false;
        $refund_label = "";
        $refund_request_status = 99;
        if (addon_is_activated('refund_request')) {
            $refund_section = true;
            $no_of_max_day = get_setting('refund_request_time');
            $last_refund_date = $order_detail->created_at->addDays($no_of_max_day);
            $today_date = \Carbon\Carbon::now();
            if ($order_detail->product != null &&
                $order_detail->product->refundable != 0 &&
                $order_detail->refund_request == null &&
                $today_date <= $last_refund_date &&
                $order_detail->payment_status == 'paid' &&
                $order_detail->delivery_status == 'delivered') {
                $refund_button = true;
            } else if ($order_detail->refund_request != null && $order_detail->refund_request->refund_status == 0) {
                $refund_label = "Pending";
                $refund_request_status = $order_detail->refund_request->refund_status;
            } else if ($order_detail->refund_request != null && $order_detail->refund_request->refund_status == 2) {
                $refund_label = "Rejected";
                $refund_request_status = $order_detail->refund_request->refund_status;
            } else if ($order_detail->refund_request != null && $order_detail->refund_request->refund_status == 1) {
                $refund_label = "Approved";
                $refund_request_status = $order_detail->refund_request->refund_status;
            } else if ($order_detail->product->refundable != 0) {
                $refund_label = "N/A";
            } else {
                $refund_label = "Non-refundable";
            }
        }

        $image_id = $order_detail->product->thumbnail_img;
        $image = Upload::where('id',$image_id)->value('file_name');
        $filename = basename($image);

        return [
            'id' => $order_detail->id,
            'product_id' => $order_detail->product->id,
            'product_name' => $order_detail->product ->name,
            'variation' => $order_detail->variation,
            'price' => format_price($order_detail->price),
            'tax' => format_price($order_detail->tax),
            'shipping_cost' => format_price($order_detail->shipping_cost),
            'coupon_discount' => format_price($order_detail->coupon_discount),
            'quantity' => (int)$order_detail->quantity,
            'payment_status' => $order_detail->payment_status,
            'payment_status_string' => ucwords(str_replace('_', ' ', $order_detail->payment_status)),
            'delivery_status' => $order_detail->delivery_status,
            'delivery_status_string' => $order_detail->delivery_status == 'pending' ? "Order Placed" : ucwords(str_replace('_', ' ', $order_detail->delivery_status)),
            'refund_section' => $refund_section,
            'refund_button' => $refund_button,
            'refund_label' => $refund_label,
            'refund_request_status' => $refund_request_status,
            'image' => $filename,
        ];
    });

    $response = [
        'data' => $data,
        'success' => true,
        'status' => 200
    ];

    return response()->json($response);
}
    public function digital_purchased_list()
    {
        $order_detail_products = OrderDetail::whereHas('order', function($q){
    		$q->where('payment_status', 'paid');
            $q->where('user_id', auth()->id());
		})->with(['product' => function($query){
            $query->where('digital', 1);
          }])
           ->paginate(15);
      
    //   $products = Product::with(['orderDetails', 'orderDetails.order' => function($q) {
    //          $q->where('payment_status', 'paid');
    //          $q->where('user_id', auth()->id());
    //     }])
    //     ->where('digital', 1)
    //     ->paginate(15);  



        return PurchasedResource::collection($order_detail_products);
    }
}
