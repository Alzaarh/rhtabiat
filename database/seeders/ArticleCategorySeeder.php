<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('article_categories')->insert([
            'name' => 'متفرقه',
            'slug' => 'متفرقه',
        ]);

        if (config('app.env') === 'local') {
            DB::table('article_categories')->insert([
                [
                    'name' => 'گیاهی',
                    'slug' => 'گیاهی',
                ],
                [
                    'name' => 'طبیعی',
                    'slug' => 'طبیعی',
                ],
            ]);
        }
    }
}
