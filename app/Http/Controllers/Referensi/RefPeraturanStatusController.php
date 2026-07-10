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
                    return view('components.datatable-actions', [
                        'edit' => route('status-peraturan.edit', encrypt($query->id_ref_peraturan_status)),
                        'destroy' => route('status-peraturan.destroy', encrypt($query->id_ref_peraturan_status)),
                    ])->render();
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
                'nama_peraturan_status' => 'required|unique:ref_peraturan_status|max:255',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_peraturan_status' => $request->nama_peraturan_status,

            ];

            ref_peraturan_status::create($data);

            // redirect to index
            return redirect()->back()->with(['success' => 'Status Peraturan Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Status Peraturan Gagal Ditambahkan!']);
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
                'nama_peraturan_status' => 'required|max:255',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_peraturan_status' => $request->nama_peraturan_status,

            ];
            ref_peraturan_status::findOrFail(decrypt($id))->update($data);

            return redirect()->route('status-peraturan.index')->with('success', 'Status Peraturan berhasil diupdate!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('status-peraturan.index')->with(['failed' => 'Status Peraturan Gagal Di Update!']);
            // return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            ref_peraturan_status::findOrFail(decrypt($id))->delete();

            return redirect()->route('status-peraturan.index')->with('success', 'Status Peraturan berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('status-peraturan.index')->with(['failed' => 'Status Peraturan Yang Dihapus Tidak Ada !']);
        }
    }
}
