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
        Schema::create('markets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->string('duplicate_event');

            $table->string('type');
            $table->string('description');

            $table->foreignId('participant_id')->nullable()->constrained('event_participants')->nullOnDelete();
            $table->string('duplicate_participant');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
