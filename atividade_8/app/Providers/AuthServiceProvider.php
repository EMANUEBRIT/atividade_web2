<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Book;
use App\Policies\BookPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * O array de policies da aplicação.
     */
    protected $policies = [
        Book::class => BookPolicy::class,
        // Ex: Author::class => AuthorPolicy::class,
    ];

    /**
     * Registre os serviços de autorização.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gates opcionais podem ir aqui
    }
}

