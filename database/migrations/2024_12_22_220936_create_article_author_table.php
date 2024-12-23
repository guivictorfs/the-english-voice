<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleAuthorTable extends Migration
{
    public function up()
    {
        Schema::create('article_author', function (Blueprint $table) {
            $table->id('article_author_id');
            $table->foreignId('article_id')->constrained('articles', 'article_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('author_type', ['Principal', 'Secundário']);
            $table->timestamps();
            $table->unique(['article_id', 'user_id', 'author_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_author');
    }
}
