<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Book;
use App\Policies\BookPolicy;
use App\Models\Loan;
use App\Policies\LoanPolicy;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * O array de policies da aplicação.
     */
    protected $policies = [
        Book::class => BookPolicy::class,
        Loan::class => LoanPolicy::class,
        \App\Models\Borrowing::class => \App\Policies\BorrowingPolicy::class,
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

