<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle', 1000)->nullable();
            $table->string('image');
            $table->string('link_text')->nullable();
            $table->string('link_dest')->nullable();
            $table->boolean('is_active')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
