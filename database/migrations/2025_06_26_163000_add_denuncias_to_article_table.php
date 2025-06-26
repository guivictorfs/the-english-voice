<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->unsignedInteger('denuncias')->default(0)->after('average_rating');
        });
    }
    public function down()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->dropColumn('denuncias');
        });
    }
};
