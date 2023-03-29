<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Store Expense Request",
 *      description="Store Expense request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreCategoryRequest
{

    /**
     * @OA\Property(
     *      title="name",
     *      description="Category name",
     *      example="Car expenses"
     * )
     *
     * @var string
     */
    public $name;
}
