<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Message;
use App\Models\Order;
use App\Models\ProductItem;
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
            BannerSeeder::class,
            AdminSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            ProductItemSeeder::class,
            ArticleCategorySeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
        ]);
        Article::factory(50)->hasComments(10)->create();
        $orders = Order::factory()
            ->count(100)
            ->for(Address::factory())
            ->create();

        $orders->each(function ($order) {
            $orderProducts = [];
            ProductItem::inRandomOrder()
                ->take(2)
                ->get()
                ->each(function ($item) use (&$orderProducts) {
                    $orderProducts[$item->id] = [
                    'price' => $item->price,
                    'quantity' => rand(1, 5),
                    'weight' => $item->weight,
                    'off' => rand(5, 20),
                    'product_id' => $item->product->id,
                ];
            });
            $order->products()->attach($orderProducts);
        });
        Message::factory(30)->create();

        User::factory(10)->hasAddresses(2)->hasDetail(1)->create();
        
    }
}
