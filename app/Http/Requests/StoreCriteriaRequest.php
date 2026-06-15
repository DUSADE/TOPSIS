<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Use middleware for auth
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:10', 'unique:criterias,code'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:BENEFIT,COST'],
            'weight' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
