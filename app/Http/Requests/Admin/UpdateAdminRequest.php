<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
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
        $isEmailExist = User::where('email', $this->email)->exists();
        $isUsernameExist = User::where('username', $this->username)->exists();

        unset($rules['photo'], $rules['email'], $rules['username'], $rules['password']);

        if ($this->hasFile('photo')) {
            $rules['photo'] = ['image', 'mimes:png,jpg,jpeg', 'max:2048'];
        }

        if ($this->email && !$isEmailExist) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'min:4', Rule::unique('users', 'email')->ignore($this)];
        }

        if ($this->username && !$isUsernameExist) {
            $rules['username'] = ['required', 'string', 'max:15', Rule::unique('users', 'username')->ignore($this)];
        }

        if ($this->password) {
            $rules['password'] = [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', // At least 1 uppercase, 1 lowercase, 1 digit, 1 special character
                'confirmed',
            ];
        }

        return $rules;
    }
}
