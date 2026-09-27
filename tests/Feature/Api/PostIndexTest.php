<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_vue_can_read_journal_posts_in_json(): void
    {
        Post::factory()->create([
            'title' => 'Catatan lama',
            'published_at' => now()->subDay(),
        ]);

        Post::factory()->create([
            'title' => 'Catatan baru',
            'published_at' => now(),
        ]);

        $response = $this
            ->withHeader('Origin', 'http://localhost:5173')
            ->getJson('/api/posts');

        $response->assertOk()
            ->assertHeader('Access-Control-Allow-Origin', 'http://localhost:5173')
            ->assertJsonCount(2)
            ->assertJsonPath('0.title', 'Catatan baru')
            ->assertJsonPath('1.title', 'Catatan lama');
    }
}
