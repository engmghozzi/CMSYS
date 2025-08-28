<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('Unpaid','Paid','Other') NOT NULL DEFAULT 'Unpaid'");
        } else {
            // No-op for sqlite/pgsql during local/testing. Production MySQL will run the ALTER above.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('Unpaid','Paid') NOT NULL DEFAULT 'Unpaid'");
        } else {
            // No-op for sqlite/pgsql
        }
    }
};


