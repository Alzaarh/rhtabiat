<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('receiver');
            $table->string('company');
            $table->char('mobile', 11);
            $table->char('phone', 11);
            $table->string('state');
            $table->string('city');
            $table->char('zipcode', 10);
            $table->string('address', 1000);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
