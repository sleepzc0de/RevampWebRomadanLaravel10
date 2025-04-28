<?php

namespace App\Http\Controllers\MenuProfile;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuProfile\VisiMisiModel;
use App\Models\backend\MenuProfile\VisiMisiImageModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class VisiMisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = VisiMisiModel::with('images')->get();
        $query = VisiMisiModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('image_visimisi', function ($query) {
                    $url = asset('storage/romadan_gambar_web/' . $query->image);
                    return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center""/></a>';
                })
                ->addColumn('has_video', function ($query) {
                    return !empty($query->video_url) ? 'Ya' : 'Tidak';
                })
                ->addColumn('image_count', function ($query) {
                    // Count images including the main image
                    $additionalCount = VisiMisiImageModel::where('visimisi_id', $query->id)->count();
                    return 1 + $additionalCount;
                })
                ->addColumn('opsi', function ($query) {
                    $edit = route('visi-misi.edit', encrypt($query->id));
                    $hapus = route('visi-misi.destroy', encrypt($query->id));
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
                ->rawColumns(['opsi', 'image_visimisi'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.visimisi.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.visimisi.create');
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
                    'unique:visimisi,judul',
                ],
                'visi' => [
                    'required',
                    'max:3000',
                    'unique:visimisi,visi',
                ],
                'misi' => [
                    'required',
                    'max:3000',
                    'unique:visimisi,misi',
                ],
                'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:10240',
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:10240',
                'video_url' => 'nullable|url|max:500',
            ]);

            // Validate video URL format if provided
            if (!empty($validated['video_url'])) {
                $this->validateVideoUrl($validated['video_url']);
            }

            // UPLOAD MAIN IMAGE
            $image = $request->file('image');
            $image->storeAs('public/romadan_gambar_web', $image->hashName());

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $validated['judul'],
                'visi' => $validated['visi'],
                'misi' => $validated['misi'],
                'image' => $image->hashName(),
                'video_url' => $validated['video_url'] ?? null,
            ];

            // Create main record
            $visimisi = VisiMisiModel::create($data);

            // Process additional images if any
            if ($request->hasFile('additional_images')) {
                $this->processAdditionalImages($request->file('additional_images'), $visimisi->id);
            }

            //redirect to index
            return redirect()->back()->with(['success' => 'Visi dan Misi Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Visi dan Misi Gagal Ditambahkan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $visimisi = VisiMisiModel::with('images')->findOrFail(decrypt($id));
        return view('backend.visimisi.edit', compact('visimisi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $decryptedId = decrypt($id);

            // VALIDASI DATA
            $validated = $request->validate([
                'judul' => [
                    'required',
                    'max:255',
                ],
                'visi' => [
                    'required',
                    'max:3000',
                ],
                'misi' => [
                    'required',
                    'max:3000',
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:10240',
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:10240',
                'video_url' => 'nullable|url|max:500',
                'sort_order.*' => 'nullable|integer',
                'delete_image.*' => 'nullable|boolean',
            ]);

            // Validate video URL format if provided
            if (!empty($validated['video_url'])) {
                $this->validateVideoUrl($validated['video_url']);
            }

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $validated['judul'],
                'visi' => $validated['visi'],
                'misi' => $validated['misi'],
                'video_url' => $validated['video_url'] ?? null,
            ];

            // Update main image if provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image->storeAs('public/romadan_gambar_web', $image->hashName());

                $data_gambar = VisiMisiModel::findOrFail($decryptedId);
                File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);

                $data['image'] = $image->hashName();
            }

            // Update the main record
            VisiMisiModel::findOrFail($decryptedId)->update($data);

            // Process image deletions
            if ($request->has('delete_image')) {
                foreach ($request->input('delete_image') as $imageId => $shouldDelete) {
                    if ($shouldDelete) {
                        $imageToDelete = VisiMisiImageModel::find($imageId);
                        if ($imageToDelete) {
                            File::delete(public_path('storage/romadan_gambar_web/') . $imageToDelete->image);
                            $imageToDelete->delete();
                        }
                    }
                }
            }

            // Update sort orders for existing images
            if ($request->has('sort_order')) {
                foreach ($request->input('sort_order') as $imageId => $order) {
                    VisiMisiImageModel::where('id', $imageId)->update(['sort_order' => $order]);
                }
            }

            // Process additional images if any
            if ($request->hasFile('additional_images')) {
                $this->processAdditionalImages($request->file('additional_images'), $decryptedId);
            }

            return redirect()->route('visi-misi.index')->with('success', "Visi Misi berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('visi-misi.index')->with(['failed' => 'Visi Misi Gagal Di Update! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $decryptedId = decrypt($id);

            // Get the main data
            $data_gambar = VisiMisiModel::findOrFail($decryptedId);

            // Delete main image
            File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);

            // Get all additional images
            $additionalImages = VisiMisiImageModel::where('visimisi_id', $decryptedId)->get();

            // Delete all additional images
            foreach ($additionalImages as $image) {
                File::delete(public_path('storage/romadan_gambar_web/') . $image->image);
                $image->delete();
            }

            // Delete the main record
            $data_gambar->delete();

            return redirect()->route('visi-misi.index')->with('success', "Visi Misi berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('visi-misi.index')->with(['failed' => 'Visi Misi Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }

    /**
     * Process and save additional images
     */
    private function processAdditionalImages($images, $visimisiId)
    {
        $sortOrder = VisiMisiImageModel::where('visimisi_id', $visimisiId)->max('sort_order') ?? 0;

        foreach ($images as $image) {
            $sortOrder++;
            $image->storeAs('public/romadan_gambar_web', $image->hashName());

            VisiMisiImageModel::create([
                'visimisi_id' => $visimisiId,
                'image' => $image->hashName(),
                'sort_order' => $sortOrder
            ]);
        }
    }

    /**
     * Validate video URL format
     */
    private function validateVideoUrl($url)
    {
        // Check if it's a YouTube or Vimeo URL
        $youtubePattern = '/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/';
        $vimeoPattern = '/^(https?:\/\/)?(www\.)?(vimeo\.com)\/.+$/';

        if (!preg_match($youtubePattern, $url) && !preg_match($vimeoPattern, $url)) {
            throw new Exception('URL video harus dari YouTube atau Vimeo');
        }

        return true;
    }
}
