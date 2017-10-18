<?php

namespace Tests\Feature;

use App\Lists;
use App\SharedLists;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SharedListsTest extends TestCase
{
    public $user_A;

    public $user_B;

    public function setUp()
    {
        parent::setUp();
        $this->actingAs($this->admin);

        $this->setupUserScenario();
    }

    public function testShare()
    {
        //create some sources
        Lists::create([
            'title' => 'myTitle1',
            'weight' => 1,
            'token' => 'myGroup'
        ]);

        Lists::create([
            'title' => 'myTitle2',
            'weight' => 0,
            'token' => 'myGroup'
        ]);

        Lists::create([
            'title' => 'myTitle3',
            'weight' => 0,
            'token' => 'das ist mein test'
        ]);

        $this->assertEquals(3, Lists::all()->count());

        //testing user_A
        $this->actingAs($this->user_A);

        $this->assertEquals(0, Lists::all()->count());

        SharedLists::share('myGroup', $this->user_A->id);

        $this->assertEquals(2, Lists::all()->count());

        //testing user_B
        $this->actingAs($this->user_B);

        $this->assertEquals(0, Lists::all()->count());

        SharedLists::share('das ist mein test', $this->user_B->id);

        $this->assertEquals(1, Lists::all()->count());
    }

    public function setupUserScenario()
    {
        $this->user_A = User::create([
            'name' => 'UserA',
            'email' => 'userA@publicare.de',
            'password' => bcrypt('password'),
        ]);

        $this->user_B = User::create([
            'name' => 'UserB',
            'email' => 'userB@publicare.de',
            'password' => bcrypt('password'),
        ]);
    }
}
