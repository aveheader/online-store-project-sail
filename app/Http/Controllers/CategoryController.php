<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('category.index', compact('categories'));
    }
    public function show(Category $category)
    {
        $products = $category->products()->paginate(12);

        return view('category.show', compact('category', 'products'));
    }
}
