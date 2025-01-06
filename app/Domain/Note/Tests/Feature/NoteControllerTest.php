<?php

namespace App\Domain\Note\Tests\Feature;

use App\Domain\Note\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * A basic feature test example.
     */
    public function testIndexMethod(): void
    {
        $notes = Note::factory(5)->create(['user_id' => $this->user->id]);
        $response = $this->get(route('notes.index'));
        $response->assertStatus(200);

        $json = $notes->map(function ($note) {
            return [
                'id' => $note->id,
                'title' => $note->title,
                'content' => $note->content,
                'created_at' => $note->created_at->toDateTimeString(),
                'updated_at' => $note->updated_at->toDateTimeString(),
            ];
        })->toArray();
        $response->assertJson($json);
    }

    public function testShowMethod(): void
    {
        $note = Note::factory()->create(['user_id' => $this->user->id]);
        $response = $this->get(route('notes.show', ['note' => $note], [
            'accept' => 'application/json'
        ]));

        $response->assertStatus(200);
        $json = [
            'id' => $note->id,
            'title' => $note->title,
            'content' => $note->content,
            'created_at' => $note->created_at->toDateTimeString(),
            'updated_at' => $note->updated_at->toDateTimeString(),
        ];
        $response->assertJson($json);
    }

    public function testStoreMethod(): void
    {
        $data = [
            'title' => 'Title',
            'content' => 'Content',
        ];

        $this->post(route('notes.store'), $data);
        $this->assertDatabaseCount('notes', 1);

        $note = Note::first();
        $this->assertEquals($note->title, $data['title']);
        $this->assertEquals($note->content, $data['content']);
    }

    public function testUpdateMethod(): void
    {
        $note = Note::factory()->create(['user_id' => $this->user->id]);
        $data = [
            'title' => 'Title',
            'content' => 'Content',
        ];

        $response = $this->put(route('notes.update', ['note' => $note]), $data);
        $response->assertOk();
        $response->assertJson([
            'id' => $note->id,
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
    }

    public function testDestroyMethod(): void
    {
        $note = Note::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete(route('notes.destroy', ['note' => $note]));
        $response->assertOk();
        $this->assertDatabaseCount('notes', 0);
        $response->assertJson([
            'message' => 'Note deleted successfully'
        ]);
    }

    public function testFileMethod(): void
    {
        $file = File::create('test.md');
        file_put_contents($file->getPathname(), 'test');
        $data = [
            'title' => 'Title',
            'file' => $file,
        ];

        $response = $this->post(route('notes.file'), $data);
        $response->assertCreated();
        $this->assertDatabaseCount('notes', 1);

        $note = Note::first();
        $response->assertJson([
            'id' => $note->id,
            'title' => $note->title,
            'content' => $note->content,
        ]);
    }
}
