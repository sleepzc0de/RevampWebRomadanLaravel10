<?php

namespace App\Http\Controllers\MenuLayanan;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuLayanan\LayananModel;
use App\Models\backend\MenuLayanan\LayananImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = LayananModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('image_layanan', function ($query) {
                    $url = asset('storage/romadan_gambar_web/' . $query->image);
                    return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center""/></a>';
                })
                ->addColumn('opsi', function ($query) {
                    $edit = route('layanan.edit', encrypt($query->id));
                    $hapus = route('layanan.destroy', encrypt($query->id));
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
                ->rawColumns(['opsi', 'image_layanan'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.layanan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.layanan.create');
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
                    'unique:layanan,judul',
                    'regex:/^[^<>]*$/', // Prevents HTML tags
                    function ($attribute, $value, $fail) {
                        if (strip_tags($value) !== $value) {
                            $fail('The '.$attribute.' field cannot contain HTML tags.');
                        }
                    },
                ],
                'layanan' => [
                    'required',
                    'min:10',
                    'max:5000', // Increased from 1000 to 5000
                ],
                'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:20480', // Increased from 10MB to 20MB
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:20480', // For multiple images
                'video_url' => 'nullable|url', // For video URL
            ],[
                'judul.regex' => 'Judul tidak boleh mengandung tag HTML',
                'image.max' => 'Ukuran gambar maksimal 20MB',
                'additional_images.*.max' => 'Ukuran gambar tambahan maksimal 20MB',
                'video_url.url' => 'URL video harus berupa URL yang valid',
            ]);

            $validated['judul'] = strip_tags($validated['judul']);

            //UPLOAD MAIN IMAGE
            $image = $request->file('image');
            $image->storeAs('public/romadan_gambar_web', $image->hashName());

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $validated['judul'],
                'layanan' => $validated['layanan'],
                'image' => $image->hashName(),
                'video_url' => $request->video_url,
            ];

            // Create the main layanan record
            $layanan = LayananModel::create($data);

            // Handle additional images if any
            if ($request->hasFile('additional_images')) {
                foreach($request->file('additional_images') as $additionalImage) {
                    $imageName = time() . '_' . $additionalImage->getClientOriginalName();
                    $additionalImage->storeAs('public/romadan_gambar_web', $imageName);

                    // Save the additional image
                    LayananImage::create([
                        'layanan_id' => $layanan->id,
                        'image_path' => $imageName
                    ]);
                }
            }

            DB::commit();

            //redirect to index
            return redirect()->back()->with(['success' => 'Layanan Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error adding layanan: ' . $e->getMessage());
            return redirect()->back()->with(['failed' => 'Layanan Gagal Ditambahkan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $layanan = LayananModel::with('additionalImages')->findOrFail(decrypt($id));
        return view('backend.layanan.edit', compact('layanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            // Get the layanan record
            $layanan = LayananModel::findOrFail(decrypt($id));

            // VALIDASI DATA
            $validated = $request->validate([
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
                'layanan' => [
                    'required',
                    'max:5000', // Increased from 1000 to 5000
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:20480', // Increased to 20MB
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:20480', // For multiple images
                'remove_images' => 'nullable|array', // For removing existing images
                'video_url' => 'nullable|url', // For video URL
            ], [
                'judul.regex' => 'Judul tidak boleh mengandung tag HTML',
                'image.max' => 'Ukuran gambar maksimal 20MB',
                'additional_images.*.max' => 'Ukuran gambar tambahan maksimal 20MB',
                'video_url.url' => 'URL video harus berupa URL yang valid',
            ]);

            $validated['judul'] = strip_tags($validated['judul']);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $validated['judul'],
                'layanan' => $validated['layanan'],
                'video_url' => $request->video_url,
            ];

            // Handle main image update if provided
            if ($request->hasFile('image')) {
                // Delete old image
                File::delete(public_path('storage/romadan_gambar_web/') . $layanan->image);

                // Upload new image
                $image = $request->file('image');
                $image->storeAs('public/romadan_gambar_web', $image->hashName());
                $data['image'] = $image->hashName();
            }

            // Handle additional images removal if requested
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $imageId) {
                    $image = LayananImage::findOrFail($imageId);
                    File::delete(public_path('storage/romadan_gambar_web/') . $image->image_path);
                    $image->delete();
                }
            }

            // Handle additional images upload if provided
            if ($request->hasFile('additional_images')) {
                foreach($request->file('additional_images') as $additionalImage) {
                    $imageName = time() . '_' . $additionalImage->getClientOriginalName();
                    $additionalImage->storeAs('public/romadan_gambar_web', $imageName);

                    // Save the additional image
                    LayananImage::create([
                        'layanan_id' => $layanan->id,
                        'image_path' => $imageName
                    ]);
                }
            }

            // Update the main layanan record
            $layanan->update($data);

            DB::commit();
            return redirect()->route('layanan.index')->with('success', "Layanan berhasil diupdate!");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating layanan: ' . $e->getMessage());
            return redirect()->route('layanan.index')->with(['failed' => 'Layanan Gagal Di Update! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $layanan = LayananModel::with('additionalImages')->findOrFail(decrypt($id));

            // Delete main image
            File::delete(public_path('storage/romadan_gambar_web/') . $layanan->image);

            // Delete all additional images
            foreach ($layanan->additionalImages as $image) {
                File::delete(public_path('storage/romadan_gambar_web/') . $image->image_path);
            }

            // Delete the layanan record (will cascade delete related records)
            $layanan->delete();

            DB::commit();
            return redirect()->route('layanan.index')->with('success', "Layanan berhasil dihapus!");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting layanan: ' . $e->getMessage());
            return redirect()->route('layanan.index')->with(['failed' => 'Layanan Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove a specific additional image
     */
    public function removeAdditionalImage(Request $request, $imageId)
    {
        try {
            $image = LayananImage::findOrFail($imageId);

            // Delete the image file
            File::delete(public_path('storage/romadan_gambar_web/') . $image->image_path);

            // Delete the record
            $image->delete();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Error removing additional image: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
