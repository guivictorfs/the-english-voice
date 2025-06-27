<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('avaliacoes', function (Blueprint $table) {
    $table->id();
    $table->integer('user_id');
    $table->integer('artigo_id');
    $table->tinyInteger('nota');
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('artigo_id')->references('article_id')->on('article')->onDelete('cascade');
});
    }

    public function down() {
        Schema::dropIfExists('avaliacoes');
    }
};
