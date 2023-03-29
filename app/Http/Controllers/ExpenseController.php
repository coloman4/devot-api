<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Retrieves a collection of expenses for the authenticated user.
     *
     * @OA\Get(
     *     path="/api/expenses",
     *     summary="Retrieves a collection of expenses for the authenticated user",
     *     tags={"Expenses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with a collection of expenses",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ExpenseResource")
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
        return ExpenseResource::collection($request->user()->expenses);
    }

    /**
     * Creates a new expense for the authenticated user.
     *
     * @OA\Post(
     *     path="/api/expenses",
     *     summary="Creates a new expense for the authenticated user",
     *     tags={"Expenses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreExpenseRequest")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with the newly created expense",
     *         @OA\JsonContent(ref="#/components/schemas/ExpenseResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=402,
     *         description="Insufficient funds",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Insufficient funds.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     */
    public function store(StoreExpenseRequest $request)
    {
        $inputData = $request->validated();
        $user = $request->user();
        if ($user->balance < $inputData['amount']) {
            return abort(402, 'Insufficient funds.');
        }

        $expenseData = [
            ...$inputData,
            'user_id' => $request->user()->id
        ];

        $newExpense = Expense::create($expenseData);
        $user->balance -= $newExpense->amount;
        $user->save();

        return new ExpenseResource($newExpense->refresh());
    }

    /**
     * Retrieves a specific expense for the authenticated user.
     *
     * @OA\Get(
     *     path="/api/expenses/{expense}",
     *     summary="Retrieves a specific expense for the authenticated user",
     *     tags={"Expenses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="expense",
     *         in="path",
     *         description="ID of the expense to retrieve",
     *         required=true,
     *         example="1",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with the expense details",
     *         @OA\JsonContent(ref="#/components/schemas/ExpenseResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Expense not found.")
     *         )
     *     )
     * )
     */
    public function show(Expense $expense, Request $request)
    {
        if ($expense->user->id != $request->user()->id) {
            return abort(401, 'Unauthorized action.');
        }
        return new ExpenseResource($expense);
    }

    /**
     * Updates an existing expense for the authenticated user.
     *
     * @OA\Put(
     *     path="/api/expenses/{expense}",
     *     summary="Updates an existing expense for the authenticated user",
     *     tags={"Expenses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="expense",
     *         in="path",
     *         description="ID of the expense to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="New expense details to update",
     *         @OA\JsonContent(ref="#/components/schemas/UpdateExpenseRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with the updated expense details",
     *         @OA\JsonContent(ref="#/components/schemas/ExpenseResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Expense not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error(s)",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\AdditionalProperties(
     *                     type="array",
     *                     @OA\Items(type="string", example="The :attribute field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $inputData = $request->validated();
        $user = $request->user();
        if ($expense->user->id != $user->id) {
            return abort(401, 'Unauthorized action.');
        }

        if ($request->has('amount')) {
            $amountDiff = $inputData['amount'] - $expense->amount;
            $user->balance += $amountDiff;
            $user->save();
        }

        $expense->update();
        return new ExpenseResource($expense);
    }

    /**
     * @OA\Delete(
     *     path="/api/expenses/{expense}",
     *     tags={"Expenses"},
     *     summary="Delete expense",
     *     description="Deletes a specific expense.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="expense",
     *         in="path",
     *         required=true,
     *         description="ID of the expense to be deleted",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense deleted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Expense deleted"
     *                 ),
     *                 @OA\Property(
     *                     property="item",
     *                     ref="#/components/schemas/ExpenseResource"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Unauthorized action."
     *             )
     *         )
     *     )
     * )
     */
    public function destroy(Request $request, Expense $expense)
    {
        if ($expense->user->id != $request->user()->id) {
            return abort(401, 'Unauthorized action.');
        }

        $expense->delete();

        $request->user()->balance += $expense->amount;
        $request->user()->save();

        return [
            'data' => [
                'message' => 'Category deleted',
                'item' => new ExpenseResource($expense)
            ]
        ];
    }
}
