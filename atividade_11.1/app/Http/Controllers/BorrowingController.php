<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing; 
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BorrowingController extends Controller
{
   public function store(Request $request, Book $book)
   {
     // Impede que clientes registrem empréstimos
     if (!in_array(auth()->user()->role, ['admin', 'bibliotecario'])) {
        return redirect()->route('books.show', $book)
            ->withErrors(['erro' => 'Você não tem permissão para registrar empréstimos.']);
        }

      $request->validate([
        'user_id' => 'required|exists:users,id',
      ]);
    
     $targetUser = User::findOrFail($request->user_id);

     try {
        $this->authorize('create', $targetUser);
    } catch (AuthorizationException $e) {
        // Detecta motivo e responde de forma amigável
        if ($targetUser->debit > 0) {
            return redirect()->route('books.show', $book)
                ->withErrors(['erro' => 'Este usuário possui débitos pendentes e não pode realizar novos empréstimos.']);
        }

        return redirect()->route('books.show', $book)
            ->withErrors(['erro' => 'Este usuário já atingiu o limite de 5 livros emprestados simultaneamente.']);
    }

     // Verifica se já existe um empréstimo em aberto para este livro
     $jaEmprestado = Borrowing::where('book_id', $book->id)
         ->whereNull('returned_at')
         ->exists();

     if ($jaEmprestado) {
         return redirect()->route('books.show', $book)->withErrors([
             'erro' => 'Este livro já está emprestado e ainda não foi devolvido.'
         ]);
     }

     Borrowing::create([
         'user_id' => $request->user_id,
         'book_id' => $book->id,
         'borrowed_at' => now(),
     ]);

     return redirect()->route('books.show', $book)->with('success', 'Empréstimo registrado com sucesso.');
    }
    public function returnBook(Borrowing $borrowing)
   {
     DB::transaction(function () use ($borrowing) {
        // Marcar devolução
        $borrowing->update([
            'returned_at' => now(),
        ]);

        // Cálculo de atraso
        $borrowedAt = Carbon::parse($borrowing->borrowed_at);
        $dueDate = $borrowedAt->copy()->addDays(LibraryRules::DUE_DAYS);

        $daysLate = now()->startOfDay()->diffInDays($dueDate, false);
        // diffInDays com 'false' retorna negativo se passou da data
        $lateDays = max(0, -$daysLate);

        if ($lateDays > 0) {
            $fine = $lateDays * LibraryRules::DAILY_FINE;

            // Soma ao débito do usuário
            $user = $borrowing->user; // certifique-se que relação existe
            $user->increment('debit', $fine);

            // (Opcional) registre um log/entidade de multas
            // Fine::create([...]);
        }
     });

     return redirect()
        ->route('books.show', $borrowing->book_id)
        ->with('success', 'Devolução registrada com sucesso.' . (isset($fine) && $fine > 0
            ? ' Multa aplicada: R$ ' . number_format($fine, 2, ',', '.')
            : ''));
    }

    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()->withPivot('borrowed_at', 'returned_at')->get();

        return view('users.borrowings', compact('user', 'borrowings'));
    }
}
