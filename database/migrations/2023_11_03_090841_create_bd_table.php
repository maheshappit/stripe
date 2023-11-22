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
        Schema::create('bd', function (Blueprint $table) {
            $table->id();

            $table->index('email');


            $table->binary('create_date', 255);
            $table->binary('email_sent_date', 255)->nullable();
            $table->binary('company_source', 255)->nullable();
            $table->binary('contact_source', 255)->nullable();
            $table->binary('database_creator_name', 255)->nullable();
            $table->binary('technology', 255)->nullable();
            $table->binary('client_speciality', 1000)->nullable();
            $table->binary('client_name', 1000)->nullable();
            $table->binary('street', 255)->nullable();
            $table->binary('city', 255)->nullable();
            $table->binary('state', 255)->nullable();
            $table->binary('zip_code', 255)->nullable();
            $table->binary('country', 255)->nullable();
            $table->binary('website', 255)->nullable();
            $table->binary('first_name', 255)->nullable();
            $table->binary('last_name', 255)->nullable();
            $table->binary('designation', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->binary('email_response_1', 255)->nullable();
            $table->binary('email_response_2', 255)->nullable();
            $table->binary('rating', 255)->nullable();
            $table->binary('followup', 255)->nullable();
            $table->binary('linkedin_link', 255)->nullable();
            $table->binary('employee_count')->nullable(); // A
            $table->string('user_id')->nullable(); // A


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::dropIfExists('bd');
    }
};
