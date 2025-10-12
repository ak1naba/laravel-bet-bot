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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sport_id')->constrained()->nullOnDelete();

            $table->string('title');
            $table->text('description');

            $table->timestampTz('start_time');
            $table->timestampTz('end_time')->nullable();

            $table->enum('status', ['scheduled', 'live', 'finished'])->default('scheduled');

            $table->jsonb('metadata')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
