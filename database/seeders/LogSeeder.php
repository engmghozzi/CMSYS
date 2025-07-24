<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            return;
        }

        $actionTypes = ['create', 'update', 'delete', 'login', 'logout', 'view'];
        $modelTypes = ['Contract', 'Payment', 'Machine', 'Client', 'User'];
        
        $descriptions = [
            'Created new Contract #123',
            'Updated Contract #123',
            'Deleted Contract #123',
            'User logged in successfully',
            'User logged out',
            'Viewed Contract details',
            'Created new Payment #456',
            'Updated Payment #456',
            'Deleted Payment #456',
            'Created new Machine #789',
            'Updated Machine #789',
            'Deleted Machine #789',
            'Created new Client #101',
            'Updated Client #101',
            'Deleted Client #101',
        ];

        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $actionType = $actionTypes[array_rand($actionTypes)];
            $modelType = $modelTypes[array_rand($modelTypes)];
            $description = $descriptions[array_rand($descriptions)];
            
            // Create log entry with random date within last 30 days
            $createdAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            Log::create([
                'user_id' => $user->id,
                'action_type' => $actionType,
                'model_type' => $modelType,
                'model_id' => rand(1, 100),
                'description' => $description,
                'old_values' => $actionType === 'update' ? ['status' => 'old'] : null,
                'new_values' => $actionType !== 'delete' ? ['status' => 'new'] : null,
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
