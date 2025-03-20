<?php

namespace App\Http\Requests\Submission;

use App\Models\Category;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends StoreCategoryRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $isNameExist = Category::where('name', $this->name)->exists();

        unset($rules['name']);

        if ($this->name && !$isNameExist) {
            $rules['name'] = ['required', 'string', 'min:2', 'max:255', Rule::unique('categories')];
        }

        return $rules;
    }
}
