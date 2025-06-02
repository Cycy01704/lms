<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowedBook extends Model
{
    protected $fillable=[
        "borrow_transaction_id","book_id","user_id","quantity"
    ] ;

    public function user_id(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function borrow_transaction_id(){
        return $this->belongsTo('App\Models\BorrowTransaction','borrow_transaction_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    
    public function user() { return $this->belongsTo(User::class); }
    public function borrowTransaction() { return $this->belongsTo(BorrowTransaction::class); }


}
