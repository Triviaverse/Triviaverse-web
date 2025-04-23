<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        $userId = $this->user()->id;

        return [
            'name'            => ['required', 'string', 'max:255'],
            'email'           => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function attributes()
    {
        return [
            'profile_picture' => 'profilkép',
        ];
    }
}
