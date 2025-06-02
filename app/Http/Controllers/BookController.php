<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
{
    $books = Book::all();
    $categories = Category::all();
    
    
    return view('books.index', compact('books', 'categories'));
}


    public function create(){

        $categories = Category::get();

        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'isbn' => 'required',
        'category_id' => 'required|exists:categories,id',
        'title' => 'required',
        'author'=> 'required',
        'publisher'=> 'required',
        'quantity'=> 'required',
        'available_copies'=> 'required',
       
        

        // other validations...
        'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->all();

    if ($request->hasFile('cover_image')) {
        $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
    }

    Book::create($data);

    return redirect()->route("books.index")
        ->with("success", "Book Added Successfully");
}
    public function show(Book $book){
        return view('books.show', compact('book'));
    }

    public function edit($id){
        $book = Book::find($id);
        $categories = Category::get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'isbn' => 'required',
        'category_id' => 'required|exists:categories,id',
        'title' => 'required',
         'author'=> 'required',
        'publisher'=> 'required',
        'quantity'=> 'required',
        'available_copies'=> 'required',
        

        // other validations...
        'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $book = Book::find($id);
    $data = $request->all();

    if ($request->hasFile('cover_image')) {
        $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
    }

    $book->update($data);

    return redirect()->route("books.index")
        ->with("success", "Book Updated Successfully");
}

    public function destroy($id){
        $book = Book::find($id);
        $book->delete();
        return redirect()-> route("books.index")
        ->with  ("success","Book Deleted Successfully");
    }
}
