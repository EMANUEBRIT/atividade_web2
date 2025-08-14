<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksControllerApi extends Controller
{
    /**
     * Lista todos os livros
     */
    public function index()
    {
        return response()->json(Book::all(), 200);
    }

    /**
     * Cria um novo livro
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author_id'    => 'required|integer|exists:authors,id',
            'publisher_id' => 'required|integer|exists:publishers,id',
            'year'         => 'required|integer|min:1000|max:' . date('Y'),
        ]);

        $book = Book::create($validated);

        return response()->json($book, 201);
    }

    /**
     * Mostra um livro específico
     */
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        return response()->json($book, 200);
    }

    /**
     * Atualiza um livro
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        $validated = $request->validate([
            'title'        => 'sometimes|required|string|max:255',
            'author_id'    => 'sometimes|required|integer|exists:authors,id',
            'publisher_id' => 'sometimes|required|integer|exists:publishers,id',
            'year'         => 'sometimes|required|integer|min:1000|max:' . date('Y'),
        ]);

        $book->update($validated);

        return response()->json($book, 200);
    }

    /**
     * Remove um livro
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Livro excluído com sucesso'], 200);
    }
}
