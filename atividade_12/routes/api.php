<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BooksControllerApi;

// Rotas da API para o recurso Book
Route::middleware('api')->group(function () {
    Route::apiResource('books', BooksControllerApi::class);
});