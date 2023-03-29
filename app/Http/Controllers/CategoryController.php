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
     * Retrieves a collection of categories for the authenticated user.
     *
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Retrieves a collection of categories for the authenticated user",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with a collection of categories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CategoryResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        return CategoryResource::collection($request->user()->categories);
    }

    /**
     * Create a new category.
     *
     * @OA\Post(
     *     path="api/categories",
     *     summary="Create a new category",
     *     description="Create a new category with the given details.",
     *     operationId="store",
     *     tags={"Categories"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category details",
     *         @OA\JsonContent(ref="#/components/schemas/StoreCategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The created category",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error(s)",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error.")
     *         )
     *     )
     * )
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
     * Display the specified category.
     *
     * @OA\Get(
     *      path="/api/categories/{category}",
     *      summary="Display a category",
     *      tags={"Categories"},
     *      security={{ "apiAuth": {} }},
     *      @OA\Parameter(
     *          name="category",
     *          description="Category ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Category details retrieved successfully",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/CategoryResource"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="Unauthorized action",
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Category not found",
     *      )
     * )
     */
    public function show(Category $category, Request $request)
    {
        if ($category->user->id != $request->user()->id) {
            return abort(401, 'Unauthorized action.');
        }

        return new CategoryResource($category);
    }

    /**
     * Update a category.
     *
     * @OA\Put(
     *     path="/api/categories/{category}",
     *     operationId="updateCategory",
     *     tags={"Categories"},
     *     summary="Update a category",
     *     description="Updates a category based on the ID provided in the URL.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="ID of category to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON payload containing category data",
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 description="The updated category",
     *                 ref="#/components/schemas/CategoryResource",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized action",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 description="Unauthorized action",
     *                 type="string"
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 description="Category not found",
     *                 type="string"
     *             ),
     *         ),
     *     ),
     * )
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if ($category->user->id != $request->user()->id) {
            return abort(401, 'Unauthorized action.');
        }

        $category->update($request->validated());
        return new CategoryResource($category);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{category}",
     *     summary="Delete a category",
     *     description="Delete a category by ID.",
     *     operationId="deleteCategory",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="ID of the category to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Category deleted"
     *                 ),
     *                 @OA\Property(
     *                     property="item",
     *                     ref="#/components/schemas/CategoryResource"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized action"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function destroy(Request $request, Category $category)
    {
        if ($category->user->id != $request->user()->id) {
            return abort(401, 'Unauthorized action.');
        }

        $category->delete();
        return [
            'data' => [
                'message' => 'Category deleted',
                'item' => new CategoryResource($category)
            ]
        ];
    }
}
