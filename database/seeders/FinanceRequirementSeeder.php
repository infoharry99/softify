<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FinanceRequirement;

class FinanceRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'vikram@example.com')->first() ?? User::first();
        $userId = $user ? $user->id : 1;

        FinanceRequirement::firstOrCreate(
            ['vendor_name' => 'siva', 'company_name' => 'digital soluation'],
            [
                'created_by' => $userId,
                'vendor_name' => 'siva',
                'vendor_location' => 'chennai',
                'company_name' => 'digital soluation',
                'selected_candidates_count' => 2,
                'budget' => 40000.00,
                'date' => '2025-11-14',
                'remaining_payment' => 70000.00,
                'status' => 'No Update',
                'note' => 'Initial finance requirement for digital solution candidates.',
            ]
        );
    }
}
