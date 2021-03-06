<?php

namespace Tests\Feature;

use App\Lists;
use App\SharedList;
use App\Tenant;
use App\User;
use Laravel\Passport\Passport;
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

        SharedList::share('myGroup', $this->user_A->id);

        $this->assertEquals(2, Lists::all()->count());

        //testing user_B
        $this->actingAs($this->user_B);

        $this->assertEquals(0, Lists::all()->count());

        SharedList::share('das ist mein test', $this->user_B->id);

        $this->assertEquals(1, Lists::all()->count());
    }

    public function testWebAccess()
    {
        //user A creates some points
        Passport::actingAs($this->user_A);

        $list1 = Lists::create([
            'title' => 'myTitle1',
            'weight' => 1,
            'token' => 'myGroup'
        ]);

        $list2 = Lists::create([
            'title' => 'myTitle2',
            'weight' => 0,
            'token' => 'myGroup'
        ]);

        //not allow remove from sharing cause this user created a list entry
        $this->assertFalse( SharedList::unshare('myGroup', $this->user_A->id));

        //userB now wants to see one of them
        Passport::actingAs($this->user_B);

        $result = $this->get('/api/lists/' . $list1->id);

        $this->assertEquals(404, $result->getStatusCode());

        //userB now wants to edit one of them
        Passport::actingAs($this->user_B);

        $result = $this->put('/api/lists/' . $list1->id,[
            'title' => 'fromUserB'
        ]);

        $this->assertEquals(404, $result->getStatusCode());

        //userB now wants to delete one of them
        Passport::actingAs($this->user_B);

        $result = $this->delete('/api/lists/' . $list1->id);

        $this->assertEquals(404, $result->getStatusCode());

        //now make it shared
        SharedList::share('myGroup', $this->user_B->id);

        $this->assertEquals(1, $this->user_B->fresh()->shared()->count());

        //reading
        $result = $this->get('/api/lists/' . $list1->id);

        $this->assertEquals(200, $result->getStatusCode());

        //so edit is possible
        $result = $this->put('/api/lists/' . $list1->id,[
            'title' => 'fromUserB'
        ]);

        $this->assertEquals(200, $result->getStatusCode());

        //so delete is possible
        $this->assertEmpty($list1->fresh()->deleted_at);
        $result = $this->delete('/api/lists/' . $list1->id);

        $this->assertEquals(200, $result->getStatusCode());

        $this->assertNotEmpty($list1->fresh()->deleted_at);
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
