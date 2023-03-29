<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetSummaryRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BudgetSummaryController extends Controller
{
    /**
     * Retrieves a summary of expenses based on a validated input.
     *
     * @param BudgetSummaryRequest $request The request object containing validated input.
     * @return array The response containing the total amount spent and expense data.
     *
     * @OA\Post(
     *      path="/api/budget/summary",
     *      summary="Retrieves a summary of expenses",
     *      tags={"Budget Summary"},
     *      description="Retrieves a summary of expenses based on a validated input. Returns the total amount spent and expense data.",
     *      @OA\RequestBody(
     *          description="The request body containing the validated input.",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/BudgetSummaryRequest")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Successful response",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="total_spent",
     *                      type="number",
     *                      example=1000.5,
     *                      description="The total amount spent on expenses."
     *                  ),
     *                  @OA\Property(
     *                      property="data",
     *                      type="array",
     *                      @OA\Items(ref="#/components/schemas/Expense"),
     *                      description="The array of expenses."
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="422",
     *          description="Unprocessable entity"
     *      ),
     *      @OA\Response(
     *          response="500",
     *          description="Server error"
     *      )
     * )
     */
    public function summary(BudgetSummaryRequest $request)
    {
        $validatedInput = $request->validated();

        $expenses = Expense::whereBetween(
            'expense_date',
            [
                new Carbon($validatedInput['start_date']),
                new Carbon($validatedInput['end_date'])
            ]
        );

        if ($request->has('categories') && !empty($validatedInput['categories'])) {
            $expenses->whereIn('category_id', $validatedInput['categories']);
        }

        $expenses = $expenses->get();

        return [
            'data' => [
                'total_spent' => $expenses->sum('amount'),
                'data' => $expenses
            ]
        ];
    }
}
