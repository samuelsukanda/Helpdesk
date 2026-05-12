<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('tickets')->with('subCategories')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:categories,name',
            'icon'   => 'nullable|string|max:50',
            'color'  => 'nullable|string|max:10',
            'subs'   => 'nullable|array',
            'subs.*' => 'string|max:255',
        ]);

        $category = Category::create([
            'name'      => $request->name,
            'icon'      => $request->icon ?? 'fa-tag',
            'color'     => $request->color ?? '#6B7280',
            'is_active' => true,
        ]);

        if ($request->filled('subs')) {
            foreach (array_filter($request->subs) as $sub) {
                SubCategory::create(['category_id' => $category->id, 'name' => $sub, 'is_active' => true]);
            }
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        $category->load('subCategories');
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon'      => 'nullable|string|max:50',
            'color'     => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        $category->update([
            'name'      => $request->name,
            'icon'      => $request->icon,
            'color'     => $request->color,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function storeSubCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $exists = $category->subCategories()
            ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Sub-kategori sudah ada.'
            ], 422);
        }

        $sub = SubCategory::create([
            'category_id' => $category->id,
            'name' => $request->name,
            'is_active' => true,
        ]);

        return response()->json([
            'id'      => $sub->id,
            'name'    => $sub->name,
            'success' => true,
        ]);
    }

    public function destroySubCategory(SubCategory $subCategory)
    {
        $subCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sub-kategori berhasil dihapus.'
        ]);
    }

    public function destroy(Category $category)
    {
        if ($category->tickets()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan tiket.');
        }
        $category->subCategories()->delete();
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
