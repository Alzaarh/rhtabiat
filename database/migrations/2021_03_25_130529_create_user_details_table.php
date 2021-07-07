<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->string('email')->unique();
            $table->foreignId('user_id')->constrained();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
