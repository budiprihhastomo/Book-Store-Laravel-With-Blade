<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    protected $table = 'books';

    protected $fillable = ['id', 'title', 'total_pages', 'rating', 'isbn', 'published_date'];

    public function authors()
    {
        return $this->belongsToMany(Authors::class, 'book_author', 'book_id', 'author_id');
    }
}
