<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUploadTable extends Migration
{
    public function up()
    {
        Schema::create('file_upload', function (Blueprint $table) {
            $table->id('file_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->foreignId('uploaded_by')->constrained('users', 'user_id')->onDelete('cascade'); // Alterado para 'user_id'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('file_upload');
    }
}
