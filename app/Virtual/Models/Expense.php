<?php

namespace App\Virtual\Models;


/**
 * @OA\Schema(
 *     title="Expense",
 *     description="Expense",
 *     @OA\Xml(
 *         name="Expense"
 *     )
 * )
 */
class Expense {
    public $id;

    /**
     * @OA\Property(
     *      title="name",
     *      description="Expense name",
     *      example="Car expenses"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="amount",
     *      description="Expence amount",
     *      example="124.33"
     * )
     *
     * @var float
     */
    public $amount;

    /**
     * @OA\Property(
     *      title="description",
     *      description="Expense description",
     *      example="Car expenses for March."
     * )
     *
     * @var string
     */
    public $description;
    
    /**
     * @OA\Property(
     *      title="id",
     *      description="Expense category",
     * )
     *
     * @var \App\Virtual\Models\Category[]
     */
    public $category;

    
    /**
     * @OA\Property(
     *      title="id",
     *      description="Expense category",
     *      example= "27.03.2023"
     * )
     *
     * @var string
     */
    public $date;
}
