<?php

namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;

trait MigrationHelper
{
    protected function addCommonFields(Blueprint $table, $includeId = true)
    {
        if ($includeId) {
            $table->increments('id');
        }
        $table->timestamps();
        $table->softDeletes();

        $table->integer('created_by')->unsigned()->nullable();
        $table->integer('updated_by')->unsigned()->nullable();

        $table->foreign('created_by')
            ->references('id')
            ->on('users')
            ->onDelete('set null');

        $table->foreign('updated_by')
            ->references('id')
            ->on('users')
            ->onDelete('set null');
    }

    protected function runSql($filename)
    {
        $sql = file_get_contents($filename);
        \DB::unprepared($sql);
    }

    protected function createView($view, $version = 1)
    {
        $filename = database_path('migrations/views/' . $view . '/v' . $version . '.sql');
        $this->runSql($filename);
    }

    protected function dropView($name)
    {
        \DB::unprepared('DROP VIEW ' . $name);
    }

    protected function dropMaterializedView($name)
    {
        \DB::unprepared('DROP MATERIALIZED VIEW ' . $name);
    }
}
