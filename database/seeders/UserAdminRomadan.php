<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\PasswordService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * Membuat akun ADMINISTRATOR awal.
 *
 * Tidak ada kredensial atau pepper yang ditulis di sini: password dibangkitkan
 * acak lalu ditampilkan SEKALI di konsol, dan hash dibuat lewat PasswordService
 * (pepper dibaca dari PASSWORD_PEPPER di .env) — skema yang sama dengan login.
 * Seeder ini idempoten: bila admin sudah ada, tidak melakukan apa pun.
 */
class UserAdminRomadan extends Seeder
{
    private const ADMIN_EMAIL = 'admin@romadan.kemenkeu.go.id';

    public function run(PasswordService $passwordService): void
    {
        if (User::where('email', self::ADMIN_EMAIL)->exists()) {
            $this->command?->warn('Admin '.self::ADMIN_EMAIL.' sudah ada — seeder dilewati.');

            return;
        }

        Role::firstOrCreate(['name' => 'ADMINISTRATOR']);

        $password = Str::password(24);
        $salt = $passwordService->generateSalt();

        $admin = User::create([
            'name' => 'Admin Romadan',
            'email' => self::ADMIN_EMAIL,
            'username' => Str::slug('Admin Romadan').'-'.Str::random(6),
            'password' => $passwordService->hash($password, $salt),
            'salt' => $salt,
        ]);

        $admin->assignRole('ADMINISTRATOR');

        $this->command?->warn('Akun admin dibuat: '.self::ADMIN_EMAIL);
        $this->command?->warn('Password awal (hanya tampil SEKALI, segera catat, ganti, dan aktifkan 2FA):');
        // escape(): password acak bisa memuat "<...>" atau "\" yang kalau tidak di-escape
        // ditafsirkan formatter konsol sebagai tag gaya sehingga yang tampil ≠ yang tersimpan.
        $this->command?->line(OutputFormatter::escape($password));
    }
}
