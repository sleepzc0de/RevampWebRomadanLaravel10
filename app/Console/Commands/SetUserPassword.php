<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\PasswordService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

/**
 * Ganti password sebuah akun dari server.
 *
 * Dibutuhkan karena akun ADMINISTRATOR sengaja tidak bisa diedit dari modul
 * User Manajemen dan aplikasi tidak punya halaman "ganti password sendiri".
 * Password diminta lewat prompt tersembunyi (tidak lewat argumen) supaya tidak
 * tersimpan di riwayat shell atau terlihat di daftar proses.
 */
class SetUserPassword extends Command
{
    protected $signature = 'user:set-password {email : Email akun yang password-nya diganti}';

    protected $description = 'Ganti password akun (termasuk ADMINISTRATOR yang tidak bisa diedit dari CMS)';

    public function handle(PasswordService $passwords): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('Akun dengan email itu tidak ditemukan.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Password baru');
        $confirmation = (string) $this->secret('Ulangi password baru');

        if ($password !== $confirmation) {
            $this->error('Konfirmasi tidak sama. Password tidak diubah.');

            return self::FAILURE;
        }

        $validator = Validator::make(
            ['password' => $password],
            ['password' => ['required', Password::min(12)->mixedCase()->letters()->numbers()->symbols()]]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }
            $this->line('Password tidak diubah.');

            return self::FAILURE;
        }

        $salt = $passwords->generateSalt();

        $user->forceFill([
            'password' => $passwords->hash($password, $salt),
            'salt' => $salt,
        ])->save();

        // save() tidak mencatat kolom password (sengaja dikecualikan dari log), jadi catat manual.
        activity('user')
            ->performedOn($user)
            ->log('Password diganti lewat artisan user:set-password');

        $this->info("Password {$user->email} berhasil diganti.");
        $this->line('Sesi login yang sedang aktif tidak otomatis berakhir; keluar-masuk ulang bila perlu.');

        return self::SUCCESS;
    }
}
