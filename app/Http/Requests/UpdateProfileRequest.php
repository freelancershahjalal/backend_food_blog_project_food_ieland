<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $userId = $this->user()->id;

        return [
            'name' => 'sometimes|required|string|max:255',
            // Ensure email is unique, but ignore the current user's own email
            'email' => 'sometimes|required|email|max:255|unique:users,email,'.$userId,
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
        ];
    }
}
