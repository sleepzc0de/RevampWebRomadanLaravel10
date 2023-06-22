<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuReferensi\ref_jenis_peraturan;
use Exception;
use Illuminate\Http\Request;

class RefJenisPeraturanController extends Controller
{
    public function index()
    {
        $query = ref_jenis_peraturan::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                // ->addColumn('image_file', function ($query) {
                //     $url = asset('storage/romadan_file_web/' . $query->image_file);
                //     return '<a href="' . $url . '">' . $query->nama_file . '</a>';
                // })
                ->addColumn('opsi', function ($query) {
                    $edit = route('jenis-peraturan.edit', encrypt($query->id_jenis_peraturan));
                    $hapus = route('jenis-peraturan.destroy', encrypt($query->id_jenis_peraturan));
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
        return view('backend.referensi.jenis_peraturan.index');
    }

    public function create()
    {
        return view('backend.referensi.jenis_peraturan.create');
    }

    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_jenis_peraturan' => 'required|unique:ref_jenis_peraturan',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_jenis_peraturan' => $request->nama_jenis_peraturan,

            ];


            ref_jenis_peraturan::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Jenis Peraturan Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Jenis Peraturan Gagal Ditambahkan! error :' . $e->getMessage()]);
        }
    }

    public function edit(string $id)
    {
        $peraturan = ref_jenis_peraturan::findOrFail(decrypt($id));
        return view('backend.referensi.jenis_peraturan.edit', compact(['peraturan']));
    }

    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_jenis_peraturan' => 'required',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_jenis_peraturan' => $request->nama_jenis_peraturan,

            ];
            ref_jenis_peraturan::findOrFail(decrypt($id))->update($data);
            return redirect()->route('jenis-peraturan.index')->with('success', "Jenis Peraturan berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('jenis-peraturan.index')->with(['failed' => 'Jenis Peraturan Gagal Di Update! error :' . $e->getMessage()]);
            // return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            ref_jenis_peraturan::findOrFail(decrypt($id))->delete();
            return redirect()->route('jenis-peraturan.index')->with('success', "Jenis Peraturan berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('jenis-peraturan.index')->with(['failed' => 'Jenis Peraturan Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
