<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    protected $decryptedId;

    public function authorize()
    {
        try {
            $this->decryptedId = Crypt::decrypt($this->route('user'));
            // Tambahan validasi otorisasi jika diperlukan
            return true;
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return false;
        }
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'not_regex:/[<>]/'], // Tambahan validasi untuk mencegah XSS
            'email' => [
                'required',
                'email:rfc,dns', // Validasi format email yang lebih ketat
                Rule::unique('users', 'email')->ignore($this->decryptedId)
            ],
            'role' => [
                'required',
                'exists:roles,id',
                Rule::notIn(['ADMINISTRATOR']) // Mencegah perubahan ke role ADMINISTRATOR
            ],
            'password' => $this->passwordRules(),
        ];
    }

    protected function passwordRules()
    {
        if ($this->filled('password')) {
            return [
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ];
        }
        return ['nullable'];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.not_regex' => 'Nama tidak boleh mengandung karakter khusus',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'role.required' => 'Role harus dipilih',
            'role.exists' => 'Role tidak valid',
            'role.not_in' => 'Role yang dipilih tidak diizinkan',
            'password.min' => 'Password minimal 8 karakter',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil',
            'password.letters' => 'Password harus mengandung huruf',
            'password.numbers' => 'Password harus mengandung angka',
            'password.symbols' => 'Password harus mengandung simbol',
            'password.uncompromised' => 'Password yang anda masukkan terlalu umum atau pernah diretas. Silakan pilih password lain',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ];
    }

    protected function prepareForValidation()
    {
        try {
            $this->decryptedId = Crypt::decrypt($this->route('user'));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $this->decryptedId = null;
        }

        // Sanitasi input
        if ($this->has('name')) {
            $this->merge([
                'name' => strip_tags($this->name)
            ]);
        }
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Hapus password dari validated data jika kosong
        if (isset($validated['password']) && empty($validated['password'])) {
            unset($validated['password']);
        }

        return $validated;
    }
}
