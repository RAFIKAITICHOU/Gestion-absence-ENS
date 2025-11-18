<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Exécute les seeds de la base de données.
     */
    public function run(): void
    {
        // Création des rôles (s'ils n'existent pas déjà)
        $roleAdministrateur = Role::firstOrCreate(
            ['name' => 'administrateur', 'guard_name' => 'web']
        );
        $roleProfesseur = Role::firstOrCreate(
            ['name' => 'professeur', 'guard_name' => 'web']
        );
        $roleEtudiant = Role::firstOrCreate(
            ['name' => 'etudiant', 'guard_name' => 'web']
        );

        // Création des permissions (s'elles n'existent pas déjà)
        $permissionGestionUtilisateurs = Permission::firstOrCreate(
            ['name' => 'gestionUtilisateurs', 'guard_name' => 'web']
        );
        $permissionGestionEDT = Permission::firstOrCreate(
            ['name' => 'gestionEDT', 'guard_name' => 'web']
        );
        $permissionGestionAbsence = Permission::firstOrCreate(
            ['name' => 'gestionAbsence', 'guard_name' => 'web']
        );
        $permissionAffichageStatistiques = Permission::firstOrCreate(
            ['name' => 'affichageStatistiques', 'guard_name' => 'web']
        );
        $permissionConfigurationApplication = Permission::firstOrCreate(
            ['name' => 'configurationApplication', 'guard_name' => 'web']
        );
        $permissionAfficherProfile = Permission::firstOrCreate(
            ['name' => 'afficherProfile', 'guard_name' => 'web']
        );
        $permissionAfficherEDT = Permission::firstOrCreate(
            ['name' => 'afficherEDT', 'guard_name' => 'web']
        );

        // Assignation des permissions au rôle Administrateur
        $roleAdministrateur->givePermissionTo([
            $permissionGestionUtilisateurs,
            $permissionGestionEDT,
            $permissionAffichageStatistiques,
            $permissionConfigurationApplication,
            $permissionAfficherProfile,
        ]);

        // Assignation des permissions au rôle Professeur
        $roleProfesseur->givePermissionTo([
            $permissionGestionAbsence,
            $permissionAfficherProfile,
            // $permissionAfficherEDT,
        ]);

        // Assignation des permissions au rôle Étudiant
        $roleEtudiant->givePermissionTo([
            $permissionAfficherProfile,
            // $permissionAfficherEDT,
        ]);
    }
}
