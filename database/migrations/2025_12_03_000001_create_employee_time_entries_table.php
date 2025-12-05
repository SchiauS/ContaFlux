<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('worked_on');
            $table->decimal('hours', 5, 2);
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'worked_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_time_entries');
    }
};
