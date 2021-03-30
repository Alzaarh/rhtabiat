<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostersTable extends Migration
{
    public function up()
    {
        Schema::create('posters', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->integer('location')->index();
            $table->boolean('is_active')->default(true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('posters');
    }
}
