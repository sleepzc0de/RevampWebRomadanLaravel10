<?php

namespace App\Http\Controllers\MenuProfile;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuProfile\SejarahModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SejarahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = SejarahModel::all();
        $query = SejarahModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                ->addColumn('image_sejarah', function ($query) {
                    // Display the first image from media if available
                    if ($query->media && is_array(json_decode($query->media, true)) && count(json_decode($query->media, true)) > 0) {
                        $mediaItems = json_decode($query->media, true);
                        foreach ($mediaItems as $item) {
                            if ($item['type'] === 'image') {
                                $url = asset('storage/romadan_gambar_web/' . $item['path']);
                                return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center"/></a>';
                            }
                        }
                    }

                    // Fallback to old image display method
                    if ($query->image) {
                        $url = asset('storage/romadan_gambar_web/' . $query->image);
                        return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center"/></a>';
                    }

                    return 'No image';
                })
                ->addColumn('opsi', function ($query) {
                    $edit = route('sejarah.edit', encrypt($query->id));
                    $hapus = route('sejarah.destroy', encrypt($query->id));
                    return '<div class="d-inline-flex">
											<div class="dropdown">
												<a href="#" class="text-body" data-bs-toggle="dropdown">
													<i class="ph-list"></i>
												</a>

												<div class="dropdown-menu dropdown-menu-end">

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
										</div>
                ';
                })

                ->editColumn('created_at', function ($query) {
                    return date('d-M-Y H:i:s', strtotime($query->created_at));
                })


                ->rawColumns(['opsi', 'image_sejarah'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.sejarah.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.sejarah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
           $validated = $request->validate([
                'judul' => [
                    'required',
                    'max:255',
                    'unique:sejarah,judul',
                    'regex:/^[^<>]*$/', // Prevents HTML tags
                    function ($attribute, $value, $fail) {
                        if (strip_tags($value) !== $value) {
                            $fail('The '.$attribute.' field cannot contain HTML tags.');
                        }
                    },
                ],
                'sejarah' => [
                    'required',
                    'min:10',
                    'max:10000', // Increased from 1000 to handle more content with images
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:20480', // Diubah dari 2000 menjadi 20480
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:20480', // Diubah dari 2000 menjadi 20480
                'video_urls' => 'nullable|array',
                'video_urls.*' => 'nullable|url',
            ],[
                'judul.regex' => 'Judul tidak boleh mengandung tag HTML',
                'image.max' => 'Ukuran gambar utama tidak boleh lebih dari 20MB.',
                'images.*.max' => 'Ukuran gambar tambahan tidak boleh lebih dari 20MB.'
            ]);

            $validated['judul'] = strip_tags($validated['judul']);
            // JANGAN strip_tags pada konten sejarah untuk mempertahankan formatting HTML
            // $validated['sejarah'] = NOT stripped

            // Initialize media array
            $mediaItems = [];

            // Process original single image for backward compatibility
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image->storeAs('public/romadan_gambar_web', $image->hashName());
                $mediaItems[] = [
                    'type' => 'image',
                    'path' => $image->hashName(),
                    'original_name' => $image->getClientOriginalName()
                ];
            }

            // Process multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $image->storeAs('public/romadan_gambar_web', $image->hashName());
                    $mediaItems[] = [
                        'type' => 'image',
                        'path' => $image->hashName(),
                        'original_name' => $image->getClientOriginalName()
                    ];
                }
            }

            // Process video URLs
            if ($request->filled('video_urls')) {
                foreach ($request->input('video_urls') as $videoUrl) {
                    if (!empty($videoUrl)) {
                        $mediaItems[] = [
                            'type' => 'video',
                            'url' => $videoUrl,
                        ];
                    }
                }
            }

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $validated['judul'],
                'sejarah' => $validated['sejarah'], // Pertahankan HTML asli dari CKEditor
                'media' => json_encode($mediaItems),
            ];

            // Add original image for backward compatibility
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $data['image'] = $image->hashName();
            }

            SejarahModel::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Sejarah Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            // Log error untuk debugging
            Log::error('Error saat menyimpan sejarah: ' . $e->getMessage());
            return redirect()->back()->with(['failed' => 'Sejarah Gagal Ditambahkan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $kategori = ref_kategori::findOrFail(decrypt($id));
        $sejarah = SejarahModel::findOrFail(decrypt($id));
        return view('backend.sejarah.edit', compact('sejarah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $validated= $request->validate([
                'judul' => [
                    'required',
                    'max:255',
                    'regex:/^[^<>]*$/', // Prevents HTML tags
                    function ($attribute, $value, $fail) {
                        if (strip_tags($value) !== $value) {
                            $fail('The '.$attribute.' field cannot contain HTML tags.');
                        }
                    },
                ],
               'sejarah' => [
                    'required',
                    'max:10000',
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:20480', // Diubah dari 2000 menjadi 20480
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:20480', // Diubah dari 2000 menjadi 20480
                'video_urls' => 'nullable|array',
                'video_urls.*' => 'nullable|url',
                'keep_media' => 'nullable|array',
            ],
            [
                'judul.regex' => 'Judul tidak boleh mengandung tag HTML',
                'image.max' => 'Ukuran gambar utama tidak boleh lebih dari 20MB.',
                'images.*.max' => 'Ukuran gambar tambahan tidak boleh lebih dari 20MB.'
            ]);

            $validated['judul'] = strip_tags($validated['judul']);
            // JANGAN strip_tags pada konten sejarah untuk mempertahankan formatting HTML
            // $validated['sejarah'] = NOT stripped

            // Get current sejarah data
            $sejarah = SejarahModel::findOrFail(decrypt($id));

            // Initialize data array with basic fields
            $data = [
                'judul' => $validated['judul'],
                'sejarah' => $validated['sejarah'], // Pertahankan HTML asli dari CKEditor
            ];

            // Handle media updates
            $newMediaItems = [];

            // If keep_media is provided, keep those items from existing media
            if ($request->has('keep_media') && is_array($request->input('keep_media'))) {
                $existingMedia = json_decode($sejarah->media ?? '[]', true);
                foreach ($request->input('keep_media') as $index) {
                    if (isset($existingMedia[$index])) {
                        $newMediaItems[] = $existingMedia[$index];
                    }
                }
            } else if ($sejarah->media && !$request->has('keep_media')) {
                // If keep_media is not provided but we have existing media,
                // assume we're keeping all existing media
                $newMediaItems = json_decode($sejarah->media, true);
            }

            // Add single image (backwards compatibility)
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($sejarah->image) {
                    File::delete(public_path('storage/romadan_gambar_web/') . $sejarah->image);
                }

                $image = $request->file('image');
                $image->storeAs('public/romadan_gambar_web', $image->hashName());

                // Set the image in data for backwards compatibility
                $data['image'] = $image->hashName();

                // Also add to the media items
                $newMediaItems[] = [
                    'type' => 'image',
                    'path' => $image->hashName(),
                    'original_name' => $image->getClientOriginalName()
                ];
            }

            // Add multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $image->storeAs('public/romadan_gambar_web', $image->hashName());
                    $newMediaItems[] = [
                        'type' => 'image',
                        'path' => $image->hashName(),
                        'original_name' => $image->getClientOriginalName()
                    ];
                }
            }

            // Add video URLs
            if ($request->filled('video_urls')) {
                foreach ($request->input('video_urls') as $videoUrl) {
                    if (!empty($videoUrl)) {
                        $newMediaItems[] = [
                            'type' => 'video',
                            'url' => $videoUrl,
                        ];
                    }
                }
            }

            // Update media in data
            $data['media'] = json_encode($newMediaItems);

            SejarahModel::findOrFail(decrypt($id))->update($data);
            return redirect()->route('sejarah.index')->with('success', "Sejarah berhasil diupdate!");
        } catch (Exception $e) {
            // Log error untuk debugging
            Log::error('Error saat mengupdate sejarah: ' . $e->getMessage());
            return redirect()->route('sejarah.index')->with(['failed' => 'Sejarah Gagal Di Update! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data_gambar = SejarahModel::findOrFail(decrypt($id));

            // Delete the single image (backwards compatibility)
            if ($data_gambar->image) {
                File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);
            }

            // Delete all media images
            if ($data_gambar->media) {
                $mediaItems = json_decode($data_gambar->media, true);
                foreach ($mediaItems as $item) {
                    if ($item['type'] === 'image' && isset($item['path'])) {
                        File::delete(public_path('storage/romadan_gambar_web/') . $item['path']);
                    }
                }
            }

            $data_gambar->delete();
            return redirect()->route('sejarah.index')->with('success', "Sejarah berhasil dihapus!");
        } catch (Exception $e) {
            // Log error untuk debugging
            Log::error('Error saat menghapus sejarah: ' . $e->getMessage());
            return redirect()->route('sejarah.index')->with(['failed' => 'Sejarah Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
