<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleKeywordTable extends Migration
{
    public function up()
    {
        Schema::create('article_keyword', function (Blueprint $table) {
            $table->id('article_keywords_id');
            $table->foreignId('article_id')->constrained('articles', 'article_id')->onDelete('cascade');
            $table->foreignId('keyword_id')->constrained('keywords', 'keyword_id')->onDelete('cascade');
            $table->unique(['article_id', 'keyword_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_keyword');
    }
}
