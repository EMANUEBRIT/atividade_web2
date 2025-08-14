{{-- resources/views/debtors/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Usuários com Débito</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Débito</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        @forelse($users as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>R$ {{ number_format($u->debit, 2, ',', '.') }}</td>
                <td>
                    <form action="{{ route('debtors.clear', $u) }}" method="POST" onsubmit="return confirm('Zerar débito deste usuário?');">
                        @csrf
                        <button class="btn btn-sm btn-primary" type="submit">Zerar débito</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">Nenhum usuário com débito.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $users->links() }}
</div>
@endsection
