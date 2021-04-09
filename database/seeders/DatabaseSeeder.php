<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            ProductItemSeeder::class,
            ArticleCategorySeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
        ]);
        Article::factory(50)->hasComments(10)->create();
        Order::factory()
            ->count(100)
            ->for(Address::factory())
            ->create();
        Banner::factory(5)->create();
        Banner::factory()->create(['is_active' => true]);

        //     Poster::factory()->create([
        //         'location' => Poster::LOCATIONS['dashboard'],
        //     ]);

        //     Category::factory(3)->hasChildren(2)->create();
        //     Category::factory(4)->create();

        //     Product::factory(5)->hasFeatures(1)->hasComments(3)->create();
        //     Product::factory(3)->hasFeatures(6)->create();
        //     Product::factory(3)
        //         ->hasFeatures(6, ['container' => 'zink'])
        //         ->hasFeatures(6, ['container' => 'plastic'])
        //         ->create();

        //     ArticleCategory::factory(5)->hasArticles(10)->create();

        Message::factory(30)->create();

        User::factory(10)->hasAddresses(2)->hasDetail(1)->create();
        // ->each(function ($user) {
        //         $products = collect();
        //         $cart = $user->cart()->save(new Cart());
        //         ProductFeature::inRandomOrder()
        //             ->take(3)
        //             ->get()
        //             ->each(function ($product) use ($products) {
        //                 $products->put($product->id, [
        //                     'quantity' => rand(1, 3),
        //                 ]);
        //             });
        //         $cart->products()->attach($products->all());
        //     });

        //     Order::factory(10)->create()->each(function ($order) {
        //         $products = collect();
        //         ProductFeature::inRandomOrder()
        //             ->take(2)
        //             ->get()
        //             ->each(function ($product) use ($products) {
        //                 $products->put($product->id, [
        //                     'price' => $product->price,
        //                     'quantity' => rand(1, 5),
        //                     'weight' => $product->weight,
        //                 ]);
        //             });
        //         if ($order->total_price < 200000) {
        //             $order->delivery_cost = $order->total_weight * 5000;
        //             $order->save();
        //         }
        //         $order->products()->attach($products->all());
        //     });
        // }
    }
}
