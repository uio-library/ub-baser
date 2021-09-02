<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    protected $logGroup = null;

    private function addBaseContext(array $context)
    {
        $context['group'] = $this->logGroup;
        $user = \Auth::user();
        if ($user !== null) {
            $context['userId'] = $user->id;
            $context['user'] = $user->name;
        }
        return $context;
    }

    protected function structLog(string $message, array $context = [])
    {
        \Log::info($message, $this->addBaseContext($context));
    }

    protected function log(...$args)
    {
        \Log::info(call_user_func_array(
            'sprintf',
            $args
        ), $this->addBaseContext([]));
    }
}
