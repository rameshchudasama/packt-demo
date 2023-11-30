<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = json_decode(file_get_contents('https://fakerapi.it/api/v1/books?_quantity=999'), true);
        if ($response['status'] == 'OK') {
            foreach ($response['data'] as $book) {
                Book::create([
                    'title' => $book['title'],
                    'author' => $book['author'],
                    'genre' => $book['genre'],
                    'description' => $book['description'],
                    'isbn' => $book['isbn'],
                    'image' => $book['image'],
                    'published_at' => $book['published'],
                    'publisher' => $book['publisher'],
                ]);
            }
        } else {
            Book::factory()->count(1000)->create();
        }
    }
}
