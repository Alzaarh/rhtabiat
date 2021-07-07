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
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('name');
            $table->string('company')->nullable();
            $table->char('mobile', 11);
            $table->char('phone', 11)->nullable();
            $table->string('province_id');
            $table->string('city_id');
            $table->char('zipcode', 10);
            $table->string('address', 1000);
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
