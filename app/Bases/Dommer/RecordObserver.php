<?php

namespace App\Bases\Dommer;

class RecordObserver
{
    protected function refreshView()
    {
        // Refresh view
        \DB::unprepared('REFRESH MATERIALIZED VIEW CONCURRENTLY dommer_view');
    }

    /**
     * Handle the app bases dommer record "created" event.
     *
     * @param Record $record
     * @return void
     */
    public function created(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases dommer record "updated" event.
     *
     * @param Record $record
     * @return void
     */
    public function updated(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases dommer record "deleted" event.
     *
     * @param Record $record
     * @return void
     */
    public function deleted(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases dommer record "restored" event.
     *
     * @param Record $record
     * @return void
     */
    public function restored(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases dommer record "force deleted" event.
     *
     * @param Record $record
     * @return void
     */
    public function forceDeleted(Record $record)
    {
        $this->refreshView();
    }
}
