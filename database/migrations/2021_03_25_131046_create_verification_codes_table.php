<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationCodesTable extends Migration
{
    public function up()
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->char('code', 5);
            $table->char('phone', 11)->unique();
            $table->unsignedTinyInteger('usage');
            $table->timestamp('updated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('verification_codes');
    }
}
