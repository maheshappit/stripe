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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('create_date', 255)->nullable();
            $table->string('email_sent_date', 255)->nullable();
            $table->string('company_source', 255)->nullable();
            $table->string('contact_source', 255)->nullable();
            $table->string('database_creator_name', 255)->nullable();
            $table->string('technology', 255)->nullable();
            $table->string('client_speciality', 255)->nullable();
            $table->string('client_name', 255)->nullable();
            $table->string('street', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('zip_code', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('designation', 255)->nullable();
            $table->string('email', 255)->unique();
            $table->string('email_response_1', 255)->nullable();
            $table->string('email_response_2', 255)->nullable();
            $table->string('rating', 255)->nullable();
            $table->string('followup', 255)->nullable();
            $table->string('linkedin_link', 255)->nullable();
            $table->string('employee_count')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
