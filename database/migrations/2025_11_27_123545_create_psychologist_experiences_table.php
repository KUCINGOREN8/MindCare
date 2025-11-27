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
        Schema::create('psychologist_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('psychologist_id')->constrained()->onDelete('cascade');
            $table->string('position');
            $table->string('organization');
            $table->string('start_year')->nullable();
            $table->string('end_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychologist_experiences');
    }
};
