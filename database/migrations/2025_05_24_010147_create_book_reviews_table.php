<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('book_title');
            $table->string('author');
            $table->text('review');
            $table->unsignedInteger('rating')->between(1, 5);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_reviews');
    }
};