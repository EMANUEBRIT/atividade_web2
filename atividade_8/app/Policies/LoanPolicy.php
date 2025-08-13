<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Loan;

class LoanPolicy
{
    // Permite criar empréstimos somente para admin e bibliotecario
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }

    // Outros métodos (view, update, delete) podem ser adicionados conforme precisar
}
