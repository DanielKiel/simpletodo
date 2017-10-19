<?php

namespace Tests\Feature;

use App\Tenant;
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

        $tenant = Tenant::create(['name' => 'Demo']);

        //test default is false
        $user = User::create([
            'name' => 'Admin',
            'email' => 'dummy@publicare.de',
            'password' => bcrypt('password'),
            'tenants_id' => $tenant->id,
            'settings' => [
                'enableNotifications' => true
            ]
        ]);

        $this->assertFalse($user->fresh()->isSuperAdmin());

        $this->assertTrue($user->profile('enableNotifications'));
    }
}
