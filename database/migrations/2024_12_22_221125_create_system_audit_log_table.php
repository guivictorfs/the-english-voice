<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemAuditLogTable extends Migration
{
    public function up()
    {
        Schema::create('system_audit_log', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade'); // Alterado para 'user_id'
            $table->string('action');
            $table->string('table_name');
            $table->integer('record_id');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_audit_log');
    }
}
