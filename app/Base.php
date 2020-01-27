<?php

namespace App;

use App\Http\Controllers\PageController;
use App\Schema\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * A Base is a collection of Records with its own namespace and settings.
 * Each Base has its own classes under `App\Bases\{Basename}`
 *
 * @property string id
 * @property string basepath
 * @property string title
 * @property string default_language
 * @property array languages
 * @property array name
 * @property array class_bindings
 */
class Base extends Model
{
    protected $default_class_bindings = [
        'AutocompleteService' => '\App\Services\AutocompleteService',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'array',
        'languages' => 'array',
        'class_bindings' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'basepath',
        'default_language',
        'languages',
        'name',
        'class_bindings',
    ];

    /**
     * The IDs are not auto-incrementing since we use string IDs.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get the title in the default language.
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        return Arr::get($this->name, \App::getLocale()) ?: Arr::get($this->name, $this->default_language);
    }

    /**
     * Generate the URL to a page controller action.
     *
     * @param string $page
     * @param string $action
     * @param bool $absolute
     * @return string
     */
    public function pageAction(string $page, string $action = 'show', bool $absolute = true)
    {
        return action([PageController::class, $action], [
            'page' => $this->basepath . '/' . $page,
        ], $absolute);
    }

    /**
     * Get the fully qualified name (FQN) for a class from this base.
     *
     * @param string|null $className
     * @return string
     */
    public function fqn(string $className = null)
    {
        $base = '\\App\\Bases\\' . $this->namespace;
        if (is_null($className)) {
            return $base;
        }
        if (substr($className, 0, 1) == '\\') {
            return $className;
        }
        return $base . '\\' . $className;
    }

    /**
     * Get the fully qualified classname for a class, taking class bindings into account.
     *
     * @param string $className
     * @return string
     */
    public function getClass(string $className)
    {
        $className = $this->class_bindings[$className] ?? $this->default_class_bindings[$className] ?? $className;
        return $this->fqn($className);
    }

    /**
     * Make a new instance a class identified by its short name.
     *
     * @param string $className
     * @return mixed
     */
    public function make(string $className)
    {
        $fqn = $this->getClass($className);
        return new $fqn();
    }

    /**
     * Generate the URL to a controller action using the controller for this base.
     *
     * @param string $action
     * @param string|array $parameters
     * @param bool $absolute
     * @return string
     */
    public function action($action = 'show', $parameters = [], bool $absolute = true)
    {
        if (strpos($action, '@') !== false) {
            list($controllerClass, $action) = explode('@', $action);
        } else {
            $controllerClass = 'Controller';
        }
        $action = $this->fqn($controllerClass) . '@' . $action;
        return action($action, $parameters, $absolute);
    }

    /**
     * Get a Page instance from this base by name.
     *
     * @param string $name
     * @return Page|null
     */
    public function getPage(string $name)
    {
        return Page::where([
            'slug' => $this->basepath . '/' . $name,
            'lang' => \App::getLocale(),
        ])->first();
    }

    /**
     * Get text from the 'intro' Page, or blank if it doesn't exist.
     *
     * @return string
     */
    public function getIntro()
    {
        $page = $this->getPage('intro');
        return !is_null($page) ? $page->body : null;
    }

    /**
     * Returns the schema object for this base.
     *
     * @return Schema
     */
    public function getSchema()
    {
        return $this->make('Schema');
    }

    /**
     * Get full view name from a short view name.
     *
     * @param string $name
     * @return string
     */
    public function getView(string $name)
    {
        return $this->id . '.' . $name;
    }

    /**
     * Get a given record, or null if it doesn't exist.
     *
     * @param int $id
     * @param bool $withTrashed
     * @return Record
     */
    public function getRecord($id, $withTrashed = true)
    {
        $recordClass = $this->getClass('Record');
        if ($withTrashed) {
            return $recordClass::withTrashed()->find($id);
        }
        return $recordClass::find($id);
    }

    /**
     * Create a new record instance (but do not store it).
     *
     * @return Record
     */
    public function newRecord()
    {
        $recordClass = $this->getClass('Record');
        return new $recordClass();
    }
}
