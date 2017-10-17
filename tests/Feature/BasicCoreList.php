<?php

namespace Tests\Feature;

use App\Lists;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasicCoreList extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCoreList()
    {
        $this->actingAs($this->admin);

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
}
