<?php

namespace Tests;

use App\Tenants;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    public $admin;

    public function setUp()
    {
        parent::setUp();

        $this->setupUser();
    }

    public function setupUser()
    {
        $tenant = Tenants::create(['name' => 'Demo']);

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'daniel.koch@publicare.de',
            'password' => bcrypt('password'),
            'tenants_id' => $tenant->id,
            'is_superadmin' => true
        ]);
    }
}
