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
        Schema::create('contracts', function (Blueprint $table) {

            $table->id();
            $table->string('contract_num')->unique(); // Auto-generated like CONT/24/001

            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade'); // One-to-one with address

            $table->enum('type', ['L', 'LS', 'C', 'Other'])->default('L');



            $table->date('start_date');
            $table->decimal('duration_months', 10, 2)->comment('Duration in months');
            $table->date('end_date'); // Auto-calculated

            $table->decimal('total_amount', 10, 3);
            $table->decimal('paid_amount', 10, 3)->default(0);
            $table->decimal('remaining_amount', 10, 3)->default(0);

            $table->decimal('commission_amount', 10, 3)->nullable();
            $table->enum('commission_type', ['Incentive Bonus', 'Referral Commission', 'Other'])->nullable();
            $table->string('commission_recipient')->nullable();
            $table->timestamp('commission_date')->nullable();

            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');

            $table->text('details')->nullable();
            $table->string('attachment_url')->nullable();

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
        Schema::dropIfExists('contracts');
    }
};
