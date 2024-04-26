<?php

namespace api;

use App\Models\File;
use Tests\TestCase;

class AttachmentApiTest extends TestCase
{
    public function test_api_get_all_files()
    {
        $response = $this->json("GET", "/api/v1/files");
        $response->assertJsonStructure([
            'data'
        ]);
        $response->assertStatus(200);

        // pagination
        $response->assertJsonStructure(["per_page"]);
    }

    public function test_api_find_file_by_specific_id()
    {
        $files = File::factory()->count(1)->create();
        $response = $this->json("GET", "/api/v1/files/" . $files->last()->id);
        $response->assertStatus(200);
    }

    public function test_api_find_file_by_wrong_file_id() {
        $response = $this->json("GET", "/api/v1/files/" . -1);
        $response->assertJsonStructure(["message"]);
        $response->assertStatus(404);
    }
}
