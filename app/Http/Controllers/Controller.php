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
        $context = [
            'group' => $this->logGroup,
        ];
        $user = \Auth::user();
        if ($user !== null) {
            $context['userId'] = $user->id;
            $context['user'] = $user->name;
        }
        \Log::info(call_user_func_array(
            'sprintf',
            $args
        ), $context);
    }
}
