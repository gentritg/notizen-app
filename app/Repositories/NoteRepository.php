<?php

namespace App\Repositories;

use App\Models\Note;
use App\Repositories\Contracts\NoteRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class NoteRepository implements NoteRepositoryInterface
{
    public function search(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Note::query();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if (isset($filters['is_important'])) {
            $query->where('is_important', $filters['is_important']);
        }

        if (isset($filters['completed'])) {
            if ($filters['completed']) {
                $query->whereNotNull('completed_at');
            } else {
                $query->whereNull('completed_at');
            }
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        $allowedSortFields = ['created_at', 'updated_at', 'title', 'is_important'];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): Note
    {
        return Note::create($data);
    }

    public function update(Note $note, array $data): Note
    {
        $note->update($data);

        return $note->fresh();
    }

    public function delete(Note $note): bool
    {
        return $note->delete();
    }

    public function toggleCompleted(Note $note): Note
    {
        $note->toggleCompleted();

        return $note->fresh();
    }
}
