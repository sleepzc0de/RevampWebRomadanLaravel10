<?php

namespace App\Http\Controllers\MenuInformasiPublik;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuInformasiPublik\AplikasiModel;
use Exception;
use Illuminate\Http\Request;
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
                    $url = asset('storage/romadan_gambar_web/'.$query->image);

                    return '<a href="'.$url.'"><img src="'.$url.'" border="0" width="100" class="img-rounded" align="center""/></a>';
                })

                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'preview' => route('aplikasi.show', encrypt($query->id)),
                        'edit' => route('aplikasi.edit', encrypt($query->id)),
                        'destroy' => route('aplikasi.destroy', encrypt($query->id)),
                    ])->render();
                })

                ->rawColumns(['opsi', 'image_aplikasi'])
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
                'judul_aplikasi' => 'required|unique:aplikasi|max:100',
                'sub_judul_aplikasi' => 'required|max:100',
                'link_aplikasi' => 'required|max:1000|url',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:1000',
            ]);

            // Filter HTML tags from input
            $filteredJudul = strip_tags($request->judul_aplikasi);
            $filteredSubJudul = strip_tags($request->sub_judul_aplikasi);
            $filteredLink = strip_tags($request->link_aplikasi);

            // UPLOAD IMAGE
            $image = $request->file('image');
            $image->storeAs('public/romadan_gambar_web', $image->hashName());

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul_aplikasi' => $filteredJudul,
                'sub_judul_aplikasi' => $filteredSubJudul,
                'link_aplikasi' => $filteredLink,
                'image' => $image->hashName(),
            ];

            AplikasiModel::create($data);

            // redirect to index
            return redirect()->back()->with(['success' => 'Data Aplikasi Berhasil Disimpan!']);
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Data Aplikasi Gagal Disimpan!']);
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
                'judul_aplikasi' => 'required|max:100',
                'sub_judul_aplikasi' => 'required|max:100',
                'link_aplikasi' => 'required|max:1000|url',
                'image' => 'image|mimes:jpeg,png,jpg|max:1000',
            ]);

            // Filter HTML tags from input
            $filteredJudul = strip_tags($request->judul_aplikasi);
            $filteredSubJudul = strip_tags($request->sub_judul_aplikasi);
            $filteredLink = strip_tags($request->link_aplikasi);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul_aplikasi' => $filteredJudul,
                'sub_judul_aplikasi' => $filteredSubJudul,
                'link_aplikasi' => $filteredLink,
            ];

            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg|max:1000',
                ], [
                    'image.mimes' => 'Gambar hanya diperbolehkaan berekstensi JPEG, JPG, PNG, SVG',
                ]);

                // UPLOAD IMAGE
                $image = $request->file('image');
                $image->storeAs('public/romadan_gambar_web', $image->hashName());

                $data_gambar = AplikasiModel::findOrFail(decrypt($id));
                File::delete(public_path('storage/romadan_gambar_web/').$data_gambar->image);

                $data = [
                    'image' => $image->hashName(),
                ];
            }
            AplikasiModel::findOrFail(decrypt($id))->update($data);

            // $berita = Berita::find($id)->update($data);
            return redirect()->route('aplikasi.index')->with('success', "Aplikasi $request->judul_aplikasi berhasil diupdate!");
        } catch (Exception $e) {
            report($e);

            return redirect()->route('aplikasi.index')->with(['failed' => 'Data Aplikasi Gagal Di Update!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data_gambar = AplikasiModel::findOrFail(decrypt($id));
            File::delete(public_path('storage/romadan_gambar_web/').$data_gambar->image);
            AplikasiModel::findOrFail(decrypt($id))->delete();

            return redirect()->route('aplikasi.index')->with('success', 'Aplikasi berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('aplikasi.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada !']);
        }
    }
}
