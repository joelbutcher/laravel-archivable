<?php

namespace LaravelArchivable;

use Exception;
use LaravelArchivable\Scopes\ArchivableScope;

/**
 * @method static static|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder withArchived()
 * @method static static|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder onlyArchived()
 * @method static static|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder withoutArchived()
 */
trait Archivable
{
    /**
     * Indicates if the model should use archives.
     *
     * @var bool
     */
    public $archives = true;

    /**
     * Boot the archiving trait for a model.
     *
     * @return void
     */
    public static function bootArchivable()
    {
        static::addGlobalScope(new ArchivableScope);
    }

    /**
     * Initialize the soft deleting trait for an instance.
     *
     * @return void
     */
    public function initializeArchivable()
    {
        if (! isset($this->casts[$this->getArchivedAtColumn()])) {
            $this->casts[$this->getArchivedAtColumn()] = 'datetime';
        }

        $this->addObservableEvents([
            'archiving',
            'archived',
            'unArchiving',
            'unArchived'
        ]);
    }

    /**
     * Archive the model.
     *
     * @return bool|null
     *
     * @throws Exception
     */
    public function archive()
    {
        $this->mergeAttributesFromClassCasts();

        if (is_null($this->getKeyName())) {
            throw new Exception('No primary key defined on model.');
        }

        // If the model doesn't exist, there is nothing to archive.
        if (! $this->exists) {
            return;
        }

        // If the archiving event doesn't return false, we'll continue
        // with the operation.
        if ($this->fireModelEvent('archiving') === false) {
            return false;
        }

        // Update the timestamps for each of the models owners. Breaking any caching
        // on the parents
        $this->touchOwners();

        $this->runArchive();

        // Fire archived event to allow hooking into the post-archive operations.
        $this->fireModelEvent('archived', false);

        // Return true as the archive is presumably successful.
        return true;
    }

    /**
     * Perform the actual archive query on this model instance.
     *
     * @return void
     */
    public function runArchive()
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());

        $time = $this->freshTimestamp();

        $columns = [$this->getArchivedAtColumn() => $this->fromDateTime($time)];

        $this->{$this->getArchivedAtColumn()} = $time;

        if ($this->usesTimestamps() && ! is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;

            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);

        $this->syncOriginalAttributes(array_keys($columns));
    }

    public function unArchive()
    {
        // If the archiving event return false, we will exit the operation.
        // Otherwise, we will clear the archived at timestamp and continue
        // with the operation
        if ($this->fireModelEvent('unArchiving') === false) {
            return false;
        }

        $this->{$this->getArchivedAtColumn()} = null;

        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('unArchived', false);

        return $result;
    }

    /**
     * Determine if the model instance has been archived.
     *
     * @return bool
     */
    public function isArchived()
    {
        return ! is_null($this->{$this->getArchivedAtColumn()});
    }

    /**
     * Register a "archiving" model event callback with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function archiving($callback)
    {
        static::registerModelEvent('archiving', $callback);
    }

    /**
     * Register a "archived" model event callback with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function archived($callback)
    {
        static::registerModelEvent('archived', $callback);
    }

    /**
     * Register a "un-archiving" model event callback with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function unArchiving($callback)
    {
        static::registerModelEvent('unArchiving', $callback);
    }

    /**
     * Register a "un-archived" model event callback with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function unArchived($callback)
    {
        static::registerModelEvent('unArchived', $callback);
    }

    /**
     * Get the name of the "created at" column.
     *
     * @return string
     */
    public function getArchivedAtColumn()
    {
        return defined('static::ARCHIVED_AT') ? static::ARCHIVED_AT : 'archived_at';
    }

    /**
     * Get the fully qualified "created at" column.
     *
     * @return string
     */
    public function getQualifiedArchivedAtColumn()
    {
        return $this->qualifyColumn($this->getArchivedAtColumn());
    }
}
