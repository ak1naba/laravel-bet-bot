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
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('duplicate_user');
            $table->foreignId('market_id')->constrained()->cascadeOnDelete();
            $table->string('duplicate_market');
            $table->foreignId('odds_id')->constrained()->cascadeOnDelete();
            $table->string('duplicate_odds');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'won', 'lost', 'canceled'])->default('pending');
            $table->decimal('payout', 10, 2)->nullable(); // сколько реально выиграл
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
