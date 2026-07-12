<?php

namespace App\Http\Controllers\MenuPublikasi;

use App\Helpers\ExcelExportHelper;
use App\Http\Controllers\Controller;
use App\Models\backend\MenuPublikasi\PublikasiModel;
use App\Models\backend\RefKategori;
use App\Models\backend\RefStatus;
use App\Models\backend\RefTipe;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $query = PublikasiModel::with(['kategori', 'status', 'tipe', 'images'])->select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('image_publikasi', function ($query) {
                    // Gunakan relasi yang sudah di-eager-load (hindari N+1)
                    $image = $query->images->firstWhere('is_primary', true)
                        ?? $query->images->first();

                    if ($image) {
                        $url = asset('storage/romadan_gambar_web/'.$image->image_path);

                        return '<a href="'.$url.'"><img src="'.$url.'" border="0" width="100" class="img-rounded" align="center""/></a>';
                    }

                    return '<span>No image</span>';
                })
                ->addColumn('file_publikasi', function ($query) {
                    $judul = strlen($query->judul) > 10 ? substr($query->judul, 0, 10).'...' : $query->judul;

                    if ($query->file) {
                        $url = asset('storage/romadan_file_web/'.$query->file);

                        return '<a href="'.$url.'" target="_blank" title="'.e($query->judul).'">'.$judul.'</a>';
                    }

                    return '<span title="'.e($query->judul).'">'.$judul.'</span>';
                })
                ->addColumn('opsi', function ($query) {
                    $encryptedId = encrypt($query->id);

                    return view('components.datatable-actions', [
                        'preview' => route('publikasi.show', $encryptedId),
                        'edit' => route('publikasi.edit', $encryptedId),
                        'destroy' => route('publikasi.destroy', $encryptedId),
                    ])->render();
                })
                ->editColumn('created_at', function ($query) {
                    return date('d-M-Y H:i:s', strtotime($query->created_at));
                })
                ->rawColumns(['opsi', 'image_publikasi', 'file_publikasi'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.publikasi.index');
    }

    /**
     * Ekspor daftar publikasi (maks 5000 baris terbaru) ke Excel.
     */
    public function exportExcel(): StreamedResponse
    {
        $rows = PublikasiModel::with(['kategori', 'status', 'tipe'])
            ->latest()
            ->limit(5000)
            ->get()
            ->map(fn ($publikasi) => [
                $publikasi->judul,
                optional($publikasi->tipe)->nama_tipe,
                optional($publikasi->kategori)->nama_kategori,
                optional($publikasi->status)->nama_status,
                $publikasi->penulis,
                $publikasi->pengedit,
                $publikasi->views,
                optional($publikasi->created_at)->format('Y-m-d H:i:s'),
            ]);

        return ExcelExportHelper::stream(
            'publikasi-'.now()->format('Ymd-His').'.xlsx',
            ['Judul', 'Tipe', 'Kategori', 'Status', 'Penulis', 'Pengedit', 'Views', 'Tanggal Dibuat'],
            $rows
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // $kategori = ModelsRef_kategori::get();
        $kategori = RefKategori::get();
        $tipe = RefTipe::get();

        return view('backend.publikasi.create', compact(['kategori', 'tipe']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => [
                    'required',
                    'max:255',
                    'unique:publikasi,judul',
                    'regex:/^[^<>]*$/',
                    function ($attribute, $value, $fail) {
                        if (strip_tags($value) !== $value) {
                            $fail('The '.$attribute.' field cannot contain HTML tags.');
                        }
                    },
                ],
                'sub_judul' => [
                    'nullable',
                    'max:255',
                    'regex:/^[^<>]*$/',
                    function ($attribute, $value, $fail) {
                        if ($value && strip_tags($value) !== $value) {
                            $fail('The '.$attribute.' field cannot contain HTML tags.');
                        }
                    },
                ],
                'kategori' => 'required|exists:ref_kategori,id_kategori',
                'tipe' => 'required|exists:ref_tipe,id_tipe',
                'images' => 'required|array|min:1',
                'images.*' => 'required|image|mimes:jpeg,png,jpg|max:20480',
                'isi' => [
                    'required',
                    'min:10',
                    'max:25000',
                ],
                'backdate' => 'nullable|date',
                'publish_at' => 'nullable|date|after:now',
                'file' => 'nullable|mimes:pdf,doc,docx|max:5120',
                'embedded_media' => 'nullable|url|max:2000',
            ], [
                'judul.regex' => 'Judul tidak boleh mengandung tag HTML',
                'sub_judul.regex' => 'Sub judul tidak boleh mengandung tag HTML',
                'images.required' => 'Minimal satu gambar harus diunggah',
                'images.*.image' => 'File yang diunggah harus berupa gambar',
                'images.*.mimes' => 'Gambar harus berformat jpeg, png, atau jpg',
                'images.*.max' => 'Ukuran gambar tidak boleh melebihi 20MB',
            ]);

            // Sanitize input before processing
            $validated['judul'] = strip_tags($validated['judul']);
            $validated['sub_judul'] = strip_tags($validated['sub_judul']);

            // Ensure images exist
            if (! $request->hasFile('images')) {
                throw new Exception('Minimal satu gambar harus diunggah');
            }

            // File upload (optional)
            $filePath = $request->hasFile('file')
                ? $request->file('file')->store('public/romadan_file_web')
                : null;

            // Prepare data
            $data = [
                'judul' => $validated['judul'],
                'sub_judul' => $validated['sub_judul'] ?? null,
                'kategori' => $validated['kategori'],
                'tipe' => $validated['tipe'],
                'image' => null, // We'll update this with the primary image path
                'isi' => $validated['isi'],
                'embedded_media' => $validated['embedded_media'] ?? null,
                'slug' => Str::slug($validated['judul']),
                'penulis' => Auth::user()->name,
                'static_random_string' => Str::random(16),
                'file' => $filePath ? basename($filePath) : null,
                'views' => 0,
            ];

            // Status, backdate, dan penjadwalan publikasi
            if ($request->filled('backdate')) {
                $data['backdate'] = Carbon::parse($validated['backdate']);
                $data['status'] = 'published';
            } elseif ($request->filled('publish_at')) {
                $data['published_at'] = Carbon::parse($validated['publish_at']);
                $data['status'] = 'scheduled';
            } else {
                $data['status'] = 'draft';
            }

            // Create the publikasi record
            $publikasi = PublikasiModel::create($data);

            // Process and store multiple images
            $images = $request->file('images');
            $isPrimary = true; // First image will be primary
            $sortOrder = 0;

            foreach ($images as $image) {
                $imagePath = $image->store('public/romadan_gambar_web');
                $imageFileName = basename($imagePath);

                // Create image record
                $publikasi->images()->create([
                    'image_path' => $imageFileName,
                    'is_primary' => $isPrimary,
                    'sort_order' => $sortOrder,
                ]);

                // If this is the primary image, update the main publikasi record
                if ($isPrimary) {
                    $publikasi->update(['image' => $imageFileName]);
                    $isPrimary = false;
                }

                $sortOrder++;
            }

            return back()->with('success', 'Data Publikasi Berhasil Disimpan!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            Log::error('Publikasi Creation Error: '.$e->getMessage());

            return back()
                ->with('failed', 'Data Publikasi Gagal Disimpan')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $data = PublikasiModel::findOrFail(decrypt($id));
        // dd($data);

        return view('backend.publikasi.show', compact(['data']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $kategori = RefKategori::all();
        $status = RefStatus::all();
        $tipe = RefTipe::all();
        $publikasi = PublikasiModel::with(['kategori', 'status', 'tipe'])->findOrFail(decrypt($id));

        // dd($publikasi['created_at']);

        // dd(Carbon::parse($publikasi->created_at)->format('Y-m-d H:i:s'));

        return view('backend.publikasi.edit', compact(['publikasi', 'kategori', 'status', 'tipe']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'judul' => [
                    'required',
                    'max:255',
                    'regex:/^[^<>]*$/',
                    function ($attribute, $value, $fail) {
                        if (strip_tags($value) !== $value) {
                            $fail('The '.$attribute.' field cannot contain HTML tags.');
                        }
                    },
                ],
                'sub_judul' => [
                    'required',
                    'max:255',
                    'regex:/^[^<>]*$/',
                    function ($attribute, $value, $fail) {
                        if (strip_tags($value) !== $value) {
                            $fail('The '.$attribute.' field cannot contain HTML tags.');
                        }
                    },
                ],
                'kategori' => 'required|exists:ref_kategori,id_kategori',
                'tipe' => 'required|exists:ref_tipe,id_tipe',
                'status' => 'required|exists:ref_status,nama_status',
                'published_at' => 'required_if:status,scheduled|nullable|date|after:now',
                'new_images' => 'nullable|array',
                'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:20480|dimensions:min_width=1024,min_height=600',
                'primary_image' => 'nullable|exists:publikasi_images,id',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'nullable|exists:publikasi_images,id',
                'isi' => [
                    'required',
                    'min:10',
                    'max:25000',
                ],
                'created_at' => 'required|date|before:now',
                'file' => 'nullable|mimes:pdf|max:10240',
                'embedded_media' => 'nullable|url|max:2000',
            ], [
                'judul.regex' => 'Judul tidak boleh mengandung tag HTML',
                'sub_judul.regex' => 'Sub judul tidak boleh mengandung tag HTML',
                'new_images.*.image' => 'File yang diunggah harus berupa gambar',
                'new_images.*.mimes' => 'Gambar harus berformat jpeg, png, atau jpg',
                'new_images.*.max' => 'Ukuran gambar tidak boleh melebihi 20MB',
                'new_images.*.dimensions' => 'Dimensi gambar minimal ukuran 1024x600 piksel',
            ]);

            // Sanitize input before processing
            $validated['judul'] = strip_tags($validated['judul']);
            $validated['sub_judul'] = strip_tags($validated['sub_judul']);

            // Get the publikasi record
            $publikasi = PublikasiModel::findOrFail(decrypt($id));

            // Prepare data — selalu dari $validated agar rule & sanitasi tidak ter-bypass
            $data = [
                'judul' => $validated['judul'],
                'sub_judul' => $validated['sub_judul'],
                'kategori' => $validated['kategori'],
                'tipe' => $validated['tipe'],
                'isi' => $validated['isi'],
                'embedded_media' => $validated['embedded_media'] ?? null,
                'status' => $validated['status'],
                'published_at' => $validated['status'] === 'scheduled'
                    ? Carbon::parse($validated['published_at'])
                    : null,
                'pengedit' => Auth::user()->name,
                'created_at' => Carbon::parse($validated['created_at'])->format('Y-m-d H:i:s'),
            ];

            // Process file upload if provided
            if ($request->hasFile('file')) {
                $request->validate([
                    'file' => 'mimes:pdf|max:10240',
                ], [
                    'file.mimes' => 'File hanya diperbolehkaan berekstensi PDF',
                    'file.max' => 'Ukuran File tidak boleh melebihi 10MB (10240 KB)',
                ]);

                // Upload file
                $file = $request->file('file');
                $file->storeAs('public/romadan_file_web', $file->hashName());

                // Delete old file if exists
                if ($publikasi->file) {
                    File::delete(public_path('storage/romadan_file_web/').$publikasi->file);
                }

                $data['file'] = $file->hashName();
            }

            // Delete images if requested
            if ($request->has('delete_images') && is_array($request->delete_images)) {
                foreach ($request->delete_images as $imageId) {
                    $image = $publikasi->images()->find($imageId);
                    if ($image) {
                        // Delete the file
                        File::delete(public_path('storage/romadan_gambar_web/').$image->image_path);
                        // Delete the record
                        $image->delete();
                    }
                }
            }

            // Upload new images if provided
            if ($request->hasFile('new_images')) {
                $newImages = $request->file('new_images');
                $sortOrder = $publikasi->images()->max('sort_order') + 1;

                foreach ($newImages as $image) {
                    $imagePath = $image->store('public/romadan_gambar_web');
                    $imageFileName = basename($imagePath);

                    // Create image record
                    $publikasi->images()->create([
                        'image_path' => $imageFileName,
                        'is_primary' => false,
                        'sort_order' => $sortOrder,
                    ]);

                    $sortOrder++;
                }
            }

            // Update primary image if requested
            if ($request->has('primary_image')) {
                // Reset all images to non-primary
                $publikasi->images()->update(['is_primary' => false]);

                // Set selected image as primary
                $primaryImage = $publikasi->images()->find($request->primary_image);
                if ($primaryImage) {
                    $primaryImage->update(['is_primary' => true]);
                    $data['image'] = $primaryImage->image_path;
                }
            }

            // Update the publikasi record
            $publikasi->update($data);

            return redirect()->route('publikasi.index')->with('success', "Publikasi $request->judul berhasil diupdate!");
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            report($e);

            return redirect()->route('publikasi.index')
                ->with(['failed' => 'Data Publikasi Gagal Di Update!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $publikasi = PublikasiModel::findOrFail(decrypt($id));

            // Soft delete: turunkan status ke draft lalu hapus (file tetap ada,
            // baru dibuang permanen saat force-delete dari halaman sampah)
            $publikasi->update(['status' => 'draft']);
            $publikasi->delete();

            return redirect()->route('publikasi.index')->with('success', 'Publikasi berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('publikasi.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada !']);
        }
    }

    public function publikasiSampah(Request $request)
    {
        $query = PublikasiModel::onlyTrashed()->with(['kategori', 'status', 'tipe']);
        // dd($query);
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('image_publikasi', function ($query) {
                    $url = asset('storage/romadan_gambar_web/'.$query->image);

                    return '<a href="'.$url.'"><img src="'.$url.'" border="0" width="100" class="img-rounded" align="center""/></a>';
                })
                ->addColumn('opsi', function ($query) {
                    $encryptedId = encrypt($query->id);

                    return view('components.datatable-actions', [
                        'restore' => route('publikasi.restore', $encryptedId),
                        'forceDelete' => route('publikasi.force-delete', $encryptedId),
                    ])->render();
                })
                ->rawColumns(['opsi', 'image_publikasi'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.publikasi.sampah', compact('query'));
    }

    public function restorePublikasi($id)
    {
        try {
            $data['status'] = 'draft';
            PublikasiModel::onlyTrashed()->findOrFail(decrypt($id))->update($data);
            PublikasiModel::onlyTrashed()->findOrFail(decrypt($id))->restore();

            return redirect()->route('publikasi.sampah')->with('success', 'Data publikasi berhasil direstore!, silahkan cek pada publikasi aktif yah guys!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('publikasi.sampah')->with(['failed' => 'Data publikasi GAGAL di Restore !']);
        }
    }

    public function restoreAllPublikasi()
    {

        $dataterhapus = PublikasiModel::onlyTrashed()->with(['kategori', 'status', 'tipe'])->get();

        if (count($dataterhapus) > 0) {
            try {
                $data['status'] = 'draft';
                PublikasiModel::onlyTrashed()->update($data);
                PublikasiModel::onlyTrashed()->restore();

                return redirect()->route('publikasi.sampah')->with('success', 'Semua Data publikasi berhasil direstore!, silahkan cek pada publikasi aktif yah guys!');
            } catch (Exception $e) {
                report($e);

                return redirect()->route('publikasi.sampah')->with(['failed' => 'Semua Data publikasi GAGAL di Restore !']);
            }
        }

        return redirect()->route('publikasi.sampah')->with(['failed' => 'Data yang direstore gak ada :( ']);
    }

    public function forceDeletePublikasi($id)
    {
        try {
            $publikasi = PublikasiModel::withTrashed()->findOrFail(decrypt($id));

            // Delete all associated image files
            foreach ($publikasi->images as $image) {
                File::delete(public_path('storage/romadan_gambar_web/').$image->image_path);
            }

            // Delete PDF file if exists
            if ($publikasi->file) {
                File::delete(public_path('storage/romadan_file_web/').$publikasi->file);
            }

            // Force delete the publikasi which will cascade delete images due to foreign key constraint
            $publikasi->forceDelete();

            return redirect()->route('publikasi.sampah')->with('success', 'Data Berhasil dihapus PERMANEN');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('publikasi.sampah')->with(['failed' => 'Data GAGAL dihapus Permanen !']);
        }
    }
}
