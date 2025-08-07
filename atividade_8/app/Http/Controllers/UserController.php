<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::paginate(10); // Paginação para 10 usuários por página
        return view('users.index', compact('users'));
    }

    public function show(\App\Models\User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(\App\Models\User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, \App\Models\User $user)
    {
        $user->update($request->only('name', 'email'));

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }
    public function updateRole(Request $request, User $user)
   {
     if (auth()->user()->role !== 'admin') {
         abort(403);
     }

     $request->validate([
         'role' => 'required|in:admin,bibliotecario,cliente',
     ]);

     $user->update(['role' => $request->role]);

     return back()->with('success', 'Papel atualizado com sucesso!');
    }
}