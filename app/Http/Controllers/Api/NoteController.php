<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\IndexNoteRequest;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Repositories\Contracts\NoteRepositoryInterface;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    public function __construct(
        protected NoteRepositoryInterface $noteRepository
    ) {}

    public function index(IndexNoteRequest $request): JsonResponse
    {
        $notes = $this->noteRepository->search(
            $request->validated(),
            $request->getPerPage()
        );

        return response()->json([
            'success' => true,
            'data' => NoteResource::collection($notes->items()),
            'meta' => [
                'current_page' => $notes->currentPage(),
                'last_page' => $notes->lastPage(),
                'per_page' => $notes->perPage(),
                'total' => $notes->total(),
                'from' => $notes->firstItem(),
                'to' => $notes->lastItem(),
            ],
            'links' => [
                'first' => $notes->url(1),
                'last' => $notes->url($notes->lastPage()),
                'prev' => $notes->previousPageUrl(),
                'next' => $notes->nextPageUrl(),
            ],
        ]);
    }

    public function store(StoreNoteRequest $request): JsonResponse
    {
        $note = $this->noteRepository->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Note created successfully',
            'data' => new NoteResource($note),
        ], 201);
    }

    public function show(Note $note): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new NoteResource($note),
        ]);
    }

    public function update(UpdateNoteRequest $request, Note $note): JsonResponse
    {
        $note = $this->noteRepository->update($note, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully',
            'data' => new NoteResource($note),
        ]);
    }

    public function destroy(Note $note): JsonResponse
    {
        $this->noteRepository->delete($note);

        return response()->json([
            'success' => true,
            'message' => 'Note deleted successfully',
        ]);
    }

    public function toggleCompleted(Note $note): JsonResponse
    {
        $note = $this->noteRepository->toggleCompleted($note);

        $message = $note->completed_at
            ? 'Note marked as completed'
            : 'Note marked as not completed';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => new NoteResource($note),
        ]);
    }
}
