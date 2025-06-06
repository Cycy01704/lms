<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view("categories.index",compact("categories"));
    }

    public function create(){
        return view("categories.create");
    }

    public function store(Request $request){
        Category::create($request->all());
        return redirect()-> route("categories.index")
        ->with  ("success"," Category Created Successfully");
    }  
    
   public function edit($id){
        $category = Category::findOrFail($id);
        return view("categories.edit",compact("category"));
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'category_name' => 'required|string|max:255',
    ]);

    Category::findOrFail($id)->update($validated);

    return redirect()->route("categories.index")
        ->with("success", "Category Updated Successfully");
}


    public function destroy($id){
        Category::findOrFail($id)->delete();
        return redirect()->route("categories.index")
        ->with ("success","Category Deleted Successfully");
    }   
}
