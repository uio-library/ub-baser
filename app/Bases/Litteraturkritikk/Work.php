<?php

namespace App\Bases\Litteraturkritikk;

use Illuminate\Database\Eloquent\SoftDeletes;

class Work extends \App\Record
{
    use SoftDeletes;

    /**
     * Short name used to determine routes etc.
     *
     * @var string
     */
    public static $shortName = 'work';

    /**
     * Schema class used with this model.
     *
     * @var string
     */
    public static $schema = WorkSchema::class;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'litteraturkritikk_works';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'verk_tittel',
        'verk_originaltittel',
        'verk_originaltittel_transkribert',
        'verk_dato',
        'verk_sjanger',
        'verk_spraak',
        'verk_originalspraak',
        'verk_kommentar',
        'verk_utgivelsessted',
        'verk_forfatter_mfl',
        'verk_fulltekst_url',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['string_representation'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'verk_spraak' => 'array',
        'verk_originalspraak' => 'array',
    ];

    /**
     * The records that discusses this work.
     */
    public function discussedIn()
    {
        return $this->belongsToMany(Record::class, 'litteraturkritikk_subject_work');
    }

    /**
     * Creators of this work.
     */
    public function forfattere()
    {
        return $this->morphToMany(
            Person::class,
            'contribution',
            'litteraturkritikk_person_contributions'
        )
            ->withPivot('person_role', 'kommentar', 'pseudonym', 'position')
            ->using(PersonContribution::class)
            ->orderBy('litteraturkritikk_person_contributions.position', 'asc');
    }

    public function getStringRepresentationAttribute()
    {
        $out = $this->verk_tittel;
        if ($this->verk_dato) {
            $out .= ' (' . $this->verk_dato . ')';
        }
        // osv..

        return $out;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        // @TODO
        return $this->verk_tittel;
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

        $dato = $this->verk_dato;

        $filters['mediatype'] = 'bøker';

        if ($this->verk_tittel) {
            $filters['title'] = $this->verk_tittel;
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

        $forfatter = $this->forfattere()->first();
        if ($forfatter !== null) {
            $filters['name'] = $forfatter->etternavn;
        }

        $query = implode(' ', $query);

        return [
            'query' => $query,
            'filters' => $filters,
        ];
    }
}
