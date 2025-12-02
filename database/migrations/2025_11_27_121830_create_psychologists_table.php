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
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();

            $table->text('short_bio')->nullable();
            $table->text('about_me')->nullable();
            $table->json('languages')->nullable();
            $table->string('title');
            $table->string('specialization');
            $table->string('license_number');
            $table->integer('years_experience')->default(0);
            $table->decimal('consultation_fee', 10, 2)->default(0); // rupiah per hour

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
