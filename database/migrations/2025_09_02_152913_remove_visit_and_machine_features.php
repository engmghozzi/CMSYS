<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Feature;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove all visit and machine related features
        if (Schema::hasTable('features')) {
            Feature::where('name', 'like', '%visit%')
                  ->orWhere('name', 'like', '%machine%')
                  ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This migration is not reversible as we're cleaning up
        // unused features. If you need to restore these,
        // you would need to recreate the original features.
    }
};
