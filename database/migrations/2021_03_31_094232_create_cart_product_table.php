<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartProductTable extends Migration
{
    public function up()
    {
        Schema::create('cart_product', function (Blueprint $table) {
            $table->foreignId('cart_id')->constrained();
            // $table->foreignId('product_feature_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            // $table->primary(['cart_id', 'product_feature_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_product');
    }
}
