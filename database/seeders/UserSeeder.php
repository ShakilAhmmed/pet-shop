<?php

namespace Database\Seeders;

use App\Enums\AdminStatus;
use App\Enums\MarketingStatus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create([
            'first_name' => 'Default',
            'last_name' => 'User',
            'email' => 'user@buckhill.co.uk',
            'is_admin' => AdminStatus::NO,
            'password' => 'user',
            'address' => 'Default Address',
            'phone_number' => '018xxxxxxxx',
            'is_marketing' => MarketingStatus::YES,
        ]);
    }
}
