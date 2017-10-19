<?php

namespace Tests;

use App\Tenant;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Mail;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    public $admin;

    public function setUp()
    {
        parent::setUp();

        Mail::fake();

        $this->setupUser();
    }

    public function setupUser()
    {
        $tenant = Tenant::create(['name' => 'Demo']);

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'daniel.koch@publicare.de',
            'password' => bcrypt('password'),
            'tenants_id' => $tenant->id,
            'is_superadmin' => true
        ]);
    }
}
