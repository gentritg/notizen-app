<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'is_important' => 'nullable|boolean',
            'sort_by' => 'nullable|in:created_at,updated_at,title,is_important',
            'sort_order' => 'nullable|in:asc,desc'
        ]);

        $query = Note::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('is_important')) {
            $query->where('is_important', $request->boolean('is_important'));
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $allowedSortFields = ['created_at', 'updated_at', 'title', 'is_important'];
        $allowedSortOrders = ['asc', 'desc'];

        if (in_array($sortBy, $allowedSortFields) && in_array($sortOrder, $allowedSortOrders)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $notes = $query->limit(1000)->get();

        return response()->json([
            'success' => true,
            'data' => $notes
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'is_important' => 'boolean'
        ]);

        $note = Note::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Note created successfully',
            'data' => $note
        ], 201);
    }

    /**
     * @param Note $note
     * @return JsonResponse
     */
    public function show(Note $note): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $note
        ]);
    }

    /**
     * @param Request $request
     * @param Note $note
     * @return JsonResponse
     */
    public function update(Request $request, Note $note): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string|max:10000',
            'is_important' => 'sometimes|boolean'
        ]);

        $note->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully',
            'data' => $note
        ]);
    }

    public function destroy(Note $note): JsonResponse
    {
        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note deleted successfully'
        ]);
    }

    /**
     * @param Note $note
     * @return JsonResponse
     */
    public function toggleCompleted(Note $note): JsonResponse
    {
        $isNowCompleted = $note->toggleCompleted();

        return response()->json([
            'success' => true,
            'message' => $isNowCompleted
                ? 'Note marked as completed'
                : 'Note marked as not completed',
            'data' => $note->fresh()
        ]);
    }
}
