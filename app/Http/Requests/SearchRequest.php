<?php

namespace App\Http\Requests;

use App\Base;
use App\Http\Request;
use App\Schema\Schema;
use App\Services\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SearchRequest extends Request
{
    public function makeQueryBuilder(): Builder
    {
        return QueryBuilder::fromDataTableRequest($this);
    }

    public function getSchema(): Schema
    {
        return app(Base::class)->getSchema();
    }

    public function getFields(): array
    {
        return $this->getSchema()->keyed();
    }

    /**
     * Turns ['input1field' => 'A', 'input1value' => 'B', 'input2field' => 'C', 'input2value' => 'D', ...]
     * into ['A' => 'B', 'C' => 'D', ...] and [['A', 'B'], ['C', 'D']].
     *
     * @return array
     */
    public function parseQuery(): array
    {
        $fields = $this->getFields();
        $inputs = [];

        $query = $this->get('q', '');

        $parts = preg_split('/ (AND|OR) /', $query, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        if (count($parts)) {
            $parts[] = 'AND';
        }

        while (count($parts) > 0) {
            $part = array_shift($parts);
            $boolean = array_shift($parts);

            $tmp = explode(' ', $part, 3);
            if (count($tmp) == 2) {
                if (in_array($tmp[1], ['isnull', 'notnull'])) {
                    $tmp[] = null;
                }
            }
            if (count($tmp) != 3) {
                // Skip invalid value
                continue;
            }

            list($key, $op, $value) = $tmp;
            if (!isset($fields[$key])) {
                continue;
            }

            // $operator = Arr::get($this, "o$idx", $field->search->operators[0]);
            $inputs[] = [
                'field' => $key,
                'operator' => $op,
                'value' => $value,
                'boolean' => strtolower($boolean),
            ];
        }

        return $inputs;
    }

    public function getSortOrder($defaultSortOrder)
    {
        $out = [];
        $orderBy = $this->get('order', '');
        if ($orderBy === '') {
            return $defaultSortOrder;
        }

        $parts = explode(',', $this->get('order', ''));
        foreach ($parts as $part) {
            $tmp = explode(':', $part);
            if (count($tmp) == 1) {
                $out[] = ['key' => $tmp[0], 'direction' => 'asc'];
            } else {
                $out[] = ['key' => $tmp[0], 'direction' => $tmp[1]];
            }
        }

        return $out;
    }

    protected function getSortedIds(string $dir, array $defaultSortOrder): Collection
    {
        $schema = $this->getSchema();
        $fields = $schema->keyed();

        $sortOrder = $this->getSortOrder($defaultSortOrder);

        // Ensure deterministic ordering
        $sortOrder[] = ['key' => $schema->primaryId, 'direction' => 'desc'];

        $qb = $this->makeQueryBuilder();

        foreach ($sortOrder as $s) {
            $key = $s['key'];
            if (!isset($fields[$key])) {
                // Invalid
                continue;
            }
            $qb->orderBy($key, $s['direction']);
        }

        return $qb->select('id')->get()->pluck('id');
    }

    public function getNextRecord(array $defaultSortOrder, $id): int
    {
        $ids = $this->getSortedIds('asc', $defaultSortOrder);
        $pos = $ids->search($id);
        return isset($ids[$pos + 1]) ? $ids[$pos + 1] : $ids[0];
    }

    public function getPreviousRecord(array $defaultSortOrder, $id): int
    {
        $ids = $this->getSortedIds('desc', $defaultSortOrder);
        $pos = $ids->search($id);
        return isset($ids[$pos - 1]) ? $ids[$pos - 1] : $ids[count($ids) - 1];
    }
}
