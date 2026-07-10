<?php

namespace App\Http\Controllers\MenuInformasiPublik;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuInformasiPublik\InfopublikHomeModel;
use App\Models\backend\MenuInformasiPublik\InformasiPublikModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class InformasiPublikController extends Controller
{
    // Helper function to sanitize HTML input
    private function sanitizeHtml($input)
    {
        // Remove all HTML tags except allowed ones
        $allowed_tags = '<p><br><strong><em><ul><li><ol><h1><h2><h3><h4><h5><h6>';
        $cleaned = strip_tags($input, $allowed_tags);

        // Hapus htmlspecialchars karena ini mengkonversi tag HTML yang valid menjadi entitas
        return $cleaned;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = InformasiPublikModel::all();
        $data2 = InfopublikHomeModel::all();
        $query = InformasiPublikModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'edit' => route('informasi-publik.edit', encrypt($query->id)),
                        'destroy' => route('informasi-publik.destroy', encrypt($query->id)),
                    ])->render();
                })

                ->rawColumns(['opsi'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.infopub.index', compact(['data', 'data2']));
    }

    public function indexHome()
    {
        $query = InfopublikHomeModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'preview' => route('informasi-publik.show', encrypt($query->id)),
                        'edit' => route('informasi-publik.edit-home', encrypt($query->id)),
                        'destroy' => route('informasi-publik.delete-home', encrypt($query->id)),
                    ])->render();
                })

                ->rawColumns(['opsi'])
                ->addIndexColumn()
                ->make(true);
        }
        // return view('backend.infopub.index');
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

    public function edit_home(string $id)
    {
        $data = InfopublikHomeModel::findOrFail(decrypt($id));

        return view('backend.infopub.edit-home', compact('data'));
    }

    public function update_home(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'judul' => 'required|max:255',
                'isi' => 'required|max:3000',
            ]);

            // Sanitize HTML input
            $judul = $this->sanitizeHtml($request->judul);
            $isi = $this->sanitizeHtml($request->isi);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $judul,
                'isi' => $isi,
            ];

            InfopublikHomeModel::findOrFail(decrypt($id))->update($data);

            return redirect()->route('informasi-publik.index')->with('success', 'Data Home Informasi Publik berhasil diupdate!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('informasi-publik.index')->with(['failed' => 'Data Home Informasi Publik Gagal Di Update!']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'judul_list_informasi' => 'required|max:255',
                'isi_list_informasi' => 'required|max:1000',
                'link_list_informasi' => 'required|max:255',
            ]);

            // Sanitize HTML input
            $judul = $this->sanitizeHtml($request->judul_list_informasi);
            $isi = $this->sanitizeHtml($request->isi_list_informasi);
            $link = filter_var($request->link_list_informasi, FILTER_SANITIZE_URL);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul_list_informasi' => $judul,
                'isi_list_informasi' => $isi,
                'link_list_informasi' => $link,
            ];

            InformasiPublikModel::create($data);

            return redirect()->back()->with(['success' => 'Data Informasi Publik Berhasil Disimpan!']);
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Data Informasi Publik Gagal Disimpan!']);
        }
    }

    public function store_home(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'judul' => 'required|max:255',
                'isi' => 'required|max:3000',
            ]);

            // Sanitize HTML input dengan tag yang diizinkan
            $judul = $this->sanitizeHtml($request->judul);
            $isi = $this->sanitizeHtml($request->isi);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $judul,
                'isi' => $isi,
            ];

            InfopublikHomeModel::create($data);

            return redirect()->back()->with(['success' => 'Data Informasi Publik Home Berhasil Disimpan!']);
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Data Informasi Publik Home Gagal Disimpan!']);
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
        $infopub = InformasiPublikModel::findOrFail(decrypt($id));

        // dd($kegiatan);
        return view('backend.infopub.edit', compact(['infopub']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'judul_list_informasi' => 'required|max:255',
                'isi_list_informasi' => 'required|max:1000',
                'link_list_informasi' => 'required|max:255',
            ]);

            // Sanitize HTML input
            $judul = $this->sanitizeHtml($request->judul_list_informasi);
            $isi = $this->sanitizeHtml($request->isi_list_informasi);
            $link = filter_var($request->link_list_informasi, FILTER_SANITIZE_URL);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul_list_informasi' => $judul,
                'isi_list_informasi' => $isi,
                'link_list_informasi' => $link,
            ];

            InformasiPublikModel::findOrFail(decrypt($id))->update($data);

            return redirect()->route('informasi-publik.index')->with('success', 'Data Informasi Publik berhasil diupdate!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('informasi-publik.index')->with(['failed' => 'Data Informasi Publik Gagal Di Update!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // $data_gambar = InformasiPublikModel::findOrFail(decrypt($id));
            // File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);
            InformasiPublikModel::findOrFail(decrypt($id))->delete();

            return redirect()->route('informasi-publik.index')->with('success', 'Informasi Publik berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('informasi-publik.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada !']);
        }
    }

    public function delete_home(string $id)
    {
        try {
            InfopublikHomeModel::findOrFail(decrypt($id))->delete();

            return redirect()->route('informasi-publik.index')->with('success', 'Informasi Publik Home berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('informasi-publik.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada !']);
        }
    }
}
