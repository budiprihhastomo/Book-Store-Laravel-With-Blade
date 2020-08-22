<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Authors extends Model
{

    protected $fillable = ['id', 'first_name', 'middle_name', 'last_name'];

    public function books()
    {
        return $this->belongsToMany(Books::class, 'book_author', 'author_id', 'book_id');
    }
}
