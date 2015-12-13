<?php

namespace App;

use Illuminate\Http\Request;

class RecordQueryBuilder
{
    protected $request;
    protected $prefix;
    protected $className;
    public $query;
    public $sortColumn;
    public $sortOrder;

    public function __construct(Request $request, $prefix, $className)
    {
        $this->request = $request;
        $this->prefix = $prefix;
        $this->className = $className;
    }

    public function make()
    {
        $this->query = (new $this->className())->newQuery();
        $this->sortColumn = $this->request->input('sort', config('baser.dommer.default.column'));
        $this->sortOrder = $this->request->input('order', config('baser.dommer.default.order'));
        $this->query->orderBy($this->sortColumn, $this->sortOrder);

        foreach (config('baser.dommer.columns') as $col) {
            if ($this->request->has($col['field'])) {
                if (array_get($col, 'type') == 'text') {
                    $this->query->where($this->prefix . '.' . $col['field'], 'LIKE', '%' . $this->request->get($col['field']) . '%');
                } else {
                    $this->query->where($this->prefix . '.' . $col['field'], '=', $this->request->get($col['field']));
                }
            }
        }
    }

    public function getColumns()
    {
        $columns = config('baser.dommer.columns');

        foreach ($columns as &$d) {
            $d['link'] = Request('url') . '?' . http_build_query([
                'sort'  => $d['field'],
                'order' => ($d['field'] == $this->sortColumn && $this->sortOrder == 'asc') ? 'desc' : 'asc',
            ]);
        }

        return $columns;
    }
}