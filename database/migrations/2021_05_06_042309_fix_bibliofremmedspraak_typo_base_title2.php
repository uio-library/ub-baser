<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixBibliofremmedspraakTypoBaseTitle2 extends Migration
{
 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \DB::table('bases')
            ->where(['id' => 'bibliofremmedspraak'])
            ->update([
                'name' => json_encode([
                    'en' => 'Foreign Languages and Foreign Language Teaching in Norway. French, Spanish and German',
                    'nb' => 'Fremmedspråk og fremmedspråkundervisning i Norge. Fransk, spansk og tysk',
                ]),
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
