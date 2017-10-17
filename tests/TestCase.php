<?php

namespace Tests;

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
        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'daniel.koch@publicare.de',
            'password' => 'password'
        ]);
    }
}
