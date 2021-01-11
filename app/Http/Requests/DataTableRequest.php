<?php

namespace App\Http\Requests;

/**
 * A DataTableRequest extends SearchRequest with column selection and ordering.
 */
class DataTableRequest extends SearchRequest
{
    /**
     * Get an ordered list of requested fields to to be SELECTed.
     *
     * @return array
     */
    protected function getSelectedFields(): array
    {
        $schema = $this->getSchema();
        $fields = $schema->keyed();
        $requestedFields = [];
        $keysSeen = [];

        foreach ($this->get('columns', []) as $idx => $column) {
            if (!isset($fields[$column])) {
                throw new \RuntimeException('Invalid column name requested: ' . $column);
            }
            $requestedFields[$idx] = $fields[$column];
            $keysSeen[] = $column;
        }

        // Always include the id column, even if not explicitly requested.
        if (!in_array($schema->primaryId, $keysSeen)) {
            $requestedFields[] = $fields[$schema->primaryId];
        }

        return $requestedFields;
    }

    /**
     * Get a list of valid ORDER BY statements from the request.
     *
     * @param array $requestedFields
     * @return array
     */
    protected function getOrderBy(array $requestedFields): array
    {
        $ordering = [];

        foreach ($this->get('order', []) as $order) {
            $idx = $order['column'];
            if (!isset($requestedFields[(int) $idx])) {
                throw new \RuntimeException('Invalid order by requested: ' . $idx);
            }
            if (!in_array($order['dir'], ['asc', 'desc'])) {
                throw new \RuntimeException('Invalid sort direction');
            }
            $field = $requestedFields[$idx];
            $ordering[] = $field->getSortColumn() . ' ' . $order['dir'];
        }

        return $ordering;
    }

    public function makeQueryBuilderAndColumnMap(): array
    {
        $schema = $this->getSchema();
        $queryBuilder = $this->makeQueryBuilder();

        // SELECT
        $select = [];
        $colMap = [];
        $requestedFields = $this->getSelectedFields();
        foreach ($requestedFields as $idx => $field) {
            $select[] = $field->getViewColumn();
            $colMap[$field->getViewColumn()] = $field->key;
        }
        $queryBuilder->select($select);

        // ORDER BY
        foreach ($this->getOrderBy($requestedFields) as $orderBy) {
            $queryBuilder->orderByRaw($orderBy);
        }

        // Ensure deterministic ordering
        $queryBuilder->orderBy($schema->primaryId, 'desc');

        return [$queryBuilder, $colMap];
    }
}
