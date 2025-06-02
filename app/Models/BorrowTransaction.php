<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowTransaction extends Model
{
    protected $table = "borrow_transactions";
    protected $fillable = [
        'book_id',
        'user_id',
        'borrow_date',
        'due_date',
        'return_date',
        'fine_amount',
        'fine_status',
        'status'
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    

    public function book()
{
    return $this->belongsTo(Book::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

public function borrowedBooks()
{
    return $this->hasMany(BorrowedBook::class);
}
}
