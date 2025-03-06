<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateAdminRequest extends StoreAdminRequest
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

        unset($rules['photo']);

        if ($this->hasFile('photo')) {
            $rules['photo'] = ['image', 'mimes:png,jpg,jpeg', 'max:2048'];
        }

        $rules['email'] = ['required', 'string', 'email', 'max:255', 'min:4', Rule::unique('users', 'email')->ignore($this->email)];
        $rules['username'] = ['required', 'string', 'max:15', Rule::unique('users', 'username')->ignore($this->username)];

        return $rules;
    }
}
