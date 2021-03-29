<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_desc', 2000);
            $table->text('desc');
            $table->string('icon')->nullable();
            $table->unsignedTinyInteger('off')->nullable();
            $table->foreignId('category_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
