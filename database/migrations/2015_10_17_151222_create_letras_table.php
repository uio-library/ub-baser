<?php

use App\Traits\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLetrasTable extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('letras');
        Schema::create('letras', function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('forfatter')->nullable();
            $table->string('land')->nullable();
            $table->string('tittel')->nullable();
            $table->string('utgivelsesaar')->nullable();
            $table->string('sjanger')->nullable();
            $table->string('oversetter')->nullable();
            $table->string('tittel2')->nullable();
            $table->string('utgivelsessted')->nullable();
            $table->string('utgivelsesaar2')->nullable();
            $table->string('forlag')->nullable();
            $table->string('foretterord')->nullable();
            $table->string('spraak')->nullable();

            $table->index('forfatter');
            $table->index('tittel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('letras');
    }
}
