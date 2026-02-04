<?php

namespace App\Providers;

use App\Adapters\AiAssistant\AiAssistantContract;
use App\Adapters\AiAssistant\GigaChatAssistant;
use App\Repositories\TransactionRepository\TransactionEloquentRepository;
use App\Repositories\TransactionRepository\TransactionRepositoryContract;
use App\Repositories\UserRepository\UserEloquentRepository;
use App\Repositories\UserRepository\UserRepositoryContract;
use App\Services\TransactionService\TransactionService;
use App\Services\TransactionService\TransactionServiceContract;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Services
        $this->app->bind(TransactionServiceContract::class, TransactionService::class);
        // Repo
        $this->app->bind(TransactionRepositoryContract::class, TransactionEloquentRepository::class);
        $this->app->bind(UserRepositoryContract::class, UserEloquentRepository::class);
        // Adapters
        $this->app->bind(AiAssistantContract::class, GigaChatAssistant::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
