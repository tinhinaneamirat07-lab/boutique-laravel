<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // `php artisan db:seed` lance cette méthode
    public function run(): void
    {
        $this->call([WilayaSeeder::class, ProductSeeder::class]);

        // Compte admin (connexion : jour 7) — mot de passe à changer avant la mise en ligne
        User::firstOrCreate(['email' => 'admin@boutique.test'], [
            'name' => 'Admin',
            'password' => 'password',   // haché automatiquement par le model User
        ]);
    }
}
