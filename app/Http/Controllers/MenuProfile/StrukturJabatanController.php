<?php

namespace App\Http\Controllers\MenuProfile;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuProfile\StrukturJabatanModel;
use App\Models\backend\MenuProfile\StrukturPejabatModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class StrukturJabatanController extends Controller
{
    /**
     * Tampilkan bagan struktur jabatan (seluruh node kecil jumlahnya,
     * jadi diambil flat lalu dirakit jadi tree di PHP — hindari N+1
     * dari eager-load rekursif).
     */
    public function index()
    {
        $nodes = StrukturJabatanModel::with('pejabat')
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $byParent = $nodes->groupBy('parent_id');

        $root = $nodes->firstWhere('parent_id', null);

        return view('backend.struktur-jabatan.index', [
            'root' => $root,
            'byParent' => $byParent,
        ]);
    }

    public function storeJabatan(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'nama_jabatan' => [
                    'required',
                    'max:255',
                    'regex:/^[^<>]*$/',
                ],
                'parent_id' => [
                    'nullable',
                    'string',
                    function ($attribute, $value, $fail) {
                        try {
                            $id = decrypt($value);
                        } catch (Exception $e) {
                            $fail('Jabatan induk tidak valid.');

                            return;
                        }
                        if (! StrukturJabatanModel::whereKey($id)->exists()) {
                            $fail('Jabatan induk tidak ditemukan.');
                        }
                    },
                ],
                'urutan' => ['nullable', 'integer'],
            ], [
                'nama_jabatan.regex' => 'Nama jabatan tidak boleh mengandung tag HTML',
            ]);

            if (empty($validated['parent_id']) && StrukturJabatanModel::whereNull('parent_id')->exists()) {
                DB::rollBack();

                return redirect()->back()->withErrors(['parent_id' => 'Sudah ada jabatan tertinggi. Tambahkan jabatan baru sebagai sub-jabatan.'])->withInput();
            }

            StrukturJabatanModel::create([
                'nama_jabatan' => strip_tags($validated['nama_jabatan']),
                'parent_id' => ! empty($validated['parent_id']) ? decrypt($validated['parent_id']) : null,
                'urutan' => $validated['urutan'] ?? 0,
            ]);

            DB::commit();
            Cache::forget('struktur_jabatan_tree');
            Log::info('Jabatan created successfully');

            return redirect()->route('struktur-jabatan.index')->with('success', 'Jabatan berhasil ditambahkan!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating jabatan', ['error' => $e->getMessage()]);

            return redirect()->back()->with('failed', 'Jabatan gagal ditambahkan!');
        }
    }

    public function updateJabatan(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $decryptedId = decrypt($id);

            $validated = $request->validate([
                'nama_jabatan' => ['required', 'max:255', 'regex:/^[^<>]*$/'],
                'urutan' => ['nullable', 'integer'],
            ], [
                'nama_jabatan.regex' => 'Nama jabatan tidak boleh mengandung tag HTML',
            ]);

            $jabatan = StrukturJabatanModel::findOrFail($decryptedId);
            $jabatan->update([
                'nama_jabatan' => strip_tags($validated['nama_jabatan']),
                'urutan' => $validated['urutan'] ?? $jabatan->urutan,
            ]);

            DB::commit();
            Cache::forget('struktur_jabatan_tree');
            Log::info('Jabatan updated successfully', ['id' => $decryptedId]);

            return redirect()->route('struktur-jabatan.index')->with('success', 'Jabatan berhasil diupdate!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating jabatan', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->route('struktur-jabatan.index')->with('failed', 'Jabatan gagal diupdate!');
        }
    }

    public function destroyJabatan(string $id)
    {
        try {
            DB::beginTransaction();
            $decryptedId = decrypt($id);
            $jabatan = StrukturJabatanModel::findOrFail($decryptedId);

            if ($jabatan->children()->exists() || $jabatan->pejabat()->exists()) {
                DB::rollBack();

                return redirect()->route('struktur-jabatan.index')->with('failed', 'Jabatan tidak bisa dihapus karena masih memiliki sub-jabatan atau pejabat.');
            }

            $jabatan->delete();

            DB::commit();
            Cache::forget('struktur_jabatan_tree');
            Log::info('Jabatan deleted successfully', ['id' => $decryptedId]);

            return redirect()->route('struktur-jabatan.index')->with('success', 'Jabatan berhasil dihapus!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting jabatan', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->route('struktur-jabatan.index')->with('failed', 'Jabatan yang dihapus tidak ada!');
        }
    }

    public function storePejabat(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'jabatan_id' => ['required', 'string'],
                'nama' => ['required', 'max:255', 'regex:/^[^<>]*$/'],
                'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:20480'],
                'urutan' => ['nullable', 'integer'],
            ], [
                'nama.regex' => 'Nama tidak boleh mengandung tag HTML',
                'foto.max' => 'Ukuran foto tidak boleh lebih dari 20MB',
            ]);

            $jabatanId = decrypt($validated['jabatan_id']);
            if (! StrukturJabatanModel::whereKey($jabatanId)->exists()) {
                DB::rollBack();

                return redirect()->back()->withErrors(['jabatan_id' => 'Jabatan tidak ditemukan.'])->withInput();
            }

            $fotoName = null;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $foto->storeAs('public/romadan_gambar_web', $foto->hashName());
                $fotoName = $foto->hashName();
            }

            StrukturPejabatModel::create([
                'jabatan_id' => $jabatanId,
                'nama' => strip_tags($validated['nama']),
                'foto' => $fotoName,
                'urutan' => $validated['urutan'] ?? 0,
            ]);

            DB::commit();
            Cache::forget('struktur_jabatan_tree');
            Log::info('Pejabat created successfully');

            return redirect()->route('struktur-jabatan.index')->with('success', 'Pejabat berhasil ditambahkan!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating pejabat', ['error' => $e->getMessage()]);

            return redirect()->back()->with('failed', 'Pejabat gagal ditambahkan!');
        }
    }

    public function updatePejabat(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $decryptedId = decrypt($id);

            $validated = $request->validate([
                'nama' => ['required', 'max:255', 'regex:/^[^<>]*$/'],
                'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:20480'],
                'urutan' => ['nullable', 'integer'],
            ], [
                'nama.regex' => 'Nama tidak boleh mengandung tag HTML',
                'foto.max' => 'Ukuran foto tidak boleh lebih dari 20MB',
            ]);

            $pejabat = StrukturPejabatModel::findOrFail($decryptedId);

            $data = [
                'nama' => strip_tags($validated['nama']),
                'urutan' => $validated['urutan'] ?? $pejabat->urutan,
            ];

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $foto->storeAs('public/romadan_gambar_web', $foto->hashName());

                if ($pejabat->foto) {
                    File::delete(public_path('storage/romadan_gambar_web/').$pejabat->foto);
                }

                $data['foto'] = $foto->hashName();
            }

            $pejabat->update($data);

            DB::commit();
            Cache::forget('struktur_jabatan_tree');
            Log::info('Pejabat updated successfully', ['id' => $decryptedId]);

            return redirect()->route('struktur-jabatan.index')->with('success', 'Pejabat berhasil diupdate!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating pejabat', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->route('struktur-jabatan.index')->with('failed', 'Pejabat gagal diupdate!');
        }
    }

    public function destroyPejabat(string $id)
    {
        try {
            DB::beginTransaction();
            $decryptedId = decrypt($id);
            $pejabat = StrukturPejabatModel::findOrFail($decryptedId);

            if ($pejabat->foto) {
                File::delete(public_path('storage/romadan_gambar_web/').$pejabat->foto);
            }

            $pejabat->delete();

            DB::commit();
            Cache::forget('struktur_jabatan_tree');
            Log::info('Pejabat deleted successfully', ['id' => $decryptedId]);

            return redirect()->route('struktur-jabatan.index')->with('success', 'Pejabat berhasil dihapus!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting pejabat', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->route('struktur-jabatan.index')->with('failed', 'Pejabat yang dihapus tidak ada!');
        }
    }
}
