<?php

namespace App\Bases\Litteraturkritikk;

use App\Schema\SchemaField;
use Illuminate\Support\Arr;

class AutocompleteService extends \App\Services\AutocompleteService
{
    /**
     * The completer method to use if no field-specific completer was found.
     * If set to null, an error will be thrown if no completer was found.
     *
     * @var string
     */
    protected $defaultCompleter = null;

    /**
     * The lister method to use if no field-specific completer was found.
     * If set to null, an error will be thrown if no lister was found.
     *
     * @var string
     */
    protected $defaultLister = null;

    /**
     * Completer methods to use with each field.
     *
     * @var array
     */
    protected $completers = [
        'publikasjon' => 'simpleStringCompleter',

        'spraak' => 'languageCompleter',
        'verk_spraak' => 'languageCompleter',
        'verk_originalspraak' => 'languageCompleter',

        'verk_sjanger' => 'simpleStringCompleter',
        'utgivelsessted' => 'simpleStringCompleter',
        'verk_utgivelsessted' => 'simpleStringCompleter',

        'kritikktype' => 'jsonArrayCompleter',
        'verk_forfatter:person_role' => 'jsonArrayCompleter',
        'tags' => 'jsonArrayCompleter',

        'verk_tittel' => 'textSearchCompleter',
        'verk_originaltittel' => 'textSearchCompleter',
        'verk_originaltittel_transkribert' => 'textSearchCompleter',

        'persons' => 'personCompleter',
        'verk_forfatter' => 'personCompleter',
        'kritiker' => 'personCompleter',
    ];

    /**
     * Lister methods to use with each field.
     *
     * @var array
     */
    protected $listers = [
        'kritikktype' => 'jsonArrayLister',
        'verk_forfatter:person_role' => 'jsonArrayLister',
        'tags' => 'jsonArrayLister',
    ];

    protected function personCompleter(SchemaField $field, string $term): array
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
                'prefLabel' => $res->etternavn_fornavn,
                'record' => $res,
            ];
        }

        return $data;
    }

    /**
     * We use a common index for all the different language fields.
     *
     * @param SchemaField $field
     * @param string $term
     * @return array
     */
    protected function languageCompleter(SchemaField $field, string $term): array
    {
        $languageFields = [
            'spraak',
            'verk_spraak',
            'verk_originalspraak',
        ];

        $subQueries = implode(' union all ', array_map(function ($field) {
            return 'select jsonb_array_elements_text(' . $field . ') as "prefLabel" from litteraturkritikk_records';
        }, $languageFields));

        $query = \DB::table(\DB::raw('(' . $subQueries . ') subquery'))
            ->where('prefLabel', 'ilike', $term . '%')
            ->where('prefLabel', 'not like', '%;%')
            ->select('prefLabel');

        return $this->getResultsOrderedByPopularity($query);
    }
}
