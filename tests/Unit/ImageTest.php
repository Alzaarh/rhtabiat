<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_image_with_url_is_stored_correct()
    {
        \Storage::fake('test');

        $url = '/api/images';
        $responseMessage = 'لینک پیوست با موفقیت اضافه شد';
        $data = [
            'title' => 'image title',
            'alt' => 'image alt',
            'short_desc' => 'image short_desc',
            'desc' => 'image desc',
            'url' => 'images/tests/my-image.jpg',
            'group' => 1,
            'image' => UploadedFile::fake()->image('image'),
        ];

        $response = $this->postJson($url, $data);

        $response->assertStatus(201)->assertJson(['message' => $responseMessage]);

        \Storage::disk('test')->assertExists($data['url']);

        $this->assertDatabaseHas('images', $data);
    }
}
