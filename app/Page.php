<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

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
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Get the base the page belongs to.
     */
    public function base()
    {
        return $this->belongsTo(Base::class);
    }

    /**
     * Get page locks.
     */
    public function locks()
    {
        return $this->morphMany(Lock::class, 'lockable');
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

    public function rendered()
    {
        $body = $this->body;

        $body = preg_replace_callback(
            '|<h([0-9])>(.*?)</h[0-9]>|',
            function($matches) {
                $level = $matches[1];
                $title = $matches[2];
                $id = preg_replace('/ /', '_', $title);
                $id = preg_replace('/[^\wæøåÆØÅ_-]/', '', $id);
                $id = mb_strtolower($id);

                return "<h$level><a id=\"$id\">$title</a></h$level>";
            },
            $body
        );

        return '<div class="ck-content">' . $body . '</div>';
    }
}
