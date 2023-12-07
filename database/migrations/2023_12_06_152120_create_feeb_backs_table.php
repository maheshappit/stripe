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
        Schema::create('feeb_backs', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('conference');
            $table->string('article');
            $table->string('client_status');
            $table->string('comment');
            $table->date('comment_created_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeb_backs');
    }
};
