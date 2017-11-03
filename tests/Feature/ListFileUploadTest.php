<?php

namespace Tests\Feature;

use App\ListFile;
use App\Lists;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListFileUploadTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->actingAs($this->admin);
        Passport::actingAs($this->admin);
    }

    public function testUpload()
    {
        $list = Lists::create([
            'title' => 'myTitle'
        ]);

        Storage::fake($list->getDirectoryName());

        //we will have validation error here
        $response = $this->json('POST', '/api/list-files', [
            'upload' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->json('POST', '/api/list-files', [
            'upload' => UploadedFile::fake()->image('avatar.jpg'),
            'lists_id' => $list->id,
            'version' => 1
        ]);


        $uploaded = json_decode($response->getContent());

        // Assert the file was stored...
        Storage::disk()->assertExists($uploaded->path);

        // Assert a file does not exist...
        Storage::disk()->assertMissing('missing.jpg');

        $response = $this->json('PUT', '/api/list-files/' . $uploaded->id, [
            'published' => 0
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent());

        $this->assertFalse($content->published);
    }
}
