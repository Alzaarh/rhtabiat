<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodeGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('discount_code_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('max')->nullable();
            $table->integer('min')->nullable();
            $table->integer('percent')->nullable();
            $table->integer('value')->nullable();
            $table->date('expires_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_code_groups');
    }
}

