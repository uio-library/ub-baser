<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CheckDatabase extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Validate encoding
        $desiredEncoding = 'UTF8';
        $res = \DB::select('SHOW server_encoding');
        $encoding = $res[0]->server_encoding;

        if ($encoding != $desiredEncoding) {
            throw new \RuntimeException(
                "The database encoding $encoding differs from the expected encoding $desiredEncoding."
            );
        }

        // Validate collation support
        $desiredCollation = 'nb_NO.utf8';
        $res = \DB::select('SELECT * FROM pg_collation where collname = ?', [$desiredCollation]);
        if (!count($res)) {
            throw new \RuntimeException(
                "The database does not have the $desiredCollation collation. " .
                "Please create it before migrating the database. " .
                "See instructions in the readme.md file."
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
