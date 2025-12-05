<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('RON');
            $table->timestamp('paid_at');
            $table->foreignId('financial_transaction_id')->nullable()->constrained('financial_transactions')->nullOnDelete();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_payments');
    }
};
