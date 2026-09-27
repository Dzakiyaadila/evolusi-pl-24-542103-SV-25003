<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_journal_index_page_can_be_rendered(): void
    {
        Post::factory()->count(3)->create();

        $response = $this->get(route('posts.index'));

        $response->assertOk();
        $response->assertSee(Post::first()->title);
    }

    public function test_a_new_journal_entry_can_be_created(): void
    {
        $response = $this->post(route('posts.store'), [
            'title' => 'Hari yang menyenangkan',
            'mood' => 'senang',
            'body' => 'Hari ini aku belajar Laravel dan berhasil membuat CRUD pertamaku.',
        ]);

        $response->assertRedirect(route('posts.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Hari yang menyenangkan',
            'mood' => 'senang',
        ]);
    }

    public function test_journal_entry_requires_a_title_and_body(): void
    {
        $response = $this->post(route('posts.store'), [
            'title' => '',
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'body']);
    }

    public function test_a_journal_entry_can_be_updated(): void
    {
        $post = Post::factory()->create(['title' => 'Judul lama']);

        $response = $this->put(route('posts.update', $post), [
            'title' => 'Judul baru',
            'mood' => $post->mood,
            'body' => $post->body,
        ]);

        $response->assertRedirect(route('posts.show', $post));
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Judul baru']);
    }

    public function test_a_journal_entry_can_be_deleted(): void
    {
        $post = Post::factory()->create();

        $response = $this->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_journal_entries_with_the_same_title_have_unique_slugs(): void
    {
        $first = Post::factory()->create(['title' => 'Catatan sama']);
        $second = Post::factory()->create(['title' => 'Catatan sama']);

        $this->assertSame('catatan-sama-1', $second->slug);
        $this->assertSame('catatan-sama', $first->slug);
    }
}
