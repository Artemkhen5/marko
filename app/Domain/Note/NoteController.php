<?php

namespace App\Domain\Note;

use App\Domain\Note\Requests\NoteStoreFromFileRequest;
use App\Domain\Note\Requests\NoteStoreRequest;
use App\Domain\Note\Requests\NoteUpdateRequest;
use App\Domain\Note\Resources\NoteResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::where('user_id', auth()->id())->get();
        return NoteResource::collection($notes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteStoreRequest $request)
    {
        $data = $request->validated();
        $note = $request->user()->notes()->create($data);
        return NoteResource::make($note);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        Gate::authorize('modify', $note);
        return NoteResource::make($note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteUpdateRequest $request, Note $note)
    {
        Gate::authorize('modify', $note);
        $data = $request->validated();
        $note->update($data);
        return NoteResource::make($note);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        Gate::authorize('modify', $note);
        $note->delete();
        return response()->json([
            'message' => 'Note deleted successfully'
        ]);
    }

    public function file(NoteStoreFromFileRequest $request)
    {
        $title = $request->validated('title');
        $content = $request->validated('file')->getContent();
        $note = $request->user()->notes()->create(['title' => $title, 'content' => $content]);
        return NoteResource::make($note);
    }
}
