<?php

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('does not create an admin without required field', function () {
    $response = $this->postJson('/api/v1/admin/create', []);
    $response->assertStatus(422);
});

it('can create an admin', function () {
    $attributes = User::factory()->raw();
    $response = $this->postJson('/api/v1/admin/create', $attributes);
    $response->assertStatus(201)->assertJson(['message' => 'admin created successfully']);
});
