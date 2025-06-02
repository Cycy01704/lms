<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publisher',        
        'published_date',
        'category_id',
        'quantity',
        'available_copies',
        'cover_image',
        'description'
    ];

    public function category(){
        return $this->belongsTo('App\Models\Category','category_id');
    }
}
