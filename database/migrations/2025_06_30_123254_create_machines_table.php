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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->string('serial_number')->nullable();
            $table->enum('type', [

                'Package Unit',
                'Split',
                'Cassette',
                'Central Ducted',
                'Floor Standing',
                'Chiller',
                'Wall Mounted',
                'Portable',
                'Window',
                'VRF/VRV',
                'Other',

            ])->default('Package Unit');

            $table->enum('brand', [
                'LG',
                'Gree',
                'Carrier',
                'Mitsubishi',
                'Panasonic',
                'Daikin',
                'York',
                'Samsung',
                'Toshiba',
                'General',
                'Sharp',
                'Hitachi',
                'Haier',
                'Trane',
                'Midea',
                'Friedrich',
                'Other',

            ])->default('LG');

            $table->enum('UOM', [
                'HP',
                'PTU',
                'Other',
            ])->default('HP');

            $table->decimal('capacity', 10)->default(1);

            $table->enum('current_efficiency', [
                5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100
            ])->default(50);
            $table->string('assessment')->nullable();

            $table->integer('cost')->default(25);
            
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
        Schema::dropIfExists('machines');
    }
};
