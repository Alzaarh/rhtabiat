<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFeaturesTable extends Migration
{
    public function up()
    {
        Schema::create('product_features', function (Blueprint $table) {
            $table->id();
            $table->float('weight');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('price');
            $table->unsignedInteger('quantity')->default(0);
            $table->enum('container', ['zink', 'plastic'])->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_features');
    }
}
