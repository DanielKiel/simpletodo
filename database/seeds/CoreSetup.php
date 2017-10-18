<?php

use Illuminate\Database\Seeder;
use App\User;

class CoreSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'dk.projects.manager@gmail.com',
            'password' => bcrypt('admin')
        ]);
    }
}
