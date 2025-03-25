<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $addHttpCookie = true;
     protected $except = [
         '/sslcommerz*',
         '/config_content',
         '/paytm*',
         '/payhere*',
         '/stripe*',
         '/iyzico*',
         '/payfast*',
         '/bkash*',
         'api/v2/bkash*',
         '/aamarpay*',
         '/mock_payments',
         '/apple-callback',
         '/aramex*',
         '/lnmo*',
         '/edfa-callback',
         '/login',
         '/verification'
     ];
}
