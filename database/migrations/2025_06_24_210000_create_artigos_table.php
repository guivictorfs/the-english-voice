<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('artigos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('pdf_path');
            $table->text('conteudo')->nullable();
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('artigos');
    }
};
