<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::query()
            ->when(!empty($request->search), function ($query) use ($request) {
                return $query->where('name', $request->search);
            })
            ->paginate($request->per_page ?? 10);

        return responseJson(
            message: 'Categories retrieved successfully',
            data: CategoryResource::collection($categories)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return responseJson(
            message: 'Category created successfully',
            data: new CategoryResource($category)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);

        return responseJson(
            message: 'Category retrieved successfully',
            data: new CategoryResource($category)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::findOrFail($id);

        $category->update($request->validated());

        return responseJson(
            message: 'Category updated successfully',
            data: new CategoryResource($category)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return responseJson(
            message: 'Category deleted successfully',
            data: new CategoryResource($category)
        );
    }
}
