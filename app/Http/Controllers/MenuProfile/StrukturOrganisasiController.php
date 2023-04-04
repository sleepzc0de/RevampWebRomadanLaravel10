<?php

namespace App\Http\Controllers\MenuProfile;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuProfile\StrukturOrganisasiModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StrukturOrganisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = StrukturOrganisasiModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                ->addColumn('image_struktur', function ($query) {
                    $url = asset('storage/romadan_gambar_web/' . $query->image);
                    return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center""/></a>';
                })
                ->addColumn('opsi', function ($query) {
                    $edit = route('struktur-organisasi.edit', encrypt($query->id));
                    $hapus = route('struktur-organisasi.destroy', encrypt($query->id));
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


                ->rawColumns(['opsi', 'image_struktur'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.struktur.index');
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
            // VALIDASI DATA
            $request->validate([
                'judul' => 'required',
                'struktur' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:1000',
            ]);

            //UPLOAD IMAGE
            $image = $request->file('image');
            $image->storeAs('public/romadan_gambar_web', $image->hashName());

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $request->judul,
                'struktur' => $request->struktur,
                'image' => $image->hashName(),

            ];


            StrukturOrganisasiModel::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Struktur Organisasi Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Struktur Organisasi Gagal Ditambahkan! error :' . $e->getMessage()]);
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
        $struktur = StrukturOrganisasiModel::findOrFail(decrypt($id));
        return view('backend.struktur.edit', compact('struktur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'judul' => 'required',
                'struktur' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,svg|max:1000',
            ]);
            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $request->judul,
                'struktur' => $request->struktur,

            ];
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,svg|max:1000',
                ], [
                    'image.mimes' => 'Gambar hanya diperbolehkaan berekstensi JPEG, JPG, PNG, SVG',
                ]);

                //UPLOAD IMAGE
                $image = $request->file('image');
                $image->storeAs('public/romadan_gambar_web', $image->hashName());

                $data_gambar = StrukturOrganisasiModel::findOrFail(decrypt($id));
                File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);

                $data = [
                    'image' => $image->hashName(),
                ];
            }

            StrukturOrganisasiModel::findOrFail(decrypt($id))->update($data);
            return redirect()->route('struktur-organisasi.index')->with('success', "struktur-organisasi berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('struktur-organisasi.index')->with(['failed' => 'struktur-organisasi Gagal Di Update! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data_gambar = StrukturOrganisasiModel::findOrFail(decrypt($id));
            File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);
            StrukturOrganisasiModel::findOrFail(decrypt($id))->delete();
            return redirect()->route('struktur-organisasi.index')->with('success', "Visi Misi berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('struktur-organisasi.index')->with(['failed' => 'Visi Misi Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
