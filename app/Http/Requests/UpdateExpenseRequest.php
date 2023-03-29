<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|nullable',
            'category_id' => 'exists:categories,id|nullable',
            'amount' => 'numeric|nullable|min:0',
            'description' => 'max:255|nullable'
        ];
    }
}
