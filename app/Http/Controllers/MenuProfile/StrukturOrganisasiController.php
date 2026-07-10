<?php

namespace App\Http\Controllers\MenuProfile;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuProfile\StrukturOrganisasiImageModel;
use App\Models\backend\MenuProfile\StrukturOrganisasiModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class StrukturOrganisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = StrukturOrganisasiModel::all();
        $query = StrukturOrganisasiModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('image_struktur', function ($query) {
                    $url = asset('storage/romadan_gambar_web/'.$query->image);

                    return '<a href="'.$url.'"><img src="'.$url.'" border="0" width="100" class="img-rounded" align="center""/></a>';
                })
                ->addColumn('additional_images_count', function ($query) {
                    $count = $query->additionalImages()->count();

                    return $count > 0 ? '<span class="badge bg-primary">'.$count.'</span>' : '-';
                })
                ->addColumn('has_video', function ($query) {
                    return ! empty($query->video_url) ? '<i class="ph-play-circle text-success"></i> Ya' : '<i class="ph-x-circle text-danger"></i> Tidak';
                })
                ->addColumn('layout_type', function ($query) {
                    $types = [
                        'standard' => '<span class="badge bg-secondary">Standar</span>',
                        'wide' => '<span class="badge bg-info">Lebar</span>',
                        'compact' => '<span class="badge bg-warning">Kompak</span>',
                    ];

                    return $types[$query->layout_type] ?? $types['standard'];
                })
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'edit' => route('struktur-organisasi.edit', encrypt($query->id)),
                        'destroy' => route('struktur-organisasi.destroy', encrypt($query->id)),
                    ])->render();
                })
                ->editColumn('created_at', function ($query) {
                    return date('d-M-Y H:i:s', strtotime($query->created_at));
                })
                ->rawColumns(['opsi', 'image_struktur', 'additional_images_count', 'has_video', 'layout_type'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.struktur.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.struktur.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // VALIDASI DATA
            $validated = $request->validate([
                'judul' => [
                    'required',
                    'max:255',
                    'unique:struktur_organisasi,judul',
                    'regex:/^[^<>]*$/', // Prevents HTML tags
                    function ($attribute, $value, $fail) {
                        if (strip_tags($value) !== $value) {
                            $fail('The '.$attribute.' field cannot contain HTML tags.');
                        }
                    },
                ],
                'struktur' => [
                    'required',
                    'min:10',
                    'max:10000',
                ],
                'image' => 'required|image|mimes:jpeg,png,jpg|max:20480',
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:20480',
                'video_url' => 'nullable|url|max:255',
                'layout_type' => 'required|in:standard,wide,compact',
            ], [
                'judul.regex' => 'Judul tidak boleh mengandung tag HTML',
                'additional_images.*.mimes' => 'Gambar tambahan hanya diperbolehkan berekstensi JPEG, JPG, PNG, SVG',
                'video_url.url' => 'URL video harus valid',
                'image.max' => 'Ukuran gambar utama tidak boleh lebih dari 20MB',
                'additional_images.*.max' => 'Ukuran gambar tambahan tidak boleh lebih dari 20MB',
            ]);

            $validated['judul'] = strip_tags($validated['judul']);

            // UPLOAD MAIN IMAGE
            $image = $request->file('image');
            $image->storeAs('public/romadan_gambar_web', $image->hashName());

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $validated['judul'],
                'struktur' => $validated['struktur'],
                'image' => $image->hashName(),
                'video_url' => $validated['video_url'] ?? null,
                'layout_type' => $validated['layout_type'],
            ];

            // Create struktur organisasi
            $struktur = StrukturOrganisasiModel::create($data);

            // Handle additional images if any
            if ($request->hasFile('additional_images')) {
                $sortOrder = 1;
                foreach ($request->file('additional_images') as $additionalImage) {
                    $additionalImage->storeAs('public/romadan_gambar_web', $additionalImage->hashName());

                    StrukturOrganisasiImageModel::create([
                        'struktur_organisasi_id' => $struktur->id,
                        'image_path' => $additionalImage->hashName(),
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }

            DB::commit();
            Log::info('Struktur Organisasi created successfully', ['id' => $struktur->id]);

            // redirect to index
            return redirect()->route('struktur-organisasi.index')->with(['success' => 'Struktur Organisasi Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating Struktur Organisasi', ['error' => $e->getMessage()]);

            return redirect()->back()->with(['failed' => 'Struktur Organisasi Gagal Ditambahkan!']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $struktur = StrukturOrganisasiModel::with(['additionalImages' => function ($query) {
                $query->orderBy('sort_order', 'asc');
            }])->findOrFail(decrypt($id));

            return view('backend.struktur.edit', compact('struktur'));
        } catch (Exception $e) {
            Log::error('Error editing Struktur Organisasi', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->route('struktur-organisasi.index')->with(['failed' => 'Struktur Organisasi tidak ditemukan']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $decryptedId = decrypt($id);

            Log::info('Starting update for Struktur Organisasi', [
                'id' => $decryptedId,
                'image_order' => $request->image_order,
            ]);

            // VALIDASI DATA
            $validated = $request->validate([
                'judul' => [
                    'required',
                    'max:255',
                    'regex:/^[^<>]*$/', // Prevents HTML tags
                    function ($attribute, $value, $fail) use ($decryptedId) {
                        if (strip_tags($value) !== $value) {
                            $fail('The '.$attribute.' field cannot contain HTML tags.');
                        }

                        // Check if judul already exists for different record
                        $exists = StrukturOrganisasiModel::where('judul', $value)
                            ->where('id', '!=', $decryptedId)
                            ->exists();
                        if ($exists) {
                            $fail('The '.$attribute.' has already been taken.');
                        }
                    },
                ],
                'struktur' => [
                    'required',
                    'max:10000',
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:20480',
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:20480',
                'video_url' => 'nullable|url|max:255',
                'layout_type' => 'required|in:standard,wide,compact',
                'remove_images' => 'nullable|array',
                'remove_images.*' => 'nullable|integer',
                'image_order' => 'nullable|array',
                'image_order.*' => 'nullable|integer',
            ], [
                'judul.regex' => 'Judul tidak boleh mengandung tag HTML',
                'additional_images.*.mimes' => 'Gambar tambahan hanya diperbolehkan berekstensi JPEG, JPG, PNG, SVG',
                'video_url.url' => 'URL video harus valid',
            ]);

            $validated['judul'] = strip_tags($validated['judul']);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $validated['judul'],
                'struktur' => $validated['struktur'],
                'video_url' => $validated['video_url'] ?? null,
                'layout_type' => $validated['layout_type'],
            ];

            // Update main image if provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image->storeAs('public/romadan_gambar_web', $image->hashName());

                $data_gambar = StrukturOrganisasiModel::findOrFail($decryptedId);
                File::delete(public_path('storage/romadan_gambar_web/').$data_gambar->image);

                $data['image'] = $image->hashName();
            }

            // Update the struktur data
            $struktur = StrukturOrganisasiModel::findOrFail($decryptedId);
            $struktur->update($data);

            // Handle removing images if requested
            if ($request->has('remove_images') && is_array($request->remove_images)) {
                $imagesToRemove = StrukturOrganisasiImageModel::whereIn('id', $request->remove_images)
                    ->where('struktur_organisasi_id', $struktur->id)
                    ->get();

                foreach ($imagesToRemove as $image) {
                    Log::info('Removing image', ['image_id' => $image->id, 'image_path' => $image->image_path]);
                    File::delete(public_path('storage/romadan_gambar_web/').$image->image_path);
                    $image->delete();
                }
            }

            // Handle additional images if any
            if ($request->hasFile('additional_images')) {
                $currentMaxSortOrder = $struktur->additionalImages()
                    ->select(DB::raw('MAX(struktur_organisasi_images.sort_order) as max_order'))
                    ->first()
                    ->max_order ?? 0;

                $sortOrder = $currentMaxSortOrder + 1;

                foreach ($request->file('additional_images') as $additionalImage) {
                    $additionalImage->storeAs('public/romadan_gambar_web', $additionalImage->hashName());

                    StrukturOrganisasiImageModel::create([
                        'struktur_organisasi_id' => $struktur->id,
                        'image_path' => $additionalImage->hashName(),
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }

            // Handle reordering if provided
            if ($request->has('image_order') && is_array($request->image_order)) {
                foreach ($request->image_order as $id => $order) {
                    Log::info('Updating image order', ['image_id' => $id, 'new_order' => $order]);

                    StrukturOrganisasiImageModel::where('id', $id)
                        ->where('struktur_organisasi_id', $struktur->id)
                        ->update(['sort_order' => $order]);
                }
            }

            DB::commit();
            Log::info('Struktur Organisasi updated successfully', ['id' => $struktur->id]);

            return redirect()->route('struktur-organisasi.index')->with('success', 'Struktur organisasi berhasil diupdate!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating Struktur Organisasi', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->route('struktur-organisasi.index')->with(['failed' => 'Struktur organisasi Gagal Di Update!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $struktur = StrukturOrganisasiModel::with('additionalImages')->findOrFail(decrypt($id));

            // Delete main image
            File::delete(public_path('storage/romadan_gambar_web/').$struktur->image);

            // Delete all additional images
            foreach ($struktur->additionalImages as $image) {
                File::delete(public_path('storage/romadan_gambar_web/').$image->image_path);
            }

            // The related images will be automatically deleted due to cascade delete in migration
            $struktur->delete();

            DB::commit();
            Log::info('Struktur Organisasi deleted successfully', ['id' => decrypt($id)]);

            return redirect()->route('struktur-organisasi.index')->with('success', 'Struktur Organisasi berhasil dihapus!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Struktur Organisasi', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->route('struktur-organisasi.index')->with(['failed' => 'Struktur Organisasi Yang Dihapus Tidak Ada !']);
        }
    }
}
