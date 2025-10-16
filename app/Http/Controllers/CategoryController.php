<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // List all categories (all users)
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // Show a single category (all users)
    public function show(Category $category)
    {
        $category->load('products');
        return view('categories.show', compact('category'));
    }

    // Show create form (admin only)
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('categories.create');
    }

    // Store new category (admin only)
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    // Show edit form (admin only)
    public function edit(Category $category)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('categories.edit', compact('category'));
    }

    // Update category (admin only)
    public function update(Request $request, Category $category)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    // Delete category (admin only)
    public function destroy(Category $category)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}

