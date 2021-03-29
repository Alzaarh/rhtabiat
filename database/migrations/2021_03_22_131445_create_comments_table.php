<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('author_email');
            $table->string('body', 2000);
            $table->unsignedTinyInteger('score');
            $table->unsignedTinyInteger('status')->default(1);
            $table->morphs('commentable');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
