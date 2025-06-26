<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('article_keyword', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('keyword_id');
            $table->timestamps();

            $table->foreign('article_id')->references('article_id')->on('article')->onDelete('cascade');
            $table->foreign('keyword_id')->references('keyword_id')->on('keyword')->onDelete('cascade');
            $table->unique(['article_id', 'keyword_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_keyword');
    }
};
