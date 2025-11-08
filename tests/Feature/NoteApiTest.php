<?php

namespace Tests\Feature;

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_all_notes(): void
    {
        Note::factory()->count(3)->create();

        $response = $this->getJson('/api/notes');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'title', 'content', 'is_important', 'created_at', 'updated_at'],
                ],
            ])
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_note(): void
    {
        $noteData = [
            'title' => 'Test Note',
            'content' => 'This is a test note content.',
            'is_important' => true,
        ];

        $response = $this->postJson('/api/notes', $noteData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Note created successfully',
            ])
            ->assertJsonStructure([
                'data' => ['id', 'title', 'content', 'is_important', 'created_at', 'updated_at'],
            ]);

        $this->assertDatabaseHas('notes', [
            'title' => 'Test Note',
            'content' => 'This is a test note content.',
            'is_important' => true,
        ]);
    }

    public function test_create_note_validation_fails(): void
    {
        $response = $this->postJson('/api/notes', [
            'content' => 'Missing title',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_can_fetch_single_note(): void
    {
        $note = Note::factory()->create();

        $response = $this->getJson("/api/notes/{$note->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $note->id,
                    'title' => $note->title,
                    'content' => $note->content,
                ],
            ]);
    }

    public function test_can_update_note(): void
    {
        $note = Note::factory()->create([
            'title' => 'Original Title',
            'is_important' => false,
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'is_important' => true,
        ];

        $response = $this->putJson("/api/notes/{$note->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Note updated successfully',
            ]);

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Updated Title',
            'is_important' => true,
        ]);
    }

    public function test_can_delete_note(): void
    {
        $note = Note::factory()->create();
        $response = $this->deleteJson("/api/notes/{$note->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Note deleted successfully',
            ]);

        $this->assertDatabaseMissing('notes', [
            'id' => $note->id,
        ]);
    }

    public function test_can_search_notes(): void
    {
        Note::factory()->create(['title' => 'Laravel Tutorial', 'content' => 'Learning Laravel']);
        Note::factory()->create(['title' => 'Vue Guide', 'content' => 'Learning Vue']);
        Note::factory()->create(['title' => 'PHP Basics', 'content' => 'PHP fundamentals']);

        $response = $this->getJson('/api/notes?search=Laravel');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_filter_notes_by_importance(): void
    {
        Note::factory()->count(2)->create(['is_important' => true]);
        Note::factory()->count(3)->create(['is_important' => false]);

        $response = $this->getJson('/api/notes?is_important=1');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_sort_notes(): void
    {
        Note::factory()->create(['title' => 'Zebra', 'created_at' => now()->subDays(1)]);
        Note::factory()->create(['title' => 'Apple', 'created_at' => now()]);
        Note::factory()->create(['title' => 'Banana', 'created_at' => now()->subDays(2)]);

        $response = $this->getJson('/api/notes?sort_by=title&sort_order=asc');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals('Apple', $data[0]['title']);
        $this->assertEquals('Banana', $data[1]['title']);
        $this->assertEquals('Zebra', $data[2]['title']);

        $response = $this->getJson('/api/notes?sort_by=created_at&sort_order=desc');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals('Apple', $data[0]['title']);
        $this->assertEquals('Zebra', $data[1]['title']);
        $this->assertEquals('Banana', $data[2]['title']);
    }

    public function test_can_combine_search_filter_and_sort(): void
    {
        Note::factory()->create([
            'title' => 'Important Laravel Note',
            'content' => 'Laravel content',
            'is_important' => true,
        ]);
        Note::factory()->create([
            'title' => 'Regular Laravel Note',
            'content' => 'Laravel content',
            'is_important' => false,
        ]);
        Note::factory()->create([
            'title' => 'Important Vue Note',
            'content' => 'Vue content',
            'is_important' => true,
        ]);

        $response = $this->getJson('/api/notes?search=Laravel&is_important=1&sort_by=title&sort_order=asc');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');

        $data = $response->json('data');
        $this->assertEquals('Important Laravel Note', $data[0]['title']);
    }

    public function test_can_toggle_note_completed_status(): void
    {
        $note = Note::factory()->create([
            'title' => 'Test Note',
            'completed_at' => null,
        ]);

        $response = $this->patchJson("/api/notes/{$note->id}/toggle-completed");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Note marked as completed',
        ]);

        $this->assertNotNull($response->json('data.completed_at'));

        $note->refresh();
        $this->assertNotNull($note->completed_at);

        $response = $this->patchJson("/api/notes/{$note->id}/toggle-completed");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Note marked as not completed',
        ]);

        $this->assertNull($response->json('data.completed_at'));

        $note->refresh();
        $this->assertNull($note->completed_at);
    }

    public function test_completed_notes_are_included_in_list(): void
    {
        Note::factory()->create([
            'title' => 'Open Note',
            'completed_at' => null,
        ]);

        Note::factory()->create([
            'title' => 'Completed Note',
            'completed_at' => now(),
        ]);

        $response = $this->getJson('/api/notes');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    public function test_note_factory_creates_notes_with_completed_at(): void
    {
        $note = Note::factory()->create([
            'completed_at' => now()->subDay(),
        ]);

        $this->assertNotNull($note->completed_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $note->completed_at);
    }
}
