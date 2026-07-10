<?php

namespace App\Http\Controllers\MenuProfile;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuProfile\TentangImage;
use App\Models\backend\MenuProfile\TentangModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TentangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = TentangModel::all();
        $query = TentangModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                ->addColumn('image_tentang', function ($query) {
                    $url = asset('storage/romadan_gambar_web/'.$query->image);

                    return '<a href="'.$url.'"><img src="'.$url.'" border="0" width="100" class="img-rounded" align="center""/></a>';
                })
                ->addColumn('video_url', function ($query) {
                    return $query->video_url ? '<a href="'.$query->video_url.'" target="_blank">Lihat Video</a>' : 'Tidak ada video';
                })
                ->addColumn('additional_images', function ($query) {
                    $images = $query->additionalImages;
                    if ($images->isEmpty()) {
                        return 'Tidak ada gambar tambahan';
                    }

                    $output = '';
                    foreach ($images as $image) {
                        $url = asset('storage/romadan_gambar_web/'.$image->image_path);
                        $output .= '<a href="'.$url.'" class="mr-2"><img src="'.$url.'" border="0" width="50" class="img-rounded" align="center"/></a>';
                    }

                    return $output;
                })
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'edit' => route('tentang.edit', encrypt($query->id)),
                        'destroy' => route('tentang.destroy', encrypt($query->id)),
                    ])->render();
                })

                ->editColumn('created_at', function ($query) {
                    return date('d-M-Y H:i:s', strtotime($query->created_at));
                })

                ->rawColumns(['opsi', 'image_tentang', 'video_url', 'additional_images'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.tentang.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.tentang.create');
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
                    'unique:tentang,judul',
                    'regex:/^[^<>]*$/', // Prevents HTML tags
                    function ($attribute, $value, $fail) {
                        if (strip_tags($value) !== $value) {
                            $fail('The '.$attribute.' field cannot contain HTML tags.');
                        }
                    },
                ],
                'tentang' => [
                    'required',
                    'min:10',
                    'max:1000',
                ],
                'image' => 'required|image|mimes:jpeg,png,jpg|max:20480', // 20MB
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:20480', // 20MB
                'video_url' => 'nullable|url',
            ], [
                'judul.regex' => 'Judul tidak boleh mengandung tag HTML',
                'image.max' => 'Ukuran gambar maksimal 20MB',
                'additional_images.*.max' => 'Ukuran gambar tambahan maksimal 20MB',
                'video_url.url' => 'URL video harus valid',
            ]);

            $validated['judul'] = strip_tags($validated['judul']);

            // UPLOAD IMAGE
            $image = $request->file('image');
            $image->storeAs('public/romadan_gambar_web', $image->hashName());

            // EXCERPT TENTANG ROMADAN
            $excerpt = Str::excerpt($request->tentang, '', [
                'radius' => 100,
                'omission' => '(...) ',
            ]);

            // Proses konten tentang
            $tentangContent = $request->tentang;

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $validated['judul'],
                'tentang' => $tentangContent,
                'excerpt' => $excerpt,
                'image' => $image->hashName(),
                'video_url' => $validated['video_url'] ?? null,
            ];

            // Buat record tentang
            $tentang = TentangModel::create($data);

            // Proses additional images jika ada
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $additionalImage) {
                    $additionalImage->storeAs('public/romadan_gambar_web', $additionalImage->hashName());

                    // Buat record untuk tiap gambar tambahan
                    TentangImage::create([
                        'tentang_id' => $tentang->id,
                        'image_path' => $additionalImage->hashName(),
                    ]);
                }
            }

            DB::commit();

            // redirect to index
            return redirect()->back()->with(['success' => 'Tentang Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['failed' => 'Tentang Gagal Ditambahkan!']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tentang = TentangModel::with('additionalImages')->findOrFail(decrypt($id));

        return view('backend.tentang.edit', compact('tentang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

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
                'tentang' => [
                    'required',
                    'max:1000',
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:20480', // 20MB
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:20480', // 20MB
                'video_url' => 'nullable|url',
                'remove_additional_image' => 'nullable|array',
                'remove_additional_image.*' => 'nullable|integer',
            ], [
                'judul.regex' => 'Judul tidak boleh mengandung tag HTML',
                'image.max' => 'Ukuran gambar maksimal 20MB',
                'additional_images.*.max' => 'Ukuran gambar tambahan maksimal 20MB',
                'video_url.url' => 'URL video harus valid',
            ]);

            $validated['judul'] = strip_tags($validated['judul']);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $validated['judul'],
                'tentang' => $request->tentang,
                'video_url' => $validated['video_url'] ?? null,
            ];

            // Update main image jika ada
            if ($request->hasFile('image')) {
                // UPLOAD IMAGE
                $image = $request->file('image');
                $image->storeAs('public/romadan_gambar_web', $image->hashName());

                $data_gambar = TentangModel::findOrFail(decrypt($id));
                File::delete(public_path('storage/romadan_gambar_web/').$data_gambar->image);

                $data['image'] = $image->hashName();
            }

            // Update tentang
            $tentang = TentangModel::findOrFail(decrypt($id));
            $tentang->update($data);

            // Proses penghapusan gambar tambahan yang dipilih
            if ($request->has('remove_additional_image')) {
                foreach ($request->remove_additional_image as $imageId) {
                    $additionalImage = TentangImage::find($imageId);
                    if ($additionalImage) {
                        File::delete(public_path('storage/romadan_gambar_web/').$additionalImage->image_path);
                        $additionalImage->delete();
                    }
                }
            }

            // Proses additional images baru jika ada
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $additionalImage) {
                    $additionalImage->storeAs('public/romadan_gambar_web', $additionalImage->hashName());

                    // Buat record untuk tiap gambar tambahan
                    TentangImage::create([
                        'tentang_id' => $tentang->id,
                        'image_path' => $additionalImage->hashName(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('tentang.index')->with('success', 'Tentang berhasil diupdate!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Tentang update error: '.$e->getMessage());

            return redirect()->route('tentang.index')->with(['failed' => 'Tentang Gagal Di Update!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $tentang = TentangModel::with('additionalImages')->findOrFail(decrypt($id));

            // Hapus gambar utama
            File::delete(public_path('storage/romadan_gambar_web/').$tentang->image);

            // Hapus semua gambar tambahan
            foreach ($tentang->additionalImages as $image) {
                File::delete(public_path('storage/romadan_gambar_web/').$image->image_path);
            }

            // Model TentangImage akan dihapus otomatis karena foreign key constraint
            $tentang->delete();

            DB::commit();

            return redirect()->route('tentang.index')->with('success', 'Tentang berhasil dihapus!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('tentang.index')->with(['failed' => 'Tentang Yang Dihapus Tidak Ada !']);
        }
    }
}
