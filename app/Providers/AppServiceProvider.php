<?php

namespace App\Providers;

use App\Repositories\Contracts\NoteRepositoryInterface;
use App\Repositories\NoteRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NoteRepositoryInterface::class, NoteRepository::class);
    }

    public function boot(): void {}
}
