<?php

namespace App\Http\Controllers\UserManajemen;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

public function index()
{
    $query = User::select('*');
    if (request()->ajax()) {
        return datatables()->of($query)
            ->addColumn('role_name', function ($query) {
                return $query->roles->pluck('name')->implode(', ');
            })
            ->addColumn('opsi', function ($query) {
                $encryptedId = Crypt::encrypt($query->id);
                $preview = route('users.show', $encryptedId);
                $edit = route('users.edit', $encryptedId);
                $hapus = route('users.destroy', $encryptedId);

                // Direct HTML rendering instead of using component
                return '<div class="d-inline-flex">
                    <div class="dropdown">
                        <a href="#" class="text-body" data-bs-toggle="dropdown">
                            <i class="ph-list"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="' . $preview . '" class="dropdown-item">
                                <i class="ph-detective me-2"></i>
                                Preview
                            </a>
                            <a href="' . $edit . '" class="dropdown-item">
                                <i class="ph-note-pencil me-2"></i>
                                Edit
                            </a>
                            <form action="' . $hapus . '" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="dropdown-item">
                                    <i class="ph-trash me-2"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>';
            })
            ->filterColumn('role_name', function ($query, $keyword) {
                $query->whereHas('roles', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->rawColumns(['opsi', 'role_name'])
            ->addIndexColumn()
            ->make(true);
    }
    return view('backend.users.index');
}

public function create()
{
    $roles = Role::whereIn('name', [
        'REDAKTUR', 'EDITOR','HUMAS','TAMU'
    ])->get();

    return view('backend.users.tambah_user', compact('roles'));
}

public function store(UserCreateRequest $request)
{
    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => Str::slug($request->name) . '-' . Str::random(6),
            'password' => Hash::make($request->password, [
                'rounds' => 12,  // Increased rounds for better security
                'memory' => 1024,
                'time' => 2,
                'threads' => 2,
            ]),
        ]);

        $user->assignRole($request->role);

        return redirect()->back()->with(['success' => 'Data User Berhasil Ditambahkan']);
    } catch (Exception $e) {
        report($e);
        return redirect()->back()->with([
            'failed' => 'Terjadi kesalahan sistem. Silakan coba lagi nanti.'
        ]);
    }
}

    public function edit($id)
    {
        try {
            // Decrypt the ID
            $decryptedId = Crypt::decrypt($id);
            $user = User::findOrFail($decryptedId);

            $roles = Role::whereIn('name', [
                'REDAKTUR', 'EDITOR', 'HUMAS_PERSIJA',
                'HUMAS_PENGELOLAAN', 'HUMAS_PERENCANAAN',
                'HUMAS_PENATAUSAHAAN', 'HUMAS_PENGADAAN',
                'TAMU'
            ])->get();

            $data = [
                'user' => $user,
                'role' => $roles,
                'olduser' => $user->roles->first(),
                'encrypted_id' => $id // Pass the encrypted ID back to view
            ];

            return view('backend.users.edit_user', compact('data'));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            report($e);
            return redirect()->route('users.index')->with([
                'failed' => 'ID User tidak valid.'
            ]);
        } catch (Exception $e) {
            report($e);
            return redirect()->route('users.index')->with([
                'failed' => 'Terjadi kesalahan saat mengambil data user.'
            ]);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            // Decrypt the ID
            $decryptedId = Crypt::decrypt($id);
            $user = User::findOrFail($decryptedId);

            // Update basic info
            $user->name = $request->name;
            $user->email = $request->email;

            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Update role if provided and ensure it's treated as integer
            if ($request->has('role')) {
                $roleId = (int) $request->role;
                $user->syncRoles([$roleId]);
            }

            return redirect()->route('users.index')->with([
                'success' => 'User berhasil diperbarui!'
            ]);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            report($e);
            return redirect()->route('users.index')->with([
                'failed' => 'ID User tidak valid.'
            ]);
        } catch (Exception $e) {
            report($e);
            return redirect()->route('users.index')->with([
                'failed' => 'Terjadi kesalahan sistem. Silahkan coba lagi nanti.'
            ]);
        }
    }

    public function destroy($id)
{
    try {
        // Decrypt the ID
        $decryptedId = Crypt::decrypt($id);
        $user = User::findOrFail($decryptedId);

        // Prevent self-deletion
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with([
                'failed' => 'Anda tidak dapat menghapus akun Anda sendiri.'
            ]);
        }

        // Remove role associations first
        $user->roles()->detach();

        // Delete the user
        $user->delete();

        return redirect()->route('users.index')->with([
            'success' => 'User berhasil dihapus!'
        ]);

    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
        report($e);
        return redirect()->route('users.index')->with([
            'failed' => 'ID User tidak valid.'
        ]);
    } catch (Exception $e) {
        report($e);
        return redirect()->route('users.index')->with([
            'failed' => 'Terjadi kesalahan sistem. Silakan coba lagi nanti.'
        ]);
    }
}
    private function generateActionButtons($encryptedId): string
    {
        $preview = route('users.show', $encryptedId);
        $edit = route('users.edit', $encryptedId);
        $hapus = route('users.destroy', $encryptedId);

        return view('components.action-buttons', compact('preview', 'edit', 'hapus'))->render();
    }
}
