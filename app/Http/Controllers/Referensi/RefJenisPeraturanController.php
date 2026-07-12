<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuReferensi\RefJenisPeraturan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefJenisPeraturanController extends Controller
{
    public function index()
    {
        $query = RefJenisPeraturan::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                // ->addColumn('image_file', function ($query) {
                //     $url = asset('storage/romadan_file_web/' . $query->image_file);
                //     return '<a href="' . $url . '">' . $query->nama_file . '</a>';
                // })
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'edit' => route('jenis-peraturan.edit', encrypt($query->id_jenis_peraturan)),
                        'destroy' => route('jenis-peraturan.destroy', encrypt($query->id_jenis_peraturan)),
                    ])->render();
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
                'nama_jenis_peraturan' => 'required|unique:ref_jenis_peraturan|max:255',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_jenis_peraturan' => $request->nama_jenis_peraturan,

            ];

            RefJenisPeraturan::create($data);

            // redirect to index
            return redirect()->back()->with(['success' => 'Jenis Peraturan Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Jenis Peraturan Gagal Ditambahkan!']);
        }
    }

    public function edit(string $id)
    {
        $peraturan = RefJenisPeraturan::findOrFail(decrypt($id));

        return view('backend.referensi.jenis_peraturan.edit', compact(['peraturan']));
    }

    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_jenis_peraturan' => ['required', 'max:255', Rule::unique('ref_jenis_peraturan')->ignore(decrypt($id), 'id_jenis_peraturan')],
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_jenis_peraturan' => $request->nama_jenis_peraturan,

            ];
            RefJenisPeraturan::findOrFail(decrypt($id))->update($data);

            return redirect()->route('jenis-peraturan.index')->with('success', 'Jenis Peraturan berhasil diupdate!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('jenis-peraturan.index')->with(['failed' => 'Jenis Peraturan Gagal Di Update!']);
            // return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            RefJenisPeraturan::findOrFail(decrypt($id))->delete();

            return redirect()->route('jenis-peraturan.index')->with('success', 'Jenis Peraturan berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('jenis-peraturan.index')->with(['failed' => 'Jenis Peraturan Yang Dihapus Tidak Ada !']);
        }
    }
}
