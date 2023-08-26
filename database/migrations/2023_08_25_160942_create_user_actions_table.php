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
        Schema::create('user_actions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->nullable();
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('action');  // created / updated / deleted
            $table->string('action_model')->nullable();
            $table->integer('action_id')->nullable();  // CRUD entry ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_actions');
    }
};
