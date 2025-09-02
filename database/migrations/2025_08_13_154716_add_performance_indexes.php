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
        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('role_id');
            $table->index('email');
            $table->index('is_active');
            $table->index(['role_id', 'is_active']);
        });

        // Contracts table indexes
        Schema::table('contracts', function (Blueprint $table) {
            $table->index('client_id');
            $table->index('address_id');
            $table->index('status');
            $table->index('type');
            $table->index('start_date');
            $table->index('end_date');
            $table->index(['status', 'end_date']);
            $table->index(['client_id', 'status']);
            $table->index(['type', 'status']);
        });

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->index('contract_id');
            $table->index('status');
            $table->index('due_date');
            $table->index('paid_date');
            $table->index(['status', 'due_date']);
            $table->index(['contract_id', 'status']);
        });



        // Clients table indexes
        Schema::table('clients', function (Blueprint $table) {
            $table->index('status');
            $table->index('client_type');
            $table->index('mobile_number');
            $table->index(['status', 'client_type']);
        });

        // Machines table indexes


        // Addresses table indexes
        Schema::table('addresses', function (Blueprint $table) {
            $table->index('client_id');
            $table->index('area');
            $table->index('block');
            $table->index(['client_id', 'area']);
        });

        // Role features pivot table indexes
        Schema::table('role_features', function (Blueprint $table) {
            $table->index(['role_id', 'feature_id']);
            $table->index('is_granted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role_id']);
            $table->dropIndex(['email']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['role_id', 'is_active']);
        });

        // Contracts table indexes
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropIndex(['client_id']);
            $table->dropIndex(['address_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['type']);
            $table->dropIndex(['start_date']);
            $table->dropIndex(['end_date']);
            $table->dropIndex(['status', 'end_date']);
            $table->dropIndex(['client_id', 'status']);
            $table->dropIndex(['type', 'status']);
        });

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['contract_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['paid_date']);
            $table->dropIndex(['status', 'due_date']);
            $table->dropIndex(['contract_id', 'status']);
        });



        // Clients table indexes
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['client_type']);
            $table->dropIndex(['mobile_number']);
            $table->dropIndex(['status', 'client_type']);
        });



        // Addresses table indexes
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['client_id']);
            $table->dropIndex(['area']);
            $table->dropIndex(['block']);
            $table->dropIndex(['client_id', 'area']);
        });

        // Role features pivot table indexes
        Schema::table('role_features', function (Blueprint $table) {
            $table->dropIndex(['role_id', 'feature_id']);
            $table->dropIndex(['is_granted']);
        });
    }
};
