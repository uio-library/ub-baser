<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        //DB::enableQueryLog();
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->collation('nb_NO.utf8');
            $table->string('email')->unique();
            $table->string('password', 60)->nullable();
            $table->string('saml_id')->nullable()->unique();
            $table->string('saml_session')->nullable();
            $table->jsonb('rights')->default('[]');
            $table->dateTime('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
        //dd(DB::getQueryLog());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
