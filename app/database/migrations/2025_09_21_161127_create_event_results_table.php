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
        Schema::create('event_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->string('duplicate_event')->nullable();

            $table->foreignId('winner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('duplicate_winner')->nullable();

            $table->jsonb('metadata');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_results');
    }
};
