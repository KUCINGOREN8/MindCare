<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
{
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('psychologist_id')->constrained()->onDelete('cascade');

        $table->string('with');
        $table->string('job_title')->nullable();

        $table->date('date');
        $table->time('start_time');
        $table->time('end_time');
        $table->decimal('consultation_fee', 10, 2);

        $table->enum('status', [
            'pending_payment',
            'pending',
            'confirmed',
            'completed',
            'cancelled',
            'expired'
        ])->default('pending_payment');

        $table->text('notes')->nullable();

        // reschedule request
        $table->date('reschedule_date')->nullable();
        $table->time('reschedule_time')->nullable();
        $table->text('reschedule_reason')->nullable();

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
