<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContentToArticleTable extends Migration
{
    public function up()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->longText('content')->nullable()->after('title');
        });
    }

    public function down()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->dropColumn('content');
        });
    }
}
