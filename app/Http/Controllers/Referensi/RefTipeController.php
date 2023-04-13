<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\backend\ref_tipe;
use Exception;
use Illuminate\Http\Request;

class RefTipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = ref_tipe::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                // ->addColumn('image_file', function ($query) {
                //     $url = asset('storage/romadan_file_web/' . $query->image_file);
                //     return '<a href="' . $url . '">' . $query->nama_file . '</a>';
                // })
                ->addColumn('opsi', function ($query) {
                    $edit = route('tipe.edit', encrypt($query->id_tipe));
                    $hapus = route('tipe.destroy', encrypt($query->id_tipe));
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
        return view('backend.referensi.tipe.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.referensi.tipe.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_tipe' => 'required|unique:ref_tipe',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_tipe' => $request->nama_tipe,

            ];


            ref_tipe::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Tipe Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Tipe Gagal Ditambahkan! error :' . $e->getMessage()]);
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
        $tipe = ref_tipe::findOrFail(decrypt($id));
        return view('backend.referensi.tipe.edit', compact(['tipe']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_tipe' => 'required',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_tipe' => $request->nama_tipe,

            ];
            ref_tipe::findOrFail(decrypt($id))->update($data);
            return redirect()->route('tipe.index')->with('success', "Tipe $request->nama_tipe berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('tipe.index')->with(['failed' => 'Tipe Gagal Di Update! error :' . $e->getMessage()]);
            // return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            ref_tipe::findOrFail(decrypt($id))->delete();
            return redirect()->route('tipe.index')->with('success', "Tipe berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('tipe.index')->with(['failed' => 'Tipe Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
