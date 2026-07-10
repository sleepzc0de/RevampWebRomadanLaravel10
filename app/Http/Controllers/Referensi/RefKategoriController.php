<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\backend\RefKategori;
use Exception;
use Illuminate\Http\Request;

class RefKategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = RefKategori::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                // ->addColumn('image_file', function ($query) {
                //     $url = asset('storage/romadan_file_web/' . $query->image_file);
                //     return '<a href="' . $url . '">' . $query->nama_file . '</a>';
                // })
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'edit' => route('kategori.edit', encrypt($query->id_kategori)),
                        'destroy' => route('kategori.destroy', encrypt($query->id_kategori)),
                    ])->render();
                })

                ->editColumn('created_at', function ($query) {
                    return date('d-M-Y H:i:s', strtotime($query->created_at));
                })

                ->rawColumns(['opsi'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.referensi.kategori.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.referensi.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_kategori' => 'required|unique:ref_kategori|max:255',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_kategori' => $request->nama_kategori,

            ];

            RefKategori::create($data);

            // redirect to index
            return redirect()->back()->with(['success' => 'Kategori Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Kategori Gagal Ditambahkan!']);
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
        $kategori = RefKategori::findOrFail(decrypt($id));

        return view('backend.referensi.kategori.edit', compact(['kategori']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_kategori' => 'required|max:255',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_kategori' => $request->nama_kategori,

            ];
            RefKategori::findOrFail(decrypt($id))->update($data);

            return redirect()->route('kategori.index')->with('success', "Kategori $request->nama_kategori berhasil diupdate!");
        } catch (Exception $e) {
            report($e);

            return redirect()->route('kategori.index')->with(['failed' => 'Kategori Gagal Di Update!']);
            // return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            RefKategori::findOrFail(decrypt($id))->delete();

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('kategori.index')->with(['failed' => 'Kategori Yang Dihapus Tidak Ada !']);
        }
    }
}
