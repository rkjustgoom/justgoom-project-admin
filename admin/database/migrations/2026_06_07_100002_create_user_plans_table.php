<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->restrictOnDelete();
            $table->date('purchase_date');
            $table->date('next_purchase_date');

            $table->index(['user_id', 'next_purchase_date']);
            $table->index('next_purchase_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_plans');
    }
};
