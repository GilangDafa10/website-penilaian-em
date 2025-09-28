<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Membuat 10 user dengan data acak
        for ($i = 3; $i <= 20; $i++) {
            User::create([
                'id_user' => $i,
                'nim' => $faker->randomElement(['V34240', 'V34230']) . rand(10, 99),
                'nama' => $faker->name,
                'password' => bcrypt('123456'), // Password default
                'role_id' => rand(1, 6), // Asumsikan ada 6 role
                'id_jabatan' => rand(1, 28), // Asumsikan ada 28 jabatan
            ]);
        }
    }
}
