<?php

use Illuminate\Database\Migrations\Migration;

class BiblioManuelAddCatalan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('bases')
            ->where(['id' => 'bibliomanuel'])
            ->update([
                'languages' => json_encode(['nb', 'es', 'ca', 'en']),
                'name' => json_encode([
                    'es' => 'Bibliografía sobre la obra de Manuel Vázquez Montalbán',
                    'ca' => 'Bibliografia sobre l\'obra de Manuel Vázquez Montalbán',
                    'en' => 'Bibliography on the works of Manuel Vázquez Montalbán',
                    'nb' => 'Bibliografi over Manuel Vázquez Montalbáns utgivelser',
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
        \DB::table('bases')
            ->where(['id' => 'bibliomanuel'])
            ->update([
                'languages' => json_encode(['nb', 'es', 'en']),
                'name' => json_encode([
                    'es' => 'Bibliografía sobre la obra de Manuel Vázquez Montalbán',
                    'en' => 'Bibliography on the works of Manuel Vázquez Montalbán',
                    'nb' => 'Bibliografi over Manuel Vázquez Montalbáns utgivelser',
                ]),
            ]);
    }
}
