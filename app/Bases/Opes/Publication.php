<?php

namespace App\Bases\Opes;

class Publication extends \Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opes_publications';

    /**
     * Get the record the publication belongs to.
     */
    public function record()
    {
        return $this->belongsTo(Record::class, 'opes_id');
    }

    public function getPapyriInfoLinkAttribute()
    {
        $baseUrl = 'https://papyri.info/ddbdp/';

        if (isset($this->ddbdp_pmichcitation)) {
            return $baseUrl . $this->ddbdp_pmichcitation;
        }
        if (isset($this->ddbdp_omichcitation)) {
            return $baseUrl . $this->ddbdp_omichcitation;
        }

        return null;
    }
}
