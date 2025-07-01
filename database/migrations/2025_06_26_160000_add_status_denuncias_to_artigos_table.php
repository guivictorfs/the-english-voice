<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('artigos', function (Blueprint $table) {
            $table->string('status')->default('aprovado')->after('conteudo');
            $table->unsignedInteger('denuncias')->default(0)->after('status');
        });
    }
    public function down()
    {
        Schema::table('artigos', function (Blueprint $table) {
            $table->dropColumn(['status', 'denuncias']);
        });
    }
};
