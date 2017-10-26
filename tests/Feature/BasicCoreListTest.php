<?php

namespace Tests\Feature;

use App\Lists;
use App\ListsHistory;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasicCoreListTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->actingAs($this->admin);

        Passport::actingAs($this->admin);
    }

    /**
     * When inserting a list point and later update it, we will produce a version based history to can reproduce changes later.
     *
     */
    public function testCoreList()
    {
        //basic insert
        $list = Lists::create([
            'title' => 'myTitle'
        ]);

        $this->assertDatabaseHas('lists', [
            'title' => 'myTitle'
        ]);

        //update means we must have an history entry
        $list->update([
            'title' => 'updatedTitle'
        ]);

        $this->assertDatabaseHas('lists', [
            'title' => 'updatedTitle'
        ]);

        $this->assertDatabaseHas('lists_history', [
            'title' => 'myTitle'
        ]);
    }

    /**
     * cause Lists Model have a global scope OrderByWeight this will be assigned to all queries.
     */
    public function testOrderingByWeight()
    {
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
            'token' => 'other'
        ]);

        $all = Lists::all();

        $this->assertEquals('myTitle2', $all->first()->title);

        //manually written token query
        $grouped = Lists::where('token', 'myGroup')->get();

        $this->assertEquals('myTitle2', $grouped->first()->title);

        //using sciope for token query
        $grouped = Lists::grouped('myGroup')->get();

        $this->assertEquals('myTitle2', $grouped->first()->title);

        $first = Lists::where('token', 'myGroup')->first();

        $this->assertEquals('myTitle2', $first->title);
    }

    /**
     * cause Lists Model have a global scope OrderByVersion this will be assigned to all queries, also to a relation.
     */
    public function testOrderingByVersion()
    {
        //basic insert
        $list = Lists::create([
            'title' => 'myTitle'
        ]);

        $this->assertDatabaseHas('lists', [
            'title' => 'myTitle'
        ]);

        //update means we must have an history entry
        $list->update([
            'title' => 'updatedTitle'
        ]);

        $list->update([
            'title' => 'updatedTitleAgain'
        ]);

        $list->update([
            'title' => 'updatedTitleAgainAndAgain'
        ]);

        $list->update([
            'title' => 'A Last Time'
        ]);

        //getting by relation
        $history = $list->history;

        $this->assertEquals('updatedTitleAgainAndAgain', $history->first()->title);

        //getting by query
        $history = ListsHistory::where('lists_id', $list->id)->get();

        $this->assertEquals('updatedTitleAgainAndAgain', $history->first()->title);

        //getting by all()
        $history = ListsHistory::all();

        $this->assertEquals('updatedTitleAgainAndAgain', $history->first()->title);
    }

    public function testAPIResource()
    {
        $result = $this->post('/api/lists', [
            'title' => 'my title'
        ]);

        $this->assertEquals(200, $result->getStatusCode());

        $content = json_decode($result->getContent());

        $this->assertEquals('my title', $content->title);

        //make an update
        $result = $this->put('/api/lists/' . $content->id, [
            'title' => 'my title updated'
        ]);

        $content = json_decode($result->getContent());

        $this->assertEquals('my title updated', $content->title);

        $this->assertEquals(1, Lists::all()->count());

        //delete it
        $result = $this->delete('/api/lists/' . $content->id);

        $this->assertEquals(200, $result->getStatusCode());


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

        //getting all list entries by token
        $result = $this->get('/api/lists?token=das%20ist%20mein%20test');

        $this->assertEquals(200, $result->getStatusCode());

        $content = json_decode($result->getContent());

        $this->assertEquals(1, $content->total);

        //now get all tokens
        $unified = Lists::withoutGlobalScopes()->select(['token'])->groupBy('token')->get();

        $result = $this->get('/api/tokens');

        $this->assertEquals(200, $result->getStatusCode());

        $content = json_decode($result->getContent());

        $this->assertEquals($unified->count(), $content->total);
    }
}
