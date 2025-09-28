<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jabatan;
use Faker\Factory as Faker;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 1; $i <= 14; $i++) {
            Jabatan::create([
                // 'id_jabatan' => $i,
                'nama_jabatan' => $faker->randomElement(['Staff']),
                'divisi_bidang' => $faker->randomElement([
                    'Sekretaris',
                    'Bendahara',
                    'Medinfo',
                    'Riset dan Development',
                    'Minat dan Bakat',
                    'Sosial Masyarakat',
                    'Hubungan Masyarakat',
                    'PSDM',
                    'Biro Personalia',
                    'Ekonomi Kreatif',
                ]),
            ]);
        }
    }
}
