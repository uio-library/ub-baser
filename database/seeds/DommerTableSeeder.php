<?php

use App\Dommer\DommerKilde;
use App\Dommer\DommerRecord;
use App\Dommer\DommerRecordView;
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
                factory(DommerRecord::class, 8)->make()->toArray()
            );
        });
        DommerRecordView::refreshView();
    }
}
