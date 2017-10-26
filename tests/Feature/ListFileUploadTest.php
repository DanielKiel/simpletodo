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

        Storage::fake('local');

        $response = $this->json('POST', '/api/list-files', [
            'files' => UploadedFile::fake()->image('avatar.jpg'),
            'lists_id' => $list->id,
            'version' => 1
        ]);

        $content = collect(json_decode($response->getContent()));

        $uploaded = $content->first();

        // Assert the file was stored...
        Storage::disk('local')->assertExists($uploaded->path);

        // Assert a file does not exist...
        Storage::disk('local')->assertMissing('missing.jpg');
    }
}
