<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function view(Category $category) {
        return view('category')->with(["category" => $category]);
    }
}
