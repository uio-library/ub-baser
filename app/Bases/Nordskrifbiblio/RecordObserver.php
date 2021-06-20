<?php

namespace App\Bases\Nordskrifbiblio;

class RecordObserver
{
    protected function refreshView()
    {
        // Refresh view
        \DB::unprepared('REFRESH MATERIALIZED VIEW CONCURRENTLY nordskrifbiblio_view');
    }

    /**
     * Handle the app bases nordskrifbiblio record "created" event.
     *
     * @param Record $record
     * @return void
     */
    public function created(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases nordskrifbiblio record "updated" event.
     *
     * @param Record $record
     * @return void
     */
    public function updated(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases nordskrifbiblio record "deleted" event.
     *
     * @param Record $record
     * @return void
     */
    public function deleted(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases nordskrifbiblio record "restored" event.
     *
     * @param Record $record
     * @return void
     */
    public function restored(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases nordskrifbiblio record "force deleted" event.
     *
     * @param Record $record
     * @return void
     */
    public function forceDeleted(Record $record)
    {
        $this->refreshView();
    }
}
