<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    protected $decryptedId;

    protected $targetUser;

    public function authorize()
    {
        try {
            // Parameter route resource users dinamai 'id' (lihat
            // Route::resource(...)->parameters([...=>'id']) di routes/web.php);
            // membaca 'user' selalu null → DecryptException → update selalu
            // ditolak "unauthorized".
            $this->decryptedId = Crypt::decrypt($this->route('id'));
            $this->targetUser = User::findOrFail($this->decryptedId);

            if ($this->targetUser->hasRole('ADMINISTRATOR')) {
                return false;
            }

            return true;
        } catch (DecryptException $e) {
            return false;
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'not_regex:/[<>]/',
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->decryptedId),
            ],
            'role' => [
                'required',
                'exists:roles,id',
                Rule::notIn(['ADMINISTRATOR']),
            ],
            'password' => $this->passwordRules(),
        ];
    }

    protected function passwordRules()
    {
        if ($this->filled('password')) {
            return [
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                function ($attribute, $value, $fail) {
                    $personalInfo = [
                        $this->input('name'),
                        $this->input('email'),
                        $this->targetUser->username,
                    ];

                    foreach ($personalInfo as $info) {
                        if ($info && stripos($value, $info) !== false) {
                            $fail('Password tidak boleh mengandung informasi personal.');
                        }
                    }
                },
            ];
        }

        return ['nullable'];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.not_regex' => 'Nama tidak boleh mengandung karakter khusus',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'email.max' => 'Email maksimal 255 karakter',
            'role.required' => 'Role harus dipilih',
            'role.exists' => 'Role tidak valid',
            'role.not_in' => 'Role yang dipilih tidak diizinkan',
            'password.min' => 'Password minimal 8 karakter',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil',
            'password.letters' => 'Password harus mengandung huruf',
            'password.numbers' => 'Password harus mengandung angka',
            'password.symbols' => 'Password harus mengandung simbol',
            'password.uncompromised' => 'Password yang anda masukkan terlalu umum atau pernah diretas. Silakan pilih password lain',
        ];
    }

    protected function prepareForValidation()
    {
        try {
            $this->decryptedId = Crypt::decrypt($this->route('id'));
            $this->targetUser = User::findOrFail($this->decryptedId);
        } catch (\Exception $e) {
            $this->decryptedId = null;
            $this->targetUser = null;
        }

        if ($this->has('name')) {
            $this->merge([
                'name' => trim(strip_tags($this->name)),
            ]);
        }

        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email)),
            ]);
        }
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (isset($validated['password']) && empty($validated['password'])) {
            unset($validated['password']);
        }

        return $validated;
    }
}
