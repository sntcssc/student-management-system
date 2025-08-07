<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        // return auth::user()->hasRole('admin');
        return auth::check() && auth::user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user)],
            'password' => 'nullable|string|min:8|confirmed',
            'mobile' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'category' => 'nullable|in:Unreserved,SC,ST,OBC',
            'photo' => 'nullable|image|max:2048',
            'roles' => 'nullable|array',
            'bio' => 'nullable|string',
            'address' => 'nullable|string',
        ];
    }
}
