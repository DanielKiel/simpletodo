<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Tenant;

class CoreSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant = Tenant::create([
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
