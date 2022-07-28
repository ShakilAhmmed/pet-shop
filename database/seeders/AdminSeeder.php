<?php

namespace Database\Seeders;

use App\Enums\AdminStatus;
use App\Enums\MarketingStatus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::query()->create([
            'first_name' => 'Default',
            'last_name' => 'Admin',
            'email' => 'admin@buckhill.co.uk',
            'is_admin' => AdminStatus::YES,
            'password' => 'admin',
            'address' => 'Default Address',
            'phone_number' => '018xxxxxxxx',
            'is_marketing' => MarketingStatus::NO,
        ]);
    }
}
