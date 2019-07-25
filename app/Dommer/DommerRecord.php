<?php

namespace App\Dommer;

use App\Record;

class DommerRecord extends Record
{
    public static $prefix = 'dommer';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dommer';


    public static function getSchema(): array
    {
        $schema = [
            'fields' => [

                // ID
                [
                    'key' => 'id',
                    'type' => 'incrementing',
                    'display' => false,
                    'edit' => false,
                    'search' => false,
                ],

                [
                    'key' => 'navn',
                    'type' => 'simple',
                ],
                [
                    'key' => 'kilde_navn',
                    'type' => 'simple',
                ],
                [
                    'key' => 'aar',
                    'type' => 'simple',
                ],
                [
                    'key' => 'side',
                    'type' => 'simple',
                ],
            ],

            'groups' => [],
        ];

        static::initSchema($schema, []);

        return $schema;
    }

    /**
     * Get the periodical this record belongs to.
     */
    public function kilde()
    {
        return $this->belongsTo('App\Dommer\DommerKilde');
    }

    public function link()
    {
        return action('DommerController@show', $this->id);
    }
}
