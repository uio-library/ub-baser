<?php

namespace App;

class DommerRecord extends Record
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dommer';

    /**
     * List of table columns with their labels for use in UI.
     *
     * @var array
     */
    public static $columns = [
        ['field' => 'navn', 'label' => 'Navn'],
        ['field' => 'aar', 'label' => 'År'],
        ['field' => 'kilde', 'label' => 'Kilde'],
        ['field' => 'side', 'label' => 'Side'],
    ];

}
