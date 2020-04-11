<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BibliomanuelLetrasDataTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bibliomanuel', function (Blueprint $table) {
            $table->text('forfatter')->nullable()->change();
            $table->text('tittel')->nullable()->change();
            $table->text('antologi')->nullable()->change();
            $table->text('boktittel')->nullable()->change();
            $table->text('redaktorer')->nullable()->change();
            $table->text('utgivelsessted')->nullable()->change();
            $table->text('avis')->nullable()->change();
            $table->text('tidsskriftstittel')->nullable()->change();
            $table->text('nettsted')->nullable()->change();
            $table->text('utgiver')->nullable()->change();
            $table->text('aar')->nullable()->change();
            $table->text('dato')->nullable()->change();
            $table->text('type')->nullable()->change();
            $table->text('bind')->nullable()->change();
            $table->text('hefte')->nullable()->change();
            $table->text('nummer')->nullable()->change();
            $table->text('sidetall')->nullable()->change();
            $table->text('isbn')->nullable()->change();
            $table->text('issn')->nullable()->change();
            $table->text('eissn')->nullable()->change();
            $table->text('url')->nullable()->change();
        });

        Schema::table('letras', function (Blueprint $table) {
            $table->text('forfatter')->nullable()->change();
            $table->text('land')->nullable()->change();
            $table->text('tittel')->nullable()->change();
            $table->text('utgivelsesaar')->nullable()->change();
            $table->text('sjanger')->nullable()->change();
            $table->text('oversetter')->nullable()->change();
            $table->text('tittel2')->nullable()->change();
            $table->text('utgivelsessted')->nullable()->change();
            $table->text('utgivelsesaar2')->nullable()->change();
            $table->text('forlag')->nullable()->change();
            $table->text('foretterord')->nullable()->change();
            $table->text('spraak')->nullable()->change();
        });
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
