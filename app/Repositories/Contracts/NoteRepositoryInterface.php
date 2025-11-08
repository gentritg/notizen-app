<?php

namespace App\Repositories\Contracts;

use App\Models\Note;
use Illuminate\Pagination\LengthAwarePaginator;

interface NoteRepositoryInterface
{
    public function search(array $filters, int $perPage): LengthAwarePaginator;

    public function create(array $data): Note;

    public function update(Note $note, array $data): Note;

    public function delete(Note $note): bool;

    public function toggleCompleted(Note $note): Note;
}
