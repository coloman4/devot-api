<?php 

namespace App\Virtual\Resources;

/**
 * @OA\Schema(
 *      title="ExpenseResource",
 *      description="Resource category",
 *      @OA\Xml(
 *         name="ExpenseResource"
 *     )
 * )
 */
class ExpenseResource {
    /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Virtual\Models\Expense[]
     */
    private $data;
}