<?php

use App\Http\Controllers\Api\NoteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:60,1'])->group(function () {
    Route::apiResource('notes', NoteController::class);
    Route::patch('notes/{note}/toggle-completed', [NoteController::class, 'toggleCompleted']);
});
