<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleRequestTable extends Migration
{
    public function up()
    {
        Schema::create('article_request', function (Blueprint $table) {
            $table->id('article_requests_id');
            $table->foreignId('article_id')->constrained('articles', 'article_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('request_type', ['Edição', 'Exclusão']);
            $table->text('reason');
            $table->enum('status', ['Pendente', 'Aprovado', 'Rejeitado'])->default('Pendente');
            $table->timestamps(); 
            $table->softDeletes(); 
        });
    }
    public function down()
    {
        Schema::dropIfExists('article_request');
    }
}
