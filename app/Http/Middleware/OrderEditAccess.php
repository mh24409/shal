<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OrderEditAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $order = $request->order;
        $allowed_order_status = [
            'pending',
        ];





        if(!in_array($order->delivery_status,$allowed_order_status) || $order->added_by_admin != 1)
         {
            $order_status = str_replace('_','  ',$order->delivery_status);
            flash(translate("Edit option allowed only if the order status is pending and not a website ordre"))->warning();
            return redirect()->back();
        }

        return $next($request);
    }
}
