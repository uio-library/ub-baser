<?php

namespace App\Http;

use App\Base;
use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    /**
     * Get a Base instance from the first part of the URL.
     * Returns null if no base is part of the request URL.
     * @throws \Illuminate\Http\Exceptions\HttpResponseException;
     * @return Base|null
     */
    public function getBase()
    {
        $basepath = explode('/', $this->path())[0];
        if (empty($basepath)) {
            return null;
        }
        $base = Base::where(['basepath' => $basepath])->first();
        if (is_null($base)) {
            return null;
        }
        return $base;
    }
}
