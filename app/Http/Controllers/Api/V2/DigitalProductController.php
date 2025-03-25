<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Upload;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\OrderDetail;
use File;

class DigitalProductController extends Controller
{
    public function download(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $orders = Order::select("id")->where('user_id', auth()->user()->id)->pluck('id');
        $orderDetails = OrderDetail::where("product_id", $request->id)->whereIn("order_id", $orders)->get();
        if (auth()->user()->user_type == 'admin' || auth()->user()->id == $product->user_id || $orderDetails) {
            $upload = Upload::findOrFail($product->file_name);
            if (env('FILESYSTEM_DRIVER') == "s3") {
                return \Storage::disk('s3')->download($upload->file_name, $upload->file_original_name . "." . $upload->extension);
            } else {
                if (file_exists(base_path('public/' . $upload->file_name))) {
                    $file = public_path() . "/$upload->file_name";
                    return response()->download($file, $upload->file_original_name . "." . $upload->extension);
                }
            }
        } else {
            return response()->download(File("dd.pdf"), "failed.jpg");
        }
    }
    public function get_coupons(Request $request)
    {
        $coupons = Coupon::get();

        $coupons = $coupons->map(function ($coupon) {
            $coupon->start_date = date('Y-m-d', $coupon->start_date);
            $coupon->end_date = date('Y-m-d', $coupon->end_date);
            return $coupon;
        });
        $count = $coupons->count();

        if ($count > 0) {
            return response()->json([
                'success' => true,
                'count' => $count,
                'coupons' => $coupons,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No coupons found.',
            ]);
        }
    }
}
