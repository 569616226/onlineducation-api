<?php

namespace Tests\Feature\Image;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;


class ImageControllerTest extends BaseCase
{

    /**
     * @test
     */
    public function test_upload_a_image()
    {
        Storage::fake('avatars');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/image/upload/', [
            'image' => UploadedFile::fake()->image('avatar.jpg', 200, 200)->size(100)
        ]);

        $response->assertStatus(200);

    }
}
