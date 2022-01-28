<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function getAll(){
        $categories = Category::all();

        return response()->json($categories, 200);
    }

    public function getCat($id){
        $category = Category::whereId($id)->first();

        return response()->json(['category' => $category], 200);
    }

    public function createCat(Request $request){
        $this->validate($request, [
            'cat' => 'required|max:30|unique:categories,name'
        ]);

        $cat = new Category;
        $cat->name = $request->cat;
        $cat->save();

        return response()->json($cat, 200);
    }
}
