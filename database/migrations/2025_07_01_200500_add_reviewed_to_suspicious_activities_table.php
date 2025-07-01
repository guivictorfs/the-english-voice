<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('suspicious_activities', function (Blueprint $table) {
            $table->boolean('reviewed')->default(false)->after('description');
        });
    }

    public function down()
    {
        Schema::table('suspicious_activities', function (Blueprint $table) {
            $table->dropColumn('reviewed');
        });
    }
};
