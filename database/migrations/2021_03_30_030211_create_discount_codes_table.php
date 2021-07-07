<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodesTable extends Migration
{
    public function up()
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->index();
            $table->boolean('is_suspended')->default(false);
            $table->foreignId('discount_code_group_id')->constrained()->onDelete('cascade');
            $table->timestamp('used_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_codes');
    }
}
