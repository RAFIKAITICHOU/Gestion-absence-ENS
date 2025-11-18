<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Filiere;
use \App\Models\Groupe;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create(); /* 10 datas aleatoires selon ICHOU */

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $filiereInfo = Filiere::factory()->create([
            'nom_filiere' => 'info',
        ]);

        $filiereMath = Filiere::factory()->create([
            'nom_filiere' => 'Math'
        ]);

        $gB = Groupe::factory()->create([
            'id_filiere' => $filiereInfo->id,
            'nom_groupe' => 'Group B'
        ]);

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
