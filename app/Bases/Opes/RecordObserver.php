<?php

namespace App\Bases\Opes;

class RecordObserver
{
    protected function refreshView()
    {
        // Refresh view
        \DB::unprepared('REFRESH MATERIALIZED VIEW CONCURRENTLY opes_view');
    }

    /**
     * Handle the app bases opes record "created" event.
     *
     * @param Record $record
     * @return void
     */
    public function created(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases opes record "updated" event.
     *
     * @param Record $record
     * @return void
     */
    public function updated(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases opes record "deleted" event.
     *
     * @param Record $record
     * @return void
     */
    public function deleted(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases opes record "restored" event.
     *
     * @param Record $record
     * @return void
     */
    public function restored(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases opes record "force deleted" event.
     *
     * @param Record $record
     * @return void
     */
    public function forceDeleted(Record $record)
    {
        $this->refreshView();
    }
}
