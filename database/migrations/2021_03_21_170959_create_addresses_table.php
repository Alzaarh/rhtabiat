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
            $table->string('receiver_name');
            $table->string('receiver_company')->nullable();
            $table->char('receiver_mobile', 11);
            $table->char('receiver_phone', 11)->nullable();
            $table->string('state');
            $table->string('city');
            $table->char('zipcode', 10);
            $table->string('address', 1000);
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
