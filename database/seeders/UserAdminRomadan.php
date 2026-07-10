<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserAdminRomadan extends Seeder
{
    private const PEPPER = 'mwdun-2937h-_(&)HG)*GOIUNJ)HG)*(&F*^D&^S%#$E^RGYOIBJNPOKMO}:}?}"?:>{K)OJ()*YT^&DRFYGUIHT&^R%E%EDYF2025'; // Pastikan nilai ini sama dengan yang ada di UserController

    private const HASH_ALGO = 'sha256';

    private const HASH_ROUNDS = 12;

    private function generateSalt(): string
    {
        return bin2hex(random_bytes(32));
    }

    private function hashPassword(string $password, string $salt): string
    {
        $peppered = hash_hmac(self::HASH_ALGO, $password.$salt, self::PEPPER);

        return Hash::make($peppered, [
            'rounds' => self::HASH_ROUNDS,
            'memory' => 1024,
            'time' => 2,
            'threads' => 2,
        ]);
    }

    public function run(): void
    {
        // Buat role ADMINISTRATOR jika belum ada
        $adminRole = Role::firstOrCreate(['name' => 'ADMINISTRATOR']);

        // Generate salt untuk admin
        $salt = $this->generateSalt();

        // Generate username unik
        $username = Str::slug('Admin Romadan').'-'.Str::random(6);

        // Buat user admin dengan salt
        $admin = User::create([
            'name' => 'Admin Romadan',
            'email' => 'admin@romadan.kemenkeu.go.id',
            'username' => $username,
            'password' => $this->hashPassword('4dM!nR00M4D4N2O24!))(!((^!#!$!(', $salt),
            'salt' => $salt,
        ]);

        // Assign role ADMINISTRATOR ke user admin
        $admin->assignRole('ADMINISTRATOR');
    }
}
