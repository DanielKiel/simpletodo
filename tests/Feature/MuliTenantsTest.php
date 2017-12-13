<?php

namespace Tests\Feature;

use App\Lists;
use App\Tenant;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MuliTenantsTest extends TestCase
{

    public $bmw_User1;

    public $bmw_User2;

    public $vw_User1;

    public $vw_User2;

    public function setUp()
    {
        parent::setUp();

        $this->setupUserScenario();
    }

    public function testVisibilities()
    {
        $this->createLists();

        Passport::actingAs($this->bmw_User1);

        //testing getting lists
        $result = json_decode($this->get('/api/lists')->content());

        $this->assertEquals(3, $result->total);

        $this->get('/api/share/bmwGroup/' . $this->bmw_User2->id);

        Passport::actingAs($this->bmw_User2);

        //testing getting lists
        $result = json_decode($this->get('/api/lists')->content());

        $this->assertEquals(3, $result->total);

        //unshare
        $this->get('/api/unshare/bmwGroup/' . $this->bmw_User2->id);

        $result = json_decode($this->get('/api/lists')->content());

        $this->assertEquals(0, $result->total);

        //testing for vw
        Passport::actingAs($this->vw_User1);

        //testing getting lists
        $result = json_decode($this->get('/api/lists')->content());

        $this->assertEquals(2, $result->total);

        $this->get('/api/share/vwGroup/' . $this->vw_User2->id);

        Passport::actingAs($this->vw_User2);

        //testing getting lists
        $result = json_decode($this->get('/api/lists')->content());

        $this->assertEquals(2, $result->total);
    }

    public function createLists()
    {
        $this->actingAs($this->bmw_User1);

        Lists::create([
            'title' => 'Something',
            'token' => 'bmwGroup'
        ]);

        Lists::create([
            'title' => 'Something else',
            'token' => 'bmwGroup'
        ]);

        Lists::create([
            'title' => 'Anything',
            'token' => 'bmwGroup'
        ]);

        $this->actingAs($this->vw_User1);

        Lists::create([
            'title' => 'VW stuff',
            'token' => 'vwGroup'
        ]);

        Lists::create([
            'title' => 'VW stuff again',
            'token' => 'vwGroup'
        ]);
    }

    public function setupUserScenario()
    {
        $bmw = Tenant::create([
            'name' => 'bmw'
        ]);

        $vw = Tenant::create([
            'name' => 'vw'
        ]);

        $this->bmw_User1 = User::create([
            'name' => 'bmw_User1',
            'email' => 'bmw_User1@test.de',
            'password' => '123',
            'tenants_id' => $bmw->id
        ]);

        $this->bmw_User2 = User::create([
            'name' => 'bmw_User2',
            'email' => 'bmw_User2@test.de',
            'password' => '123',
            'tenants_id' => $bmw->id
        ]);

        $this->vw_User1 = User::create([
            'name' => 'vw_User1',
            'email' => 'vw_User1@test.de',
            'password' => '123',
            'tenants_id' => $vw->id
        ]);

        $this->vw_User2 = User::create([
            'name' => 'vw_User2',
            'email' => 'vw_User2@test.de',
            'password' => '123',
            'tenants_id' => $vw->id
        ]);
    }
}
