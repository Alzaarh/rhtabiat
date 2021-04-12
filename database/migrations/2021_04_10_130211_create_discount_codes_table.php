<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('max')->nullable();
            $table->integer('min')->nullable();
            $table->integer('percent')->nullable();
            $table->integer('value')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamp('created_at')->useCurrent();
            $table->date('expires_at');
            $table->timestamp('used_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_codes');
    }
}
