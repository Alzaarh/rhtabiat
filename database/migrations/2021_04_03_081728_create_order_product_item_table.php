<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_item', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained();
            $table->foreignId('product_item_id')->constrained();
            $table->integer('price');
            $table->integer('off');
            $table->integer('quantity');
            $table->integer('weight');
            $table->primary(['order_id', 'product_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product_item');
    }
}
