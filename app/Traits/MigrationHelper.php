<?php

namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;

trait MigrationHelper
{
    protected function addCommonFields(Blueprint $table)
    {
        $table->increments('id');
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
}
