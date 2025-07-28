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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->date('visit_date');
            $table->string('visit_type')->enum('visit_type', ['Proactive', 'Maintenance', 'Repair', 'Installation', 'Other']);
            $table->string('visit_status')->enum('visit_status', ['schedualed', 'Completed', 'Cancelled']);
            $table->string('technician_name');
            $table->string('visit_notes')->nullable();
            $table->string('before_visit_attachments')->nullable();
            $table->string('after_visit_attachments')->nullable();
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
        Schema::dropIfExists('visits');
    }
};
