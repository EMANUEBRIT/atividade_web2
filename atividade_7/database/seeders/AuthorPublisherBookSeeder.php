<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as FakerFactory;

class AuthorPublisherBookSeeder extends Seeder
{
    public function run()
    {
        $faker = FakerFactory::create();

        // Gera 100 autores, cada um com 10 livros
        Author::factory(100)->create()->each(function ($author) use ($faker) {
            // Gera uma editora para cada autor
            $publisher = Publisher::factory()->create();

            $booksData = [];

            for ($i = 0; $i < 10; $i++) {
                // Gera a imagem no storage público
                $imagePath = 'covers/' . uniqid() . '.jpg';
                Storage::disk('public')->put($imagePath, file_get_contents($faker->imageUrl(200, 300)));

                $booksData[] = [
                    'title' => $faker->sentence(3),
                    'category_id' => Category::inRandomOrder()->first()->id,
                    'publisher_id' => $publisher->id,
                    'published_year' => $faker->year(),
                    'cover' => $imagePath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Cria os livros associados ao autor
            $author->books()->createMany($booksData);
        });
    }
}
