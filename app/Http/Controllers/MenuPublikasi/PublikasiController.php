<?php

namespace App\Http\Controllers\MenuPublikasi;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuPublikasi\PublikasiModel;
use App\Models\backend\ref_kategori;
use App\Models\backend\ref_status;
use App\Models\backend\ref_tipe;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PublikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = PublikasiModel::with(['kategori', 'status', 'tipe', 'images'])->select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('image_publikasi', function ($query) {
                    // Get the primary image or the first image
                    $image = $query->images()->where('is_primary', true)->first();
                    if (!$image) {
                        $image = $query->images()->first();
                    }

                    if ($image) {
                        $url = asset('storage/romadan_gambar_web/' . $image->image_path);
                        return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center""/></a>';
                    }

                    return '<span>No image</span>';
                })
                ->addColumn('file_publikasi', function ($query) {
                    $judul = strlen($query->judul) > 10 ? substr($query->judul, 0, 10) . '...' : $query->judul;

                    if ($query->file) {
                        $url = asset('storage/romadan_file_web/' . $query->file);
                        return '<a href="' . $url . '" target="_blank" title="' . e($query->judul) . '">' . $judul . '</a>';
                    }

                    return '<span title="' . e($query->judul) . '">' . $judul . '</span>';
                })
                ->addColumn('opsi', function ($query) {
                    $preview = route('publikasi.show', encrypt($query->id));
                    $edit = route('publikasi.edit', encrypt($query->id));
                    $hapus = route('publikasi.destroy', encrypt($query->id));
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
                                        ' . @csrf_field() . '
                                        ' . @method_field('DELETE') . '
                                        <button type="submit" name="submit" class="dropdown-item"> <i class="ph-trash me-2"></i> Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>';
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $kategori = ModelsRef_kategori::get();
        $kategori = ref_kategori::get();
        $tipe = ref_tipe::get();
        return view('backend.publikasi.create', compact(['kategori', 'tipe']));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
             if (!$request->hasFile('images')) {
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
                 'views' => 0
             ];

             // Status and backdate handling
             if ($request->filled('backdate')) {
                 $data['backdate'] = Carbon::parse($validated['backdate']);
                 $data['status'] = 'published';
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
                     'sort_order' => $sortOrder
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
             Log::error('Publikasi Creation Error: ' . $e->getMessage());
             return back()
                 ->with('failed', 'Data Publikasi Gagal Disimpan: ' . $e->getMessage())
                 ->withInput();
         }
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategori = ref_kategori::all();
        $status = ref_status::all();
        $tipe = ref_tipe::all();
        $publikasi = PublikasiModel::with(['kategori', 'status', 'tipe'])->findOrFail(decrypt($id));


        // dd($publikasi['created_at']);

        // dd(Carbon::parse($publikasi->created_at)->format('Y-m-d H:i:s'));

        return view('backend.publikasi.edit', compact(['publikasi', 'kategori', 'status', 'tipe']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
                'kategori' => 'required',
                'tipe' => 'required',
                'new_images' => 'nullable|array',
                'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:20480|dimensions:min_width=1024,min_height=600',
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
                'new_images.*.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau svg',
                'new_images.*.max' => 'Ukuran gambar tidak boleh melebihi 20MB',
                'new_images.*.dimensions' => 'Dimensi gambar minimal ukuran 1024x600 piksel',
            ]);

            // Sanitize input before processing
            $validated['judul'] = strip_tags($validated['judul']);
            $validated['sub_judul'] = strip_tags($validated['sub_judul']);

            // Get the publikasi record
            $publikasi = PublikasiModel::findOrFail(decrypt($id));

            // Prepare data
            $data = [
                'judul' => $request->judul,
                'sub_judul' => $request->sub_judul,
                'kategori' => $request->kategori,
                'tipe' => $request->tipe,
                'isi' => $request->isi,
                'embedded_media' => $request->embedded_media,
                'status' => $request->status,
                'pengedit' => Auth::user()->name,
                'created_at' => Carbon::parse($request->created_at)->format('Y-m-d H:i:s'),
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
                    File::delete(public_path('storage/romadan_file_web/') . $publikasi->file);
                }

                $data['file'] = $file->hashName();
            }

            // Delete images if requested
            if ($request->has('delete_images') && is_array($request->delete_images)) {
                foreach ($request->delete_images as $imageId) {
                    $image = $publikasi->images()->find($imageId);
                    if ($image) {
                        // Delete the file
                        File::delete(public_path('storage/romadan_gambar_web/') . $image->image_path);
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
                        'sort_order' => $sortOrder
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
            return redirect()->route('publikasi.index')
                ->with(['failed' => 'Data Publikasi Gagal Di Update! error :' . $e->getMessage()]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $publikasi = PublikasiModel::findOrFail(decrypt($id));

            // Get all images to delete later
            $images = $publikasi->images()->get();

            // Update status to draft
            $data['status'] = 'draft';
            $publikasi->update($data);

            // Soft delete the publikasi
            $publikasi->delete();

            return redirect()->route('publikasi.index')->with('success', "Publikasi berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('publikasi.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }

    public function publikasiSampah(Request $request)
    {
        $query = PublikasiModel::onlyTrashed()->with(['kategori', 'status', 'tipe']);
        // dd($query);
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('image_publikasi', function ($query) {
                    $url = asset('storage/romadan_gambar_web/' . $query->image);
                    return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center""/></a>';
                })
                ->addColumn('opsi', function ($query) {
                    // $preview = route('berita.show', $query->id);
                    $restore = route('publikasi.restore', encrypt($query->id));
                    $paksahapus = route('publikasi.force-delete', encrypt($query->id));
                    return '<div class="d-inline-flex">
											<div class="dropdown">
												<a href="#" class="text-body" data-bs-toggle="dropdown">
													<i class="ph-list"></i>
												</a>

												<div class="dropdown-menu dropdown-menu-end">
													<form action="' . $restore . '" method="POST">
													' . @csrf_field() . '
													<button type="submit" name="submit" class="dropdown-item"> <i class="ph-trash me-2"></i> Restore</button>
													</form>
													<form action="' . $paksahapus . '" method="POST">
													' . @csrf_field() . '
													' . @method_field('DELETE') . '
													<button type="submit" name="submit" class="dropdown-item"> <i class="ph-trash me-2"></i> Paksa Hapus</button>
													</form>
												</div>
											</div>
										</div>
                ';
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
            return redirect()->route('publikasi.sampah')->with('success', "Data publikasi berhasil direstore!, silahkan cek pada publikasi aktif yah guys!");
        } catch (Exception $e) {
            return redirect()->route('publikasi.sampah')->with(['failed' => 'Data publikasi GAGAL di Restore ! error :' . $e->getMessage()]);
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
                return redirect()->route('publikasi.sampah')->with('success', "Semua Data publikasi berhasil direstore!, silahkan cek pada publikasi aktif yah guys!");
            } catch (Exception $e) {
                return redirect()->route('publikasi.sampah')->with(['failed' => 'Semua Data publikasi GAGAL di Restore ! error :' . $e->getMessage()]);
            }
        }
        return redirect()->route('publikasi.sampah')->with(['failed' => 'Data yang direstore gak ada :( ']);
    }

    public function forceDeletePublikasi($id)
{
    try {
        $publikasi = PublikasiModel::withTrashed()->findOrFail(decrypt($id));

        // Delete all associated image files
        foreach($publikasi->images as $image) {
            File::delete(public_path('storage/romadan_gambar_web/') . $image->image_path);
        }

        // Delete PDF file if exists
        if ($publikasi->file) {
            File::delete(public_path('storage/romadan_file_web/') . $publikasi->file);
        }

        // Force delete the publikasi which will cascade delete images due to foreign key constraint
        $publikasi->forceDelete();

        return redirect()->route('publikasi.sampah')->with('success', "Data Berhasil dihapus PERMANEN");
    } catch (Exception $e) {
        return redirect()->route('publikasi.sampah')->with(['failed' => 'Data GAGAL dihapus Permanen ! error :' . $e->getMessage()]);
    }
}
}
