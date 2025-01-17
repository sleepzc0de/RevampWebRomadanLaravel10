<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'exists:roles,id'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()        // Require both uppercase and lowercase letters
                    ->letters()          // Require at least one letter
                    ->numbers()          // Require at least one number
                    ->symbols()          // Require at least one symbol
                    ->uncompromised(),   // Check if password hasn't been compromised in data leaks
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'role.required' => 'Role harus dipilih',
            'role.exists' => 'Role tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil',
            'password.letters' => 'Password harus mengandung huruf',
            'password.numbers' => 'Password harus mengandung angka',
            'password.symbols' => 'Password harus mengandung simbol',
            'password.uncompromised' => 'Password yang anda masukkan terlalu umum atau pernah diretas. Silakan pilih password lain',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ];
    }
}
