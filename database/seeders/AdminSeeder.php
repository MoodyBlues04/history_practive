<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'admin@admin.com';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->where('email', self::ADMIN_EMAIL)->exists()) {
            return;
        }

        User::query()->create([
            'name' => 'admin',
            'email' => self::ADMIN_EMAIL,
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now()->toDateString(),
            'is_admin' => true,
        ]);
    }
}
