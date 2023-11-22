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
        Schema::create('conference_details', function (Blueprint $table) {
            $table->id();

            $table->index('email');
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('phone_number', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->integer('topic_id')->nullable();
            $table->integer('conference_id')->nullable();
            $table->integer('user_id');
            $table->date('user_created_at')->nullable();
            $table->date('user_updated_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conference_details');
    }
};
