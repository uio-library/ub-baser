<?php

namespace App\Bases\Litteraturkritikk;

class PersonObserver
{
    protected function refreshView()
    {
        // Refresh view
        \DB::unprepared('REFRESH MATERIALIZED VIEW CONCURRENTLY litteraturkritikk_records_search');
    }

    /**
     * Handle the person "created" event.
     *
     * @param  Person  $person
     * @return void
     */
    public function created(Person $person)
    {
        //
    }

    /**
     * Handle the person "updated" event.
     *
     * @param  Person  $person
     * @return void
     */
    public function updated(Person $person)
    {
        $this->refreshView();
    }

    /**
     * Handle the person "deleted" event.
     *
     * @param  Person  $person
     * @return void
     */
    public function deleted(Person $person)
    {
        $this->refreshView();
    }

    /**
     * Handle the person "restored" event.
     *
     * @param  Person  $person
     * @return void
     */
    public function restored(Person $person)
    {
        //
    }

    /**
     * Handle the person "force deleted" event.
     *
     * @param  Person  $person
     * @return void
     */
    public function forceDeleted(Person $person)
    {
        //
    }
}
