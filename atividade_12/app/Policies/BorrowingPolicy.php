<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Borrowing;

class BorrowingPolicy
{
    /**
     * Determina se o usuário pode criar um novo empréstimo.
     */
     public function create(User $loggedUser, User $targetUser)
 {
     // 4.1: bloqueia se tiver débito pendente
     if ($targetUser->debit > 0) {
         return false;
     }

     // 4.2: mantém a regra dos 5 empréstimos
     $emprestimosAtivos = Borrowing::where('user_id', $targetUser->id)
         ->whereNull('returned_at')
         ->count();

     return $emprestimosAtivos < 5;
  }

}
