<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('system_audit_log', function (Blueprint $table) {
            $table->id();
            $table->integer('record_id');
            $table->string('action');
            $table->string('table_name');
            $table->integer('performed_by');
            $table->string('email');
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_audit_log');
    }
};
