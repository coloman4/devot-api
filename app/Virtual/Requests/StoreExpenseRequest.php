<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Store Expense Request",
 *      description="Store Expense request body data",
 *      type="object",
 *      required={"name", "category_id", "amount"}
 * )
 */
class StoreExpenseRequest
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

    /**
     * @OA\Property(
     *      title="email",
     *      description="User email",
     *      example="myemail@gmail.com"
     * )
     *
     * @var string
     */

    /**
     * @OA\Property(
     *      title="Category id",
     *      description="Category id",
     *      example="1"
     * )
     *
     * @var int
     */
    public $category_id;

    /**
     * @OA\Property(
     *      title="amount",
     *      description="Amount",
     *      example="77.1"
     * )
     *
     * @var float
     */
    public $amount;

    /**
     * @OA\Property(
     *      title="description",
     *      description="Expense description",
     *      example="Some expense description....."
     * )
     *
     * @var string
     */
    public $description;
}
