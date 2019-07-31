<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug')->unique();
            $table->string('layout');
            $table->string('permission');

            $table->string('title')->default('');
            $table->longText('body')->default('');

            $table->integer('updated_by')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('updated_by')
                ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }
}
