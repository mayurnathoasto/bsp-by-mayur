<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with([
            'subcategories' => function ($q) {
                $q->orderBy('sort_order')->orderBy('name');
            }
        ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'url', 'max:2048'],
            'icon' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $baseSlug = Str::slug($data['name']);
        $slug = $this->makeUniqueCategorySlug($baseSlug);

        $category = Category::create([
            'name' => $data['name'],
            'slug' => $slug,
            'image' => $data['image'] ?? null,
            'icon' => $data['icon'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'image' => ['nullable', 'url', 'max:2048'],
            'icon' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if (array_key_exists('name', $data) && $data['name'] !== $category->name) {
            $category->name = $data['name'];
            $category->slug = $this->makeUniqueCategorySlug(Str::slug($data['name']), $category->id);
        }

        if (array_key_exists('image', $data)) {
            $category->image = $data['image'];
        }

        if (array_key_exists('icon', $data)) {
            $category->icon = $data['icon'];
        }

        if (array_key_exists('is_active', $data)) {
            $category->is_active = $data['is_active'];
        }

        if (array_key_exists('sort_order', $data)) {
            $category->sort_order = $data['sort_order'];
        }

        $category->save();

        return response()->json([
            'message' => 'Category updated successfully.',
            'data' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.'
        ]);
    }

    public function storeSubcategory(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'url', 'max:2048'],
            'icon' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $baseSlug = Str::slug($data['name']);
        $slug = $this->makeUniqueSubcategorySlug($baseSlug);

        $subcategory = Subcategory::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $slug,
            'image' => $data['image'] ?? null,
            'icon' => $data['icon'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return response()->json([
            'message' => 'Subcategory created successfully.',
            'data' => $subcategory
        ], 201);
    }

    public function updateSubcategory(Request $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);

        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'image' => ['nullable', 'url', 'max:2048'],
            'icon' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if (array_key_exists('category_id', $data)) {
            $subcategory->category_id = $data['category_id'];
        }

        if (array_key_exists('name', $data) && $data['name'] !== $subcategory->name) {
            $subcategory->name = $data['name'];
            $subcategory->slug = $this->makeUniqueSubcategorySlug(Str::slug($data['name']), $subcategory->id);
        }

        if (array_key_exists('image', $data)) {
            $subcategory->image = $data['image'];
        }

        if (array_key_exists('icon', $data)) {
            $subcategory->icon = $data['icon'];
        }

        if (array_key_exists('is_active', $data)) {
            $subcategory->is_active = $data['is_active'];
        }

        if (array_key_exists('sort_order', $data)) {
            $subcategory->sort_order = $data['sort_order'];
        }

        $subcategory->save();

        return response()->json([
            'message' => 'Subcategory updated successfully.',
            'data' => $subcategory
        ]);
    }

    public function destroySubcategory($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->delete();

        return response()->json([
            'message' => 'Subcategory deleted successfully.'
        ]);
    }

    protected function makeUniqueCategorySlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug ?: 'category';
        $original = $slug;
        $counter = 1;

        while (
            Category::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function makeUniqueSubcategorySlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug ?: 'subcategory';
        $original = $slug;
        $counter = 1;

        while (
            Subcategory::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}