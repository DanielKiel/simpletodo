<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasicUserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSuperAdmin()
    {
        $this->assertTrue($this->admin->isSuperAdmin());

        //test default is false
        $user = User::create([
            'name' => 'Admin',
            'email' => 'dummy@publicare.de',
            'password' => bcrypt('password')
        ]);

        $this->assertFalse($user->fresh()->isSuperAdmin());
    }
}
