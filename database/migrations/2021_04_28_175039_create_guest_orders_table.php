<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('guest_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('name');
            $table->string('company')->nullable();
            $table->char('mobile', 11);
            $table->char('phone', 11)->nullable();
            $table->string('province_id');
            $table->string('city_id');
            $table->char('zipcode', 10);
            $table->string('address', 1000);
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_orders');
    }
}
