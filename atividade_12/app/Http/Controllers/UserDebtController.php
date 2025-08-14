<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserDebtController extends Controller
{
    public function index(Request $request)
    {
        // Autorização simples por role
        if (!in_array(auth()->user()->role, ['admin', 'bibliotecario'])) {
            abort(403);
        }

        $users = User::where('debit', '>', 0)
            ->orderByDesc('debit')
            ->paginate(20);

        return view('debtors.index', compact('users'));
    }

    public function clear(User $user)
    {
        if (!in_array(auth()->user()->role, ['admin', 'bibliotecario'])) {
            abort(403);
        }

        $user->update(['debit' => 0]);

        return back()->with('success', 'Débito do usuário zerado com sucesso.');
    }
}