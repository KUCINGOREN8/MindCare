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
        Schema::create('psychologists', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('photo_url')->nullable();
            $table->text('short_bio')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->json('languages')->nullable(); 
            
            $table->string('specialization');
            $table->string('license_number'); 
            $table->integer('years_experience')->default(0);
            $table->integer('consultation_fee')->default(0); // rupiah per hour

            $table->string('email')->unique();
            $table->string('password');

            $table->enum('preferred_language', ['en','id'])->default('en');
            $table->boolean('agree_to_terms')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychologists');
    }
};
