<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $category->load('products');
        return view('categories.show', compact('category'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('categories.create');
    }

    public function store(CategoryRequest $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $validated = $request->validated();
        Category::create(['name' => $validated['name']]);
        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $validated = $request->validated();
        $category->update(['name' => $validated['name']]);
        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}
