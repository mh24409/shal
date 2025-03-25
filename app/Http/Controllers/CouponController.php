<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\User;
use DateTime;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_all_coupons'])->only('index');
        $this->middleware(['permission:add_coupon'])->only('create');
        $this->middleware(['permission:edit_coupon'])->only('edit');
        $this->middleware(['permission:delete_coupon'])->only('destroy');
    }
    public function index()
    {
        $coupons = Coupon::where('user_id', User::where('user_type', 'admin')->first()->id)->orderBy('id', 'desc')->get();
        return view('backend.marketing.coupons.index', compact('coupons'));
    }
    public function create()
    {
        return view('backend.marketing.coupons.create');
    }

    public function store(CouponRequest $request)
    {

        $user_id = User::where('user_type', 'admin')->first()->id;
        $lifetime = isset($request->lifetime) ? 1 : 0;
        $is_limited = isset($request->is_limited) ? 1 : 0;
        $is_user_limit = isset($request->is_user_limit) ? 1 : 0;

        Coupon::create(array_merge(['lifetime' => $lifetime, 'is_user_limit' => $is_user_limit, 'is_limited' => $is_limited, 'user_id' => $user_id], $request->validated()));

        flash(translate('Coupon has been saved successfully'))->success();
        return redirect()->route('coupon.index');
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        $coupon = Coupon::findOrFail(decrypt($id));
        return view('backend.marketing.coupons.edit', compact('coupon'));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {

        $lifetime = isset($request->lifetime) ? 1 : 0;
        $is_limited = isset($request->is_limited) ? 1 : 0;
        $is_user_limit = isset($request->is_user_limit) ? 1 : 0;
        $coupon->update(array_merge(['lifetime' => $lifetime, 'is_user_limit' => $is_user_limit, 'is_limited' => $is_limited], $request->validated()));

        flash(translate('Coupon has been updated successfully'))->success();
        return redirect()->route('coupon.index');
    }

    public function destroy($id)
    {
        Coupon::destroy($id);
        flash(translate('Coupon has been deleted successfully'))->success();
        return redirect()->route('coupon.index');
    }

    public function get_coupon_form(Request $request)
    {
        if ($request->coupon_type == "product_base") {
            $admin_id = \App\Models\User::where('user_type', 'admin')->first()->id;
            $products = filter_products(\App\Models\Product::where('user_id', $admin_id))->get();
            return view('partials.coupons.product_base_coupon', compact('products'));
        } elseif ($request->coupon_type == "cart_base") {
            return view('partials.coupons.cart_base_coupon');
        }
    }

    public function get_coupon_form_edit(Request $request)
    {
        if ($request->coupon_type == "product_base") {
            $coupon = Coupon::findOrFail($request->id);
            $admin_id = \App\Models\User::where('user_type', 'admin')->first()->id;
            $products = filter_products(\App\Models\Product::where('user_id', $admin_id))->get();
            return view('partials.coupons.product_base_coupon_edit', compact('coupon', 'products'));
        } elseif ($request->coupon_type == "cart_base") {
            $coupon = Coupon::findOrFail($request->id);
            return view('partials.coupons.cart_base_coupon_edit', compact('coupon'));
        }
    }
}
