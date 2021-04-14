<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fileHandle = fopen(storage_path('app/articles.csv'), 'r');
        while (!feof($fileHandle)) {
            $articles[] = fgetcsv($fileHandle, 0, ',');
        }
        fclose($fileHandle);

        $toBeCreatedArticles = [];
        foreach ($articles as $article) {
            if (
                is_array($article) && 
                $article[20] === 'post' && 
                !array_search($article[5], array_column($toBeCreatedArticles, 'slug'))) {
                array_push($toBeCreatedArticles, [
                    'title' => $article[5],
                    'body' => $article[4],
                    'admin_id' => 1,
                    'article_category_id' => 1,
                    'slug' => $article[5],
                    'is_verified' => true,
                ]);
            }
        }
        
        DB::table('articles')->insert($toBeCreatedArticles);
    }
}
