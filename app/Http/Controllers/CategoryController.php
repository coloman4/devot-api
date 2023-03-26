<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        dd($request->user()->categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $categoryData = [
            ...$request->validated(),
            'user_id' => $request->user()->id
        ];

        $newCategory = Category::create($categoryData);
        return new CategoryResource($newCategory);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if ($category->user->id != $request->user()->id) {
            return abort(403, 'Unauthorized action.');
        }

        $category->update($request->validated());
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Category $category)
    {
        if ($category->user->id != $request->user()->id) {
            return abort(403, 'Unauthorized action.');
        }

        $category->delete();
        return [
            'message' => 'Category deleted',
            'data' => new CategoryResource($category)
        ];
    }
}
