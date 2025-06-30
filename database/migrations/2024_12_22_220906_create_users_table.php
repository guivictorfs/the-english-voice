<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['Aluno', 'Professor', 'admin']);
            $table->string('ra', 20)->unique()->nullable();
            $table->foreignId('course_id')->constrained('course', 'course_id');
            $table->string('password_reset_token')->nullable();
            $table->timestamp('token_expiration')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}