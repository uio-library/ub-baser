<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RecordQBuilderLetras
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
        $this->sortColumn = $this->request->input('sort', config('baser.letras.default_sort_column'));
        $this->sortOrder = $this->request->input('order', config('baser.letras.default_sort_order'));
        $this->query->orderBy($this->sortColumn, $this->sortOrder);

        $fields = LetrasRecord::getFlatSchema();

        foreach ($fields as $col) {
            if ($this->request->has($col['key'])) {
                $this->query->where($this->prefix . '.' . $col['key'], 'ilike', '%' . $this->request->get($col['key']) . '%');
            }
        }
    }
}
