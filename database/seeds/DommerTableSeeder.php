<?php

use App\Bases\Dommer\DommerKilde;
use App\Bases\Dommer\Record;
use App\Bases\Dommer\RecordView;
use Illuminate\Database\Seeder;

class DommerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(DommerKilde::class, 4)->create()->each(function ($kilde) {
            $kilde->poster()->createMany(
                factory(Record::class, 8)->make()->toArray()
            );
        });
        RecordView::refreshView();
    }
}
