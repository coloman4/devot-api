<?php 

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Category",
 *     description="Category",
 *     @OA\Xml(
 *         name="Category"
 *     )
 * )
 */
class Category {
    /**
     * @OA\Property(
     *      title="id",
     *      description="Category Id",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $id;

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