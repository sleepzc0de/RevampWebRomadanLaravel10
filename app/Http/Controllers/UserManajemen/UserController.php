<?php

namespace App\Http\Controllers\UserManajemen;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    private const PEPPER = 'mwdun-2937h-_(&)HG)*GOIUNJ)HG)*(&F*^D&^S%#$E^RGYOIBJNPOKMO}:}?}"?:>{K)OJ()*YT^&DRFYGUIHT&^R%E%EDYF2025';
    private const HASH_ALGO = 'sha256';
    private const HASH_ROUNDS = 12;
    private const ALLOWED_ROLES = [
        'REDAKTUR',
        'EDITOR',
        'HUMAS',
        'TAMU'
    ];

    public function index()
    {
        if (!request()->ajax()) {
            return view('backend.users.index');
        }

        $query = User::with('roles')->select(['id', 'name', 'email', 'username']);

        return datatables()->of($query)
            ->addColumn('role_name', function ($user) {
                return $user->roles->pluck('name')->implode(', ');
            })
            ->addColumn('opsi', function ($user) {
                if ($user->hasRole('ADMINISTRATOR')) {
                    return '<span class="badge bg-info">Protected</span>';
                }

                $encryptedId = Crypt::encrypt($user->id);
                return view('components.action-buttons', [
                    'edit' => route('users.edit', $encryptedId),
                    'hapus' => route('users.destroy', $encryptedId),
                    'encrypted_id' => $encryptedId
                ])->render();
            })
            ->filterColumn('role_name', function ($query, $keyword) {
                $query->whereHas('roles', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['opsi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        $roles = Role::whereIn('name', self::ALLOWED_ROLES)->get();
        return view('backend.users.tambah_user', compact('roles'));
    }

    public function store(UserCreateRequest $request)
    {
        try {
            Log::info('Received request data:', $request->safe()->except(['password', 'password_confirmation']));

            DB::beginTransaction();
            // Validate role
            $role = Role::findOrFail($request->role);
            Log::info('Found role:', ['role' => $role->name]);

            if (!in_array($role->name, self::ALLOWED_ROLES)) {
                Log::warning('Invalid role attempted:', ['role' => $role->name]);
                throw new Exception('Role yang dipilih tidak valid.');
            }

            // Create user
            $salt = $this->generateSalt();
            Log::info('Generated salt');

            $userData = [
                'name' => strip_tags($request->name),
                'email' => $request->email,
                'username' => $this->generateUniqueUsername($request->name),
                'password' => $this->hashPassword($request->password, $salt),
                'salt' => $salt,
            ];

            // Log user data tanpa password
            Log::info('Attempting to create user with:', [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'username' => $userData['username']
            ]);

            $user = User::create($userData);

            // Assign role
            $user->assignRole($role->name);

            DB::commit();

            Log::info('User created successfully:', ['user_id' => $user->id]);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil ditambahkan.');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('Database error:', ['error' => $e->getMessage()]);

            if ($e->getCode() == 23000) { // Duplicate entry
                return redirect()->back()
                    ->withInput()
                    ->with('failed', 'Email atau username sudah digunakan.');
            }

            return redirect()->back()
                ->withInput()
                ->with('failed', 'Gagal menyimpan data user. Silakan coba lagi.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating user:', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->withInput()
                ->with('failed', $e->getMessage());
        }
    }

    private function generateSalt(): string
    {
        return bin2hex(random_bytes(32));
    }

    private function hashPassword(string $password, string $salt): string
    {
        $peppered = hash_hmac(self::HASH_ALGO, $password . $salt, self::PEPPER);
        return Hash::make($peppered);
    }

    private function generateUniqueUsername(string $name): string
    {
        $baseUsername = Str::slug($name);
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '-' . $counter++;
        }

        return $username;
    }

    public function edit($id)
    {
        try {
            $decryptedId = Crypt::decrypt($id);
            $user = User::findOrFail($decryptedId);

            if ($user->hasRole('ADMINISTRATOR')) {
                return redirect()->route('users.index')
                    ->with('failed', 'User dengan role ADMINISTRATOR tidak dapat diedit.');
            }

            $roles = Role::whereIn('name', self::ALLOWED_ROLES)->get();
            $userRole = $user->roles->first();

            return view('backend.users.edit_user', compact('user', 'roles', 'userRole'));

        } catch (Exception $e) {
            Log::error('Error editing user:', ['error' => $e->getMessage()]);
            return redirect()->route('users.index')
                ->with('failed', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $decryptedId = Crypt::decrypt($id);
            $user = User::findOrFail($decryptedId);

            $user->name = strip_tags($request->name);
            $user->email = $request->email;

            if ($request->filled('password')) {
                $salt = $this->generateSalt();
                $user->password = $this->hashPassword($request->password, $salt);
                $user->salt = $salt;
            }

            $user->save();

            if ($request->has('role')) {
                $role = Role::findOrFail($request->role);
                if (!in_array($role->name, self::ALLOWED_ROLES)) {
                    throw new Exception('Role yang dipilih tidak valid.');
                }
                $user->syncRoles([$role->name]);
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User berhasil diperbarui.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating user:', ['error' => $e->getMessage()]);

            return redirect()->route('users.index')
                ->with('failed', 'Gagal memperbarui user. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $decryptedId = Crypt::decrypt($id);
            $user = User::findOrFail($decryptedId);

            if ($user->hasRole('ADMINISTRATOR')) {
                throw new Exception('User dengan role ADMINISTRATOR tidak dapat dihapus.');
            }

            if (auth()->id() === $user->id) {
                throw new Exception('Anda tidak dapat menghapus akun Anda sendiri.');
            }

            $user->roles()->detach();
            $user->delete();

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User berhasil dihapus.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user:', ['error' => $e->getMessage()]);

            return redirect()->route('users.index')
                ->with('failed', $e->getMessage());
        }
    }
}
