<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fileHandle = fopen(storage_path('app/city.csv'), 'r');
        while (!feof($fileHandle)) {
            $cities[] = fgetcsv($fileHandle, 0, ',');
        }
        fclose($fileHandle);
        
        $correctCities = array_filter($cities, function ($city) {
            return !preg_match('~[0-9]+~', $city[1]);
        });

        DB::table('cities')->insert(array_map(fn ($city) => [
            'name' => $city[1],
            'province_id' => $city[3],
        ], $correctCities));
    }
}
