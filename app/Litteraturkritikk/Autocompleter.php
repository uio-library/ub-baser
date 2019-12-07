<?php

namespace App\Litteraturkritikk;

use App\Schema\Schema;
use App\Schema\SchemaField;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

class Autocompleter
{
    public function complete(Schema $schema, SchemaField $field, string $term)
    {
        switch ($field->key) {
            case 'publikasjon':
            case 'spraak':
            case 'verk_spraak':
            case 'verk_sjanger':
            case 'utgivelsessted':
            case 'verk_utgivelsessted':
                return $this->completeString($schema, $field, $term);

            case 'verk_tittel':
                return $this->completeWorkTitle($schema, $field, $term);

            case 'person':
            case 'verk_forfatter':
            case 'kritiker':
                return $this->completePerson($schema, $field, $term);

            case 'kritikktype':
            case 'tags':
                return $this->completeFromJsonArray($schema, $field, $term);

            default:
                throw new \ErrorException('Unknown search field');
        }
    }

    protected function listAllValues(Schema $schema, SchemaField $field)
    {
        $rows = \DB::table($schema->view)
            ->select($field->key)
            ->distinct()
            ->groupBy($field->key)
            ->get();

        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                'value' => $row->{$field->key},
            ];
        }
        return $data;
    }

    protected function noLetterChars(string $term)
    {
        // If the query contains no normal letters
        return strlen(preg_replace('/[^a-zA-ZæøåÆØÅ]/', '', $term)) < 1;
    }

    protected function completePerson(Schema $schema, SchemaField $field, string $term)
    {
        if ($this->noLetterChars($term)) {
            return [];
        }

        $query = PersonView::select(
            'id',
            'etternavn_fornavn',
            'etternavn',
            'fornavn',
            'kjonn',
            'fodt',
            'dod',
            'bibsys_id',
            'wikidata_id'
        )
            ->whereRaw(
                "any_field_ts @@ (phraseto_tsquery('simple', ?)::text || ':*')::tsquery",
                [$term]
            );

        $personRole = Arr::get($field, 'person_role');
        if ($personRole) {
            $query->whereRaw('? = ANY(roller)', [$personRole]);
        } else {
            // Skjul personer som ikke er i bruk
            $query->whereRaw('CARDINALITY(roller) != 0');
        }

        $data = [];
        foreach ($query->limit(25)->get() as $res) {
            $data[] = [
                'id' => $res->id,
                'value' => $res->etternavn_fornavn,
                'record' => $res,
            ];
        }
        return $data;
    }

    protected function completeFromJsonArray(Schema $schema, SchemaField $field, string $term)
    {
        if ($this->noLetterChars($term)) {
            return [];
        }

        # Ref: https://stackoverflow.com/a/31757242/489916
        # for the #>> '{}' magick
        $results = \DB::select("
            select
                distinct jd.value #>> '{}' as value
            from
                litteraturkritikk_records,
                jsonb_array_elements(litteraturkritikk_records.{$field->key}) as jd
            order by value
        ");

        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'value' => $row->value,
            ];
        }
        return $data;
    }

    protected function completeString(Schema $schema, SchemaField $field, string $term)
    {
        if (empty($term)) {
            return $this->listAllValues($schema, $field);
        }

        $query = \DB::table($schema->view)
            ->where($field->key, 'ilike', $term . '%');

        return $this->getResults($query, $field->key);

    }

    protected function completeWorkTitle(Schema $schema, SchemaField $field, string $term)
    {
        if ($this->noLetterChars($term)) {
            return [];
        }

        $query = \DB::table($schema->view)
            ->whereRaw(
                "verk_tittel_ts @@ (phraseto_tsquery('simple', ?)::text || ':*')::tsquery",
                [$term]
            );

        return $this->getResults($query, $field->key);
    }

    protected function getResults(Builder $query, string $key, int $limit = 25)
    {
        $query->groupBy($key)
            ->select([$key, \DB::raw('COUNT(*) AS cnt')])
            ->orderBy('cnt', 'desc');

        if ($limit > 0) {
            $query->limit($limit);
        }

        $data = [];
        foreach ($query->get() as $res) {
            $data[] = [
                'value' => $res->{$key},
                'count' => $res->cnt,
            ];
        }
        return $data;
    }
}
