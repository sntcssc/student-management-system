<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth::id(),
            'mobile' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'category' => 'nullable|in:Unreserved,SC,ST,OBC',
            'photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
            'address' => 'nullable|string',
        ];
    }
}
