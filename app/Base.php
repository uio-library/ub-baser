<?php

namespace App;

use App\Bases\Config;
use App\Record;
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
 */
class Base extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'array',
        'languages' => 'array',
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
    ];

    /**
     * The IDs are not auto-incrementing since we use string IDs.
     *
     * @var bool
     */
    public $incrementing = false;
    private $_config;
    private $_schemas = [];

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
        if (strpos($className, '\\') === 0) {
            // Absolute path
            return $className;
        }
        $base = 'App\\Bases\\' . $this->namespace;
        if (is_null($className)) {
            return '\\' . $base;
        }
        if (strpos($className, 'App\\') === false) {
            $className = $base . '\\' . $className;
        }
        return '\\' . $className;
    }

    /**
     * Get the fully qualified classname for a class, taking class bindings into account.
     *
     * @param string $className
     * @return string
     */
    public function getClass(string $className)
    {
        if ($className !== 'Config') {
            $className = $this->getConfig()->getClassBinding($className);
        }
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
     * @param string|array $action
     * @param string|array $parameters
     * @param bool $absolute
     * @return string
     */
    public function action($action = 'show', $parameters = [], bool $absolute = true)
    {
        if (is_array($action)) {
            $controllerClass = $action[0];
            $action = $action[1];
        } elseif (strpos($action, '@') !== false) {
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
        return !is_null($page) ? '<div class="lead ck-content">' . $page->rendered() . '</div>' : null;
    }

    /**
     * Returns the schema object for this base.
     *
     * @param Record|null $record
     * @return Schema
     */
    public function getSchema(Record $record = null): Schema
    {
        if (!is_null($record) && isset($record::$schema)) {
            $schemaClass = $record::$schema;
        } else {
            $recordClass = $this->getClass('Record');
            $schemaClass = $recordClass::$schema;
        }
        if (!isset($this->_schemas[$schemaClass])) {
            $this->_schemas[$schemaClass] = $this->make($schemaClass);
        }
        return $this->_schemas[$schemaClass];
    }

    /**
     * Returns the config object for this base.
     *
     * @return Config
     */
    public function getConfig()
    {
        if (is_null($this->_config)) {
            $this->_config = $this->make('Config');
        }
        return $this->_config;
    }

    /**
     * Returns a config value for this base.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->getConfig()->get($key, $default);
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
     * @param string $className
     * @return Record
     */
    public function getRecord($id, $withTrashed = true, $className = 'Record')
    {
        $recordClass = $this->getClass($className);
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
    public function newRecord($model = 'Record')
    {
        $recordClass = $this->getClass($model);
        return new $recordClass();
    }

    /**
     * Returns true if the base use soft deletes.
     *
     * @return bool
     */
    public function usesSoftDeletes()
    {
        return boolval($this->config('softDeletes', true));
    }
}
