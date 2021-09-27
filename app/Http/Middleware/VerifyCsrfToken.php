<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'delivery/*',
        'api/*',
        '*/embedded-form-*',
        'payments/stripe/credit-card*',
        'payments/paddle/card*/hook',
        'payments/payumoney-success/*',
        'payments/payumoney-fail/*',
    ];
}
