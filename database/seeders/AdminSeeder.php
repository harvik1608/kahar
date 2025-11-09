<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'myfish@gmail.com')->exists()) {
            User::create([
                'name' => 'Niraj Purabiya',
                'email' => 'myfish@gmail.com',
                'password' => Hash::make('myfish@123')
            ]);
        }
    }
}
