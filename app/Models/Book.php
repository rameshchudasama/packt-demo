<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Searchable;

class Book extends Model
{

    use Searchable;

    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'genre',
        'published_at'
    ];
}
