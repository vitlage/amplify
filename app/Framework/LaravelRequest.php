<?php

namespace App\Framework;

use Illuminate\Http\Request as BaseRequest;
use App\Framework\SymfonyRequest;

class LaravelRequest extends BaseRequest
{
    /**
     * Create a new Illuminate HTTP request from server variables.
     *
     * @return static
     */
    public static function capture($uri = null)
    {
        static::enableHttpMethodParameterOverride();

        return static::createFromBase(SymfonyRequest::createFromGlobals($uri));
    }
}
