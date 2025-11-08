<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class IndexNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:100',
            'is_important' => 'nullable|boolean',
            'completed' => 'nullable|boolean',
            'sort_by' => 'nullable|in:created_at,updated_at,title,is_important',
            'sort_order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function getPerPage(): int
    {
        return $this->input('per_page', 15);
    }
}
