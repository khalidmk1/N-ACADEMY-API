<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('avatar')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->boolean('is_login')->default(false);
            $table->string('type_speaker')->nullable();
            $table->string('date_inscription')->nullable();
            $table->date('date_renouvellement')->nullable();
            $table->string('status')->nullable();
            $table->text('biographie')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
