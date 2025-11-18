<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\Administrateur;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    public function run()
    {
        //  Étudiant
        $etudiantUser = User::updateOrCreate(
            ['email' => 'etudiantTest@example.com'],
            [
                'name' => 'Ali Etudiant',
                'prenom' => 'ALAMI',
                'password' => Hash::make('password'),
                'photo' => 'images/default.png'
            ]
        );

        Etudiant::updateOrCreate(
            ['user_id' => $etudiantUser->id],
            [
                'cne' => 'CNE001',
                'groupe_id' => 1,
            ]
        );

        //  Professeur
        $profUser = User::updateOrCreate(
            ['email' => 'prof@example.com'],
            [
                'name' => 'Yassine',
                'prenom' => 'Profyass',
                'password' => Hash::make('password'),
                'photo' => 'images/default.png'
            ]
        );

        Professeur::updateOrCreate(
            ['user_id' => $profUser->id],
            []
        );

        //  Administrateur
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'prenom' => 'AdminOne',
                'password' => Hash::make('password'),
                'photo' => 'images/default.png'
            ]
        );

        Administrateur::updateOrCreate(
            ['user_id' => $adminUser->id],
            []
        );

        $adminUser->assignRole('administrateur');
        $profUser->assignRole('professeur');
        $etudiantUser->assignRole("etudiant");
    }
}
