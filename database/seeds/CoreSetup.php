<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Tenants;

class CoreSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant = Tenants::create([
            'name' => 'MainBU'
        ]);

        User::create([
            'name' => 'admin',
            'email' => 'dk.projects.manager@gmail.com',
            'password' => bcrypt('admin'),
            'tenants_id' => $tenant->id
        ]);
    }
}
