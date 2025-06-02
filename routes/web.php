<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



route::get('/home', [AdminController::class,'index'])->name('home');


Route::resource('/categories', CategoryController::class);
Route::resource('/books', BookController::class);

Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow.index');

Route::put('/borrow/return/{transaction}', [BorrowController::class, 'returnBook'])->name('borrow.return');
Route::put('/borrow/mark-lost/{transaction}', [BorrowController::class, 'markAsLost'])->name('borrow.markLost');
Route::put('/borrow/mark-damaged/{transaction}', [BorrowController::class, 'markAsDamaged'])->name('borrow.markDamaged');
Route::get('/borrow/create', [BorrowController::class, 'create'])->name('borrow.create');
Route::post('/borrow/store', [BorrowController::class, 'store'])->name('borrow.store');

Route::get('/borrow/books', [BorrowController::class, 'books'])->name('borrow.books');


Route::put('/borrow/{id}/lost', [BorrowController::class, 'markAsLost'])->name('borrow.markAsLost');
Route::put('/borrow/{id}/damaged', [BorrowController::class, 'markAsDamaged'])->name('borrow.damaged');
Route::put('/fines/{id}/mark-paid', [BorrowController::class, 'markFineAsPaid'])->name('fines.markPaid');

Route::post('/borrow/{id}/borrow', [BorrowController::class, 'borrow'])->name('borrow.borrow');

