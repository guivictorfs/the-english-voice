<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoteHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('vote_history', function (Blueprint $table) {
            $table->id('vote_history_id');
            $table->foreignId('article_id')->constrained('articles', 'article_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->tinyInteger('rating')->check('rating >= 1 and rating <= 5');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vote_history');
    }
}