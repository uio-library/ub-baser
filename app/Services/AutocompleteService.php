<?php

namespace App\Services;

use App\Base;
use App\Schema\SchemaField;
use Illuminate\Database\Query\Builder;

/**
 * General autocomplete service that can be extended by the different bases if needed.
 */
class AutocompleteService implements AutocompleteServiceInterface
{
    /**
     * Classname of the model to query.
     *
     * @var string
     */
    protected $model;

    /**
     * Max number of results to fetch.
     *
     * @var int
     */
    protected $limit = 25;

    /**
     * Max number of results to fetch for lists.
     *
     * @var int
     */
    protected $listerLimit = 1000;

    /**
     * Completer methods to use with each field.
     *
     * @var array
     */
    protected $completers = [];

    /**
     * Lister methods to use with each field.
     *
     * @var array
     */
    protected $listers = [];

    /**
     * The completer method to use if no field-specific completer was found.
     * If set to null, an error will be thrown if no completer was found.
     *
     * @var string
     */
    protected $defaultCompleter = 'simpleStringCompleter';

    /**
     * The lister method to use if no field-specific completer was found.
     * If set to null, an error will be thrown if no lister was found.
     *
     * @var string
     */
    protected $defaultLister = 'simpleLister';

    /**
     * AutocompleteService constructor.
     *
     * @param Base $base
     */
    public function __construct(Base $base)
    {
        $this->model = $base->getClass('RecordView');
    }

    /**
     * Get autocomplete suggestions for an input term.
     *
     * @param SchemaField $field The column to complete against
     * @param string|null $term The term to complete
     * @return array
     */
    public function complete(SchemaField $field, ?string $term): array
    {
        if (empty($term)) {
            $lister = $this->getLister($field->key);
            return $lister($field);
        }

        $completer = $this->getCompleter($field->key);
        return $completer($field, $term);
    }

    protected function noLetterChars(string $term)
    {
        // If the query contains no normal letters
        return strlen(preg_replace('/[^a-zA-ZæøåÆØÅ]/', '', $term)) < 1;
    }

    /**
     * Register a completer for a specific schema field. This method can be
     * used by base-specific services extending the default AutocompleteService.
     *
     * @param string $fieldName
     * @param callable $fn
     */
    protected function registerCompleter(string $fieldName, callable $fn)
    {
        $this->completers[$fieldName] = $fn;
    }

    /**
     * Register a lister for a specific schema field. This method can be
     * used by base-specific services extending the default AutocompleteService.
     *
     * @param string $fieldName
     * @param callable $fn
     */
    protected function registerLister(string $fieldName, callable $fn)
    {
        $this->listers[$fieldName] = $fn;
    }

    /**
     * Get a lister for a given field.
     *
     * @param $fieldName
     * @return callable
     */
    protected function getLister($fieldName)
    {
        if (isset($this->listers[$fieldName])) {
            return [$this, $this->listers[$fieldName]];
        }
        if (is_null($this->defaultLister)) {
            throw new \RuntimeException('No lister found for the field: ' . $fieldName);
        }
        return [$this, $this->defaultLister];
    }

    /**
     * Get a completer for a given field.
     *
     * @param $fieldName
     * @return callable
     */
    protected function getCompleter($fieldName): callable
    {
        if (isset($this->completers[$fieldName])) {
            return [$this, $this->completers[$fieldName]];
        }
        if (is_null($this->defaultCompleter)) {
            throw new \RuntimeException('No completer found for the field: ' . $fieldName);
        }
        return [$this, $this->defaultCompleter];
    }

    /**
     * Get query results, most popular first.
     *
     * @param Builder $query
     * @return array
     */
    protected function getResultsOrderedByPopularity(Builder $query, $limit = null): array
    {
        $query->groupBy('prefLabel')
            ->addSelect(['count' => \DB::raw('COUNT(*)')])
            ->orderBy('count', 'desc');

        $limit = $limit ?: $this->limit;

        if ($limit > 0) {
            $query->limit($this->limit);
        }

        return $query->get()->toArray();
    }

    /**
     * Create a new query builder using the default model for this base.
     *
     * @return Builder
     */
    protected function newQuery(): Builder
    {
        $table = (new $this->model())->getTable();
        return \DB::query()->from($table);
    }

    /**
     * Create a new query builder to get all the values from a JSON array column.
     *
     * @param SchemaField $field
     * @param string|null $table
     * @return Builder
     */
    protected function newJsonArrayQuery(SchemaField $field, $table = null): Builder
    {
        $table = $table ?: $this->newQuery()->from;

        // Ref: https://stackoverflow.com/a/31757242/489916
        // for the #>> '{}' magick
        return \DB::table(function ($subquery) use ($table, $field) {
            $subquery->selectRaw("jd.value #>> '{}' as \"prefLabel\"")
                ->fromRaw("{$table}, jsonb_array_elements({$table}.{$field->key}) as jd");
        }, 'all_values')->select('prefLabel');
    }

    /**
     * Completer that works with JSON array columns
     *
     * @param SchemaField $field
     * @param string $term
     * @return array
     */
    protected function jsonArrayCompleter(SchemaField $field, string $term): array
    {
        $query = $this->newJsonArrayQuery($field)
            ->where('prefLabel', 'ilike', $term . '%');

        return $this->getResultsOrderedByPopularity($query);
    }

    /**
     * Lister that works with JSON array columns.
     *
     * @param SchemaField $field
     * @return array
     */
    protected function jsonArrayLister(SchemaField $field): array
    {
        $query = $this->newJsonArrayQuery($field);

        return $this->getResultsOrderedByPopularity($query, $this->listerLimit);
    }

    /**
     * Completer that works with a Postgres tsvector column. The tsvector column must have
     * the same name as the schema field column, but with a "_ts" suffix.
     *
     * @param SchemaField $field
     * @param string $term
     * @return array
     */
    protected function textSearchCompleter(SchemaField $field, string $term): array
    {
        if ($this->noLetterChars($term)) {
            return [];
        }

        $column = $field->key;
        $ts_column = $column . '_ts';

        $query = $this->newQuery()
            ->select("{$column} as prefLabel")
            ->whereRaw(
                "{$ts_column} @@ (phraseto_tsquery('simple', ?)::text || ':*')::tsquery",
                [$term]
            );

        return $this->getResultsOrderedByPopularity($query);
    }

    /**
     * Simple string completer using the ILIKE operator.
     *
     * @param SchemaField $field
     * @param string $term
     * @return array
     */
    protected function simpleStringCompleter(SchemaField $field, string $term): array
    {
        $query = $this->newQuery()
            ->select("{$field->key} as prefLabel")
            ->where($field->key, 'ilike', $term . '%');

        return $this->getResultsOrderedByPopularity($query);
    }

    /**
     * List all available values.
     *
     * @param SchemaField $field
     * @return array
     */
    protected function simpleLister(SchemaField $field): array
    {
        $query = $this->newQuery()
            ->select("{$field->key} as prefLabel");

        return $this->getResultsOrderedByPopularity($query, $this->listerLimit);
    }
}
