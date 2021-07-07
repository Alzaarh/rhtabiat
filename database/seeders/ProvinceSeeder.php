<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fileHandle = fopen(storage_path('app/province.csv'), 'r');
        while (!feof($fileHandle)) {
            $provinces[] = fgetcsv($fileHandle, 0, ',');
        }
        fclose($fileHandle);
        
        DB::table('provinces')->insert(array_map(fn ($province) => [
            'name' => $province[1],
        ], $provinces));
    }
}
