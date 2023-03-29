<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class BudgetSummaryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'categories' => 'array'
        ];
    }

    public function prepareForValidation()
    {
        $this->mergeIfMissing([
            'start_date'  => Carbon::now()->startOfMonth(),
            'end_date' => Carbon::today()
        ]);
    }
}
