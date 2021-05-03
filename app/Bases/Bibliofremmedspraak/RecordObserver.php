<?php

namespace App\Bases\Bibliofremmedspraak;

class RecordObserver
{
    protected function refreshView()
    {
        // Refresh view
        \DB::unprepared('REFRESH MATERIALIZED VIEW CONCURRENTLY bibliofremmedspraak_view');
    }

    /**
     * Handle the app bases bibliofremmedspraak record "created" event.
     *
     * @param Record $record
     * @return void
     */
    public function created(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases bibliofremmedspraak record "updated" event.
     *
     * @param Record $record
     * @return void
     */
    public function updated(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases bibliofremmedspraak record "deleted" event.
     *
     * @param Record $record
     * @return void
     */
    public function deleted(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases bibliofremmedspraak record "restored" event.
     *
     * @param Record $record
     * @return void
     */
    public function restored(Record $record)
    {
        $this->refreshView();
    }

    /**
     * Handle the app bases bibliofremmedspraak record "force deleted" event.
     *
     * @param Record $record
     * @return void
     */
    public function forceDeleted(Record $record)
    {
        $this->refreshView();
    }
}
