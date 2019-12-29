<?php

namespace App\Bases\Litteraturkritikk;

class RecordObserver
{
    protected function refreshView()
    {
        // Refresh view
        \DB::unprepared('REFRESH MATERIALIZED VIEW CONCURRENTLY litteraturkritikk_records_search');
    }

    /**
     * Handle the app bases litteraturkritikk record "created" event.
     *
     * @param Record $record
     * @return void
     */
    public function created(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases litteraturkritikk record "updated" event.
     *
     * @param Record $record
     * @return void
     */
    public function updated(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases litteraturkritikk record "deleted" event.
     *
     * @param Record $record
     * @return void
     */
    public function deleted(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases litteraturkritikk record "restored" event.
     *
     * @param Record $record
     * @return void
     */
    public function restored(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases litteraturkritikk record "force deleted" event.
     *
     * @param Record $record
     * @return void
     */
    public function forceDeleted(Record $record)
    {
        $this->refreshView();
    }
}
