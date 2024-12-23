<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('article_history', function (Blueprint $table) {
            $table->id('article_history_id');
            $table->foreignId('article_id')->constrained('articles', 'article_id')->onDelete('cascade');
            $table->foreignId('changed_by')->constrained('users', 'user_id')->onDelete('cascade'); // Alterado para 'user_id'
            $table->enum('change_type', ['Edição', 'Exclusão']);
            $table->text('change_description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_history');
    }
}
