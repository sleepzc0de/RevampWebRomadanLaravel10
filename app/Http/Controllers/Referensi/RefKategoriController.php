<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\backend\ref_kategori;
use Exception;
use Illuminate\Http\Request;

class RefKategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = ref_kategori::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                // ->addColumn('image_file', function ($query) {
                //     $url = asset('storage/romadan_file_web/' . $query->image_file);
                //     return '<a href="' . $url . '">' . $query->nama_file . '</a>';
                // })
                ->addColumn('opsi', function ($query) {
                    $edit = route('kategori.edit', encrypt($query->id_kategori));
                    $hapus = route('kategori.destroy', encrypt($query->id_kategori));
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
                'nama_kategori' => 'required|unique:ref_kategori',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_kategori' => $request->nama_kategori,

            ];


            ref_kategori::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Kategori Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Kategori Gagal Ditambahkan! error :' . $e->getMessage()]);
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
        $kategori = ref_kategori::findOrFail(decrypt($id));
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
                'nama_kategori' => 'required',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_kategori' => $request->nama_kategori,

            ];
            ref_kategori::findOrFail(decrypt($id))->update($data);
            return redirect()->route('kategori.index')->with('success', "Kategori $request->nama_kategori berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('kategori.index')->with(['failed' => 'Kategori Gagal Di Update! error :' . $e->getMessage()]);
            // return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            ref_kategori::findOrFail(decrypt($id))->delete();
            return redirect()->route('kategori.index')->with('success', "Kategori berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('kategori.index')->with(['failed' => 'Kategori Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
