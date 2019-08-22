<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $logGroup = null;

    protected function log(...$args)
    {
        \Log::info(call_user_func_array(
            'sprintf',
            $args
        ), [
            'userId' => \Auth::user()->id,
            'user' => \Auth::user()->name,
            'group' => $this->logGroup,
        ]);
    }
}
