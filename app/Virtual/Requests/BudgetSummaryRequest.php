<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Login Request",
 *      description="Login Request request body data",
 *      type="object"
 * )
 */
class BudgetSummaryRequest
{

    /**
     * @OA\Property(
     *      title="email",
     *      description="List data that is created after this date",
     *      example="21.03.2022",
     *      default="Start of the current month"
     * )
     *
     * @var string
     */
    public $start_date;

    /**
     * @OA\Property(
     *      title="end date",
     *      description="List data that is created before this date",
     *      example="21.03.2023",
     *      default="Today"
     * )
     *
     * @var string
     */
    public $end_date;

    /**
     * @OA\Property(
     *      title="categories",
     *      description="List of categories to apply",
     *      example="[1, 4, 55]",
     *      @OA\Items(
     *         type="integer"
     *       )
     * )
     *
     * @var array
     */
    public $categores;
}
