<?php

namespace App\Http\Controllers;

use App\LogEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Monolog\Logger;

class LogEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $query = LogEntry::where('level', '>=', Logger::INFO)
            ->orderBy('id', 'desc')
            ->limit(200);

        $filters = [];

        if ($request->has('user')) {
            $query->where('context', '@>', '{"user": "'.$request->user.'"}');
            $filters[] = 'user:'.$request->user;
        }

        if ($request->has('group')) {
            $query->where('context', '@>', '{"group": "'.$request->group.'"}');
            $filters[] = 'group:'.$request->group;
        }

        return response()->view('log.index', [
            'entries' => $query->get(),
            'filters' => $filters,
        ]);
    }
}
