<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPageSlugContraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {

            $conn = Schema::getConnection();
            $dbSchemaManager = $conn->getDoctrineSchemaManager();
            $doctrineTable = $dbSchemaManager->listTableDetails('pages');
            if ($doctrineTable->hasIndex('pages_slug_unique')) {
                $table->dropUnique('pages_slug_unique');
            }

            $table->unique(['slug', 'lang']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropUnique('pages_slug_lang_unique');
            // We can't add add the original index back here
            // since we may now have non-unique slugs.
        });
    }
}
