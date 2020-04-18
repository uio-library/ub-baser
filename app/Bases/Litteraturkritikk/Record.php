<?php

namespace App\Bases\Litteraturkritikk;

use App\Exceptions\HttpErrorResponse;
use App\Exceptions\NationalLibraryRecordNotFound;
use App\Services\NationalLibraryApi;
use Http\Client\Exception\RequestException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends \App\Record
{
    use SoftDeletes;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        //'forfattere',
        //'kritikere',
    ];

    public static $prefix = 'litteraturkritikk';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'litteraturkritikk_records';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'kritikktype' => 'array',
        'tags' => 'array',
        'spraak' => 'array',
        'verk_spraak' => 'array',
        'verk_originalspraak' => 'array',
    ];

    /**
     * ------------------------------------------------------------------------
     * Relations
     * ------------------------------------------------------------------------
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    /**
     * The persons that are part of this record.
     */
    public function persons()
    {
        return $this->belongsToMany(
            'App\Bases\Litteraturkritikk\Person',
            'litteraturkritikk_record_person'
            //'record_id',
            //'person_id'
        )
            ->using(RecordPerson::class)
            ->withPivot('person_role', 'kommentar', 'pseudonym', 'posisjon')
            ->orderBy('litteraturkritikk_record_person.posisjon', 'asc');
    }

    /**
     * The persons that are part of this record.
     */
    public function forfattere()
    {
        return $this->persons()
            ->whereJsonDoesntContain('person_role','kritiker');
    }

    /**
     * The persons that are part of this record.
     */
    public function kritikere()
    {
        return $this->persons()
            ->whereJsonContains('person_role', 'kritiker');
    }

    /**
     * ------------------------------------------------------------------------
     * Accessors and muators
     * ------------------------------------------------------------------------
     */

    /**
     * Validate/mutate URL field against external API.
     *
     * @param string $field
     * @param string|null $value
     * @return string|null
     */
    protected function mutateUrlField(string $field, ?string $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        /* @var NationalLibraryApi $api */
        $api = app(NationalLibraryApi::class);

        $urls = explode(' ', $value);

        $urls = array_map(function ($url) use ($api, $field) {
            try {
                $newUrl = $api->resolveUrl($url);
                if ($newUrl !== $url) {
                    \Log::info("Endret NB-URL-en <a href=\"$url\">$url</a> til <a href=\"$newUrl\">$newUrl</a>");
                }
                return $newUrl;
            } catch (HttpErrorResponse $ex) {
                \Log::info("Klarte ikke å slå opp NB-urlen <a href=\"$url\">$url</a>");

                throw new NationalLibraryRecordNotFound($url, $field);
            }
        }, $urls);

        return implode(' ', $urls);
    }

    /**
     * Mutator for the fulltekst_url field.
     *
     * @param  string|null  $value
     * @return void
     */
    public function setFulltekstUrlAttribute(?string $value): void
    {
        $this->attributes['fulltekst_url'] = $this->mutateUrlField('fulltekst_url', $value);
    }

    /**
     * Mutator for the verk_fulltekst_url field.
     *
     * @param  string|null  $value
     * @return void
     */
    public function setVerkFulltekstUrlAttribute(?string $value): void
    {
        $this->attributes['verk_fulltekst_url'] = $this->mutateUrlField('verk_fulltekst_url', $value);
    }

    /**
     * ------------------------------------------------------------------------
     * Misc
     * ------------------------------------------------------------------------
     */

    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    public function getTitle(): string
    {
        if ($this->tittel) {
            return "Post {$this->id}: «{$this->tittel}»";
        }
        return "Post {$this->id}";
    }

    public function formatKritikkType($name)
    {
        if ($name == 'debatt') {
            $name = 'Debatt';
        }

        return ucfirst($name);
    }

    public function preferredKritikktype($types)
    {
        $preferredTypes = [
            'forfatterportrett',
            'dagskritikk',
            'teaterkritikk',
            'debatt',
            'essay',
            'bokanmeldelse',
            'artikkel',
            'vitenskapelig artikkel',
            'oversiktsartikkel',
            'samtaleprogram',
        ];
        foreach ($preferredTypes as $key) {
            if (in_array($key, $types)) {
                return $key;
            }
        }

        return '';
    }

    public function kritikktypeSkilleord($preferredType)
    {
        $separators = [
            'forfatterportrett' => 'av',
            'dagskritikk' => 'av',
            'teaterkritikk' => 'av',
            'bokanmeldelse' => 'av',
            'debatt' => 'om',
            'artikkel' => 'om',
            'oversiktsartikkel' => 'om',
            'vitenskapelig artikkel' => 'om',
            'essay' => 'om',
            'samtaleprogram' => 'om',
        ];

        return isset($separators[$preferredType]) ? $separators[$preferredType] : 'av/om';
    }

    public function formatVerk()
    {
        $forfatter_delimiter = '; ';
        $forfatter_verk_delimiter = '. ';

        $repr = '';
        $forfattere = [];
        foreach ($this->forfattere as $person) {
            $forfatter = strval($person);
            foreach ($person->pivot->person_role as $role) {
                $forfatter .= ($role != 'forfatter') ? ' (' . $role . ')' : '';
            }
            $forfattere[] = $forfatter;
        }

        $forfattere = implode($forfatter_delimiter, $forfattere);

        $repr .= $forfattere;

        if ($forfattere && $this->verk_tittel) {
            $repr .= $forfatter_verk_delimiter;
        }
        if ($this->verk_tittel) {
            $repr .= '«' . $this->verk_tittel . '»';
        }
        if ($this->verk_dato) {
            $repr .= ' (' . $this->verk_dato . ')';
        }

        return $repr;
    }

    public function formatKritikk()
    {

        $repr = '';

        $kritikere = [];
        foreach ($this->kritikere as $person) {
            $kritikere[] = strval($person);
        }

        $kritikere = implode(', ', $kritikere);

        $repr .= $kritikere ?: 'ukjent';

        if ($this->dato) {
            $repr .= ' (' . $this->dato . ')';
        }

        if (strlen($repr) && $this->tittel) {
            $repr .= '. ';
        }

        if ($this->tittel) {
            $repr .= '«' . $this->tittel . '»';
        }

        if (strlen($repr) && $this->publikasjon) {
            $repr .= '. ';
        }

        if ($this->publikasjon) {
            $repr .= '<em>' . $this->publikasjon . '</em>';
        }
        if ($this->bind) {
            $repr .= ' bd. ' . $this->bind . '';
        }
        if ($this->nummer) {
            $repr .= ' nr. ' . $this->nummer . '';
        }

        if (strlen($repr)) {
            $repr .= '.';
        }

        return $repr;
    }

    public function representation()
    {
        $repr = '<a href="' . action('\App\Bases\Litteraturkritikk\Controller@show', $this->id) . '">';

        $repr .= $this->formatKritikk();
        $kritikktype = $this->preferredKritikktype($this->kritikktype);
        $verk = $this->formatVerk();

        $repr .= '</a><br>';

        $repr .= ' ' . $this->formatKritikkType($kritikktype);

        if ($kritikktype && $verk) {
            $repr .= ' ' . $this->kritikktypeSkilleord($kritikktype) . ': ';
        } elseif ($verk) {
            $repr .= 'Om: ';
        }

        $repr .= $verk;

        return $repr;
    }

    public function isNewspaper()
    {
        return Publications::isNewspaper($this->publikasjon);
    }

    public function isJournal()
    {
        return Publications::isJournal($this->publikasjon);
    }

    public function oriaSearchLink(): string
    {
        // Oria syntax is of course weird and non-standard to the point
        // that we cannot use a single http_build_query call.

        $queries = [];
        $forfatter = $this->forfattere()->first();
        if ($forfatter !== null) {
            $queries[] = http_build_query(['query' => "creator,contains,{$forfatter->etternavn}"]);
        }
        if ($this->verk_tittel) {
            $queries[] = http_build_query(['query' => "title,contains,{$this->verk_tittel}"]);
        }

        $queries[] = http_build_query([
            'tab' => 'default_tab',
            'search_scope' => 'default_scope',
            'vid' => 'UIO',
            'mode' => 'advanced',
        ]);

        return implode('&', $queries);
    }

    public function nationalLibrarySearchQuery(string $group): array
    {
        $query = [];
        $filters = [];

        if ($group == 'Kritikken') {
            $dato = $this->dato;

            if ($this->isNewspaper()) {
                $filters['mediatype'] = 'aviser';
                $filters['series'] = $this->publikasjon;
            } elseif ($this->isJournal()) {
                $filters['mediatype'] = 'tidsskrift';
                $filters['title'] = $this->publikasjon;
            }
        } else {
            $dato = $this->verk_dato;

            $filters['mediatype'] = 'bøker';

            if ($this->verk_tittel) {
                $filters['title'] = $this->verk_tittel;
            }
        }

        if ($dato && strlen($dato) <= 10) {
            $fromDate = str_replace('-', '', $dato);
            $toDate = str_replace('-', '', $dato);
            if (strlen($dato) == 4) {
                $fromDate = "{$dato}0101";
                $toDate = "{$dato}1231";
            }
            $filters['date'] = [$fromDate, $toDate];
        }

        if ($group == 'Kritikken') {
            if ($this->nummer) {
                $query[] = $this->nummer;
            }

            $kritiker = $this->kritikere()->first();
            if ($kritiker !== null && !$kritiker->pivot->pseudonym && !preg_match('/(anonym|ukjent)/', $kritiker->etternavn)) {
                $query[] = $kritiker->etternavn;
            }
            $forfatter = $this->forfattere()->first();
            if ($forfatter !== null) {
                $query[] = $forfatter->etternavn;
            }
        } else {
            $forfatter = $this->forfattere()->first();
            if ($forfatter !== null) {
                $filters['name'] = $forfatter->etternavn;
            }
        }

        $query = implode(' ', $query);

        return [
            'query' => $query,
            'filters' => $filters,
        ];
    }

}
