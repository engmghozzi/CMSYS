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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->decimal('amount', 10, 3);
            
            $table->enum('status', [
                'Unpaid',
                'Paid',
            ])->default('Unpaid');
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->enum('method', [
                'Cash',
                'KNET',
                'Cheque',
                'Wamd',
                'other'
            ])->default('Cash');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
