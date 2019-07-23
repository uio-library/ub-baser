<?php
namespace App;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RecordQueryBuilderOpes
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
    

// Her bygges det opp en liste. den er baser paa kolonner definert i felles fila 
    public function make()
    {
        $this->query = (new $this->className())->newQuery();
        $this->sortColumn = $this->request->input('sort', config('baser.opes.default.column'));
        $this->sortOrder = $this->request->input('order', config('baser.opes.default.order'));
        $this->query->orderBy($this->sortColumn, $this->sortOrder);
        foreach (config('baser.opes.columns') as $col) {
            if ($this->request->has($col['field'])) {
                if (Arr::get($col, 'type') == 'text') {
                    $this->query->where($this->prefix . '.' . $col['field'], 'LIKE', '%' . $this->request->get($col['field']) . '%');
                } else {
                    $this->query->where($this->prefix . '.' . $col['field'], '=', $this->request->get($col['field']));
                }
            }
        }
    }

    // Her hentes info om kollonner fra et felles fil. igjen saa er det en sub definision av hva som skal vaere med
    public function getColumns()
    {
        $columns = config('baser.opes.columns');
        foreach ($columns as &$d) {
            $d['link'] = Request('url') . '?' . http_build_query([
                'sort'  => $d['field'],
                'order' => ($d['field'] == $this->sortColumn && $this->sortOrder == 'asc') ? 'desc' : 'asc',
            ]);
        }
        return $columns;
    }
}