@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Editar Livro</h1>

 <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $book->title) }}" required>
    </div>

    <div class="mb-3">
        <label for="publisher_id" class="form-label">Editora</label>
        <select class="form-select" id="publisher_id" name="publisher_id" required>
            @foreach($publishers as $publisher)
                <option value="{{ $publisher->id }}" {{ $book->publisher_id == $publisher->id ? 'selected' : '' }}>
                    {{ $publisher->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="author_id" class="form-label">Autor</label>
        <select class="form-select" id="author_id" name="author_id" required>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" {{ $book->author_id == $author->id ? 'selected' : '' }}>
                    {{ $author->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Categoria</label>
        <select class="form-select" id="category_id" name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="cover" class="form-label">Atualizar Capa (opcional)</label><br>
        @if ($book->cover)
            <img src="{{ asset('storage/' . $book->cover) }}" alt="Capa atual" width="100" class="mb-2"><br>
        @endif
        <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
    </div>

    <button type="submit" class="btn btn-success">Atualizar</button>
    <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
