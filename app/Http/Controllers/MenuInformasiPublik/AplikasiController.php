<?php

namespace App\Http\Controllers\MenuInformasiPublik;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuInformasiPublik\AplikasiModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AplikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = AplikasiModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                ->addColumn('image_aplikasi', function ($query) {
                    $url = asset('storage/romadan_gambar_web/' . $query->image);
                    return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center""/></a>';
                })

                ->addColumn('opsi', function ($query) {
                    $preview = route('aplikasi.show', encrypt($query->id));
                    $edit = route('aplikasi.edit', encrypt($query->id));
                    $hapus = route('aplikasi.destroy', encrypt($query->id));
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
										</div>
                ';
                })

                ->rawColumns(['opsi', 'image_aplikasi',])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.infopub.aplikasi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.infopub.aplikasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'judul_aplikasi' => 'required|unique:aplikasi',
                'sub_judul_aplikasi' => 'required',
                'link_aplikasi' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,svg'
            ]);

            //UPLOAD IMAGE
            $image = $request->file('image');
            $image->storeAs('public/romadan_gambar_web', $image->hashName());

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul_aplikasi' => $request->judul_aplikasi,
                'sub_judul_aplikasi' => $request->sub_judul_aplikasi,
                'link_aplikasi' => $request->link_aplikasi,
                'image' => $image->hashName(),

            ];


            AplikasiModel::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Data Aplikasi Berhasil Disimpan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Data Aplikasi Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = AplikasiModel::findOrFail(decrypt($id));
        return view('backend.infopub.aplikasi.edit', compact(['data']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'judul_aplikasi' => 'required',
                'sub_judul_aplikasi' => 'required',
                'link_aplikasi' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,svg|max:1000',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul_aplikasi' => $request->judul_aplikasi,
                'sub_judul_aplikasi' => $request->sub_judul_aplikasi,
                'link_aplikasi' => $request->sub_judul_aplikasi,

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

                $data_gambar = AplikasiModel::findOrFail(decrypt($id));
                File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);

                $data = [
                    'image' => $image->hashName(),
                ];
            }
            AplikasiModel::findOrFail(decrypt($id))->update($data);
            // $berita = Berita::find($id)->update($data);
            return redirect()->route('aplikasi.index')->with('success', "Aplikasi $request->judul_aplikasi berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('aplikasi.index')->with(['failed' => 'Data Aplikasi Gagal Di Update! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data_gambar = AplikasiModel::findOrFail(decrypt($id));
            File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);
            AplikasiModel::findOrFail(decrypt($id))->delete();
            return redirect()->route('aplikasi.index')->with('success', "Aplikasi berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('aplikasi.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
