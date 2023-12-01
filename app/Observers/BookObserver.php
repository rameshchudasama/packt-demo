<?php

namespace App\Observers;

use App\Models\Book;
use Elastic\Elasticsearch\Client;

class BookObserver
{
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }
    /**
     * Handle the Book "created" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function created(Book $book)
    {
        $this->elasticsearch->index([
            'index' => $book->getSearchIndex(),
            'type' => $book->getSearchType(),
            'id' => $book->id,
            'body' => $book->toSearchArray(),
        ]);
    }

    /**
     * Handle the Book "updated" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function updated(Book $book)
    {
        $this->elasticsearch->index([
            'index' => $book->getSearchIndex(),
            'type' => $book->getSearchType(),
            'id' => $book->id,
            'body' => $book->toSearchArray(),
        ]);
    }

    /**
     * Handle the Book "deleted" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function deleted(Book $book)
    {
        $this->elasticsearch->delete([
            'index' => $book->getSearchIndex(),
            'type' => $book->getSearchType(),
            'id' => $book->id,
        ]);
    }

    /**
     * Handle the Book "restored" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function restored(Book $book)
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function forceDeleted(Book $book)
    {
        //
    }
}
