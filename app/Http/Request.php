<?php

namespace App\Http;

use App\Base;
use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    /**
     * Get a Base instance from the first part of the URL.
     * Throw 404 if not found.
     * @throws \Illuminate\Http\Exceptions\HttpResponseException;
     * @return Base
     */
    public function getBase()
    {
        $basepath = explode('/', $this->path())[0];
        $base = Base::where(['basepath' => $basepath])->first();
        if (is_null($base)) {
            abort('404', trans('base.notfound', ['name' => $basepath]));
        }
        return $base;
    }
}
