<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'is_important',
        'completed_at',
    ];

    protected $casts = [
        'is_important' => 'boolean',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function markAsCompleted(): void
    {
        $this->completed_at = now();
        $this->save();
    }

    public function markAsNotCompleted(): void
    {
        $this->completed_at = null;
        $this->save();
    }

    public function toggleCompleted(): bool
    {
        if ($this->isCompleted()) {
            $this->markAsNotCompleted();

            return false;
        }

        $this->markAsCompleted();

        return true;
    }
}
