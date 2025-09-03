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
        Schema::table('contracts', function (Blueprint $table) {
            // Only add units_machines if it doesn't exist
            if (!Schema::hasColumn('contracts', 'units_machines')) {
                $table->integer('units_machines')->default(0)->after('centeral_machines');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (Schema::hasColumn('contracts', 'units_machines')) {
                $table->dropColumn('units_machines');
            }
        });
    }
};
