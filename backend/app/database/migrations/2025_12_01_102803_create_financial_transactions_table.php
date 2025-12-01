<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('financial_account_id')->constrained()->cascadeOnDelete();
            $table->string('counterparty')->nullable();
            $table->string('description')->nullable();
            $table->enum('direction', ['debit', 'credit']);
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('RON');
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->timestamp('occurred_at');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
