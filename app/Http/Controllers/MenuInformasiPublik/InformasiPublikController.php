<?php

namespace App\Http\Controllers\MenuInformasiPublik;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuInformasiPublik\InfopublikHomeModel;
use App\Models\backend\MenuInformasiPublik\InformasiPublikModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InformasiPublikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = InformasiPublikModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('opsi', function ($query) {
                    $preview = route('informasi-publik.show', encrypt($query->id));
                    $edit = route('informasi-publik.edit', encrypt($query->id));
                    $hapus = route('informasi-publik.destroy', encrypt($query->id));
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


                ->rawColumns(['opsi'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.infopub.index');
    }
    public function indexHome()
    {
        $query = InfopublikHomeModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('opsi', function ($query) {
                    $preview = route('informasi-publik.show', encrypt($query->id));
                    $edit = route('informasi-publik.edit', encrypt($query->id));
                    $hapus = route('informasi-publik.delete-home', encrypt($query->id));
                    return '<div class="d-inline-flex">
											<div class="dropdown">
												<a href="#" class="text-body" data-bs-toggle="dropdown">
													<i class="ph-list"></i>
												</a>

												<div class="dropdown-menu dropdown-menu-end">
													
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


                ->rawColumns(['opsi'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.infopub.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.infopub.create');
    }

    public function create_home()
    {
        return view('backend.infopub.create-home');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'judul_list_informasi' => 'required',
                'isi_list_informasi' => 'required',
                'link_list_informasi' => 'required'
            ]);


            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul_list_informasi' => $request->judul_list_informasi,
                'isi_list_informasi' => $request->isi_list_informasi,
                'link_list_informasi' => $request->link_list_informasi

            ];


            InformasiPublikModel::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Data Informasi Publik Berhasil Disimpan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Data Informasi Publik Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    public function store_home(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'judul' => 'required',
                'isi' => 'required',
            ]);


            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $request->judul,
                'isi' => $request->isi,

            ];


            InfopublikHomeModel::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Data Informasi Publik Home Berhasil Disimpan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Data Informasi Publik Home Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('infopub.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kegiatan = InformasiPublikModel::findOrFail(decrypt($id));
        // dd($kegiatan);
        return view('backend.infopub.edit', compact(['kegiatan']));
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
                'isi' => 'required',
                'judul_list_informasi' => 'required',
                'isi_list_informasi' => 'required',
                'link_list_informasi' => 'required'
            ]);


            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $request->judul,
                'isi' => $request->isi,
                'judul_list_informasi' => $request->judul_list_informasi,
                'isi_list_informasi' => $request->isi_list_informasi,
                'link_list_informasi' => $request->link_list_informasi

            ];

            InformasiPublikModel::findOrFail(decrypt($id))->update($data);
            // $berita = Berita::find($id)->update($data);
            return redirect()->route('informasi-publik.index')->with('success', "Data Informasi Publik berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('informasi-publik.index')->with(['failed' => 'Data Informasi Publik Gagal Di Update! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data_gambar = InformasiPublikModel::findOrFail(decrypt($id));
            File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);
            InformasiPublikModel::findOrFail(decrypt($id))->delete();
            return redirect()->route('informasi-publik.index')->with('success', "Informasi Publik berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('informasi-publik.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }

    public function delete_home(string $id)
    {
        try {
            InfopublikHomeModel::findOrFail(decrypt($id))->delete();
            return redirect()->route('informasi-publik.index')->with('success', "Informasi Publik Home berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('informasi-publik.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
