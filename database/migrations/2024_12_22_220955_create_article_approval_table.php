<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleApprovalTable extends Migration
{
    public function up()
    {
        Schema::create('article_approval', function (Blueprint $table) {
            $table->id('approval_id');
            $table->foreignId('article_id')->constrained('articles', 'article_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->timestamp('approval_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_approval');
    }
}
