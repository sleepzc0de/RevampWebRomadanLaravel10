<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuReferensi\ref_peraturan_status;
use Exception;
use Illuminate\Http\Request;

class RefPeraturanStatusController extends Controller
{
    public function index()
    {
        $query = ref_peraturan_status::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                // ->addColumn('image_file', function ($query) {
                //     $url = asset('storage/romadan_file_web/' . $query->image_file);
                //     return '<a href="' . $url . '">' . $query->nama_file . '</a>';
                // })
                ->addColumn('opsi', function ($query) {
                    $edit = route('status-peraturan.edit', encrypt($query->id_ref_peraturan_status));
                    $hapus = route('status-peraturan.destroy', encrypt($query->id_ref_peraturan_status));
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
        return view('backend.referensi.status_peraturan.index');
    }

    public function create()
    {
        return view('backend.referensi.status_peraturan.create');
    }

    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_peraturan_status' => 'required|unique:ref_peraturan_status',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_peraturan_status' => $request->nama_peraturan_status,

            ];


            ref_peraturan_status::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Status Peraturan Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Status Peraturan Gagal Ditambahkan! error :' . $e->getMessage()]);
        }
    }

    public function edit(string $id)
    {
        $peraturan = ref_peraturan_status::findOrFail(decrypt($id));
        return view('backend.referensi.status_peraturan.edit', compact(['peraturan']));
    }

    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_peraturan_status' => 'required',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_peraturan_status' => $request->nama_peraturan_status,

            ];
            ref_peraturan_status::findOrFail(decrypt($id))->update($data);
            return redirect()->route('status-peraturan.index')->with('success', "Status Peraturan berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('status-peraturan.index')->with(['failed' => 'Status Peraturan Gagal Di Update! error :' . $e->getMessage()]);
            // return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            ref_peraturan_status::findOrFail(decrypt($id))->delete();
            return redirect()->route('status-peraturan.index')->with('success', "Status Peraturan berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('status-peraturan.index')->with(['failed' => 'Status Peraturan Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
