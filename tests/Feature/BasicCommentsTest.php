<?php

namespace Tests\Feature;

use App\Comment;
use App\Lists;
use App\Tenant;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasicCommentsTest extends TestCase
{
    public $user_A;

    public $user_B;

    public function setUp()
    {
        parent::setUp();
        $this->actingAs($this->admin);

        $this->setupUserScenario();
    }

    public function testCommenting()
    {
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

        $comment  = Comment::create([
            'content' => 'Ich bin der Kommentar',
            'position' => [
                'title' => '1'
            ],
            'lists_id' => $list1->id,
            'version' => $list1->version
        ]);

        $this->assertDatabaseHas('comments',[
            'content' => 'Ich bin der Kommentar',
            'lists_id' => $list1->id,
            'by' => $this->admin->id
        ]);

        $this->assertEquals(1, $list1->fresh()->comments()->count());
        $this->assertEquals(1, $this->admin->fresh()->comments()->count());

        $this->assertEquals($this->admin->name, $comment->fresh()->byUser->name);
        $this->assertEquals($list1->title, $comment->fresh()->relatedList->title);
    }

    public function setupUserScenario()
    {
        $tenant = Tenant::create(['name' => 'Demo']);

        $this->user_A = User::create([
            'name' => 'UserA',
            'email' => 'userA@publicare.de',
            'password' => bcrypt('password'),
            'tenants_id' => $tenant->id,
        ]);

        $this->user_B = User::create([
            'name' => 'UserB',
            'email' => 'userB@publicare.de',
            'password' => bcrypt('password'),
            'tenants_id' => $tenant->id,
        ]);
    }
}
