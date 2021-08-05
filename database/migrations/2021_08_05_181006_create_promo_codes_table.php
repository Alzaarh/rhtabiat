<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->boolean('user_only');
            $table->boolean('one_per_user');
            $table->integer('off_percent')->nullable();
            $table->integer('off_value')->nullable();
            $table->integer('max')->nullable();
            $table->integer('min')->nullable();
            $table->boolean('infinite');
            $table->integer('count')->nullable();
            $table->date('valid_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_codes');
    }
}
