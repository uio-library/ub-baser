<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'layout',
        'permission',
        'title',
        'body',
        'updated_by',
    ];

    /**
     * Get the user that last updated the page.
     */
    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }

    /**
     * Get the base the page belongs to.
     */
    public function base()
    {
        return $this->belongsTo('App\Base');
    }

    /**
     * Get the route key for the model.
     * Ref: https://laravel.com/docs/master/routing
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
