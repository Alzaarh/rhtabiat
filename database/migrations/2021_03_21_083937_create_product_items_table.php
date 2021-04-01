<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductItemsTable extends Migration
{
    public function up()
    {
        Schema::create('product_items', function (Blueprint $table) {
            $table->id();
            $table->float('weight');
            $table->integer('price');
            $table->integer('quantity')->default(0);
            $table->integer('container')->nullable();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_items');
    }
}
