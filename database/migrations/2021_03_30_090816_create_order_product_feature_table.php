<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductFeatureTable extends Migration
{
    public function up()
    {
        Schema::create('order_product_feature', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained();
            $table->foreignId('product_feature_id')->constrained();
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('weight');
            $table->primary(['order_id', 'product_feature_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_product_feature');
    }
}
