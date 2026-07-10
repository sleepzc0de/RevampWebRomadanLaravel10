<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\backend\RefTipe;
use Exception;
use Illuminate\Http\Request;

class RefTipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = RefTipe::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                // ->addColumn('image_file', function ($query) {
                //     $url = asset('storage/romadan_file_web/' . $query->image_file);
                //     return '<a href="' . $url . '">' . $query->nama_file . '</a>';
                // })
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'edit' => route('tipe.edit', encrypt($query->id_tipe)),
                        'destroy' => route('tipe.destroy', encrypt($query->id_tipe)),
                    ])->render();
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
                'nama_tipe' => 'required|unique:ref_tipe|max:255',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_tipe' => $request->nama_tipe,

            ];

            RefTipe::create($data);

            // redirect to index
            return redirect()->back()->with(['success' => 'Tipe Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Tipe Gagal Ditambahkan!']);
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
        $tipe = RefTipe::findOrFail(decrypt($id));

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
                'nama_tipe' => 'required|max:255',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_tipe' => $request->nama_tipe,

            ];
            RefTipe::findOrFail(decrypt($id))->update($data);

            return redirect()->route('tipe.index')->with('success', "Tipe $request->nama_tipe berhasil diupdate!");
        } catch (Exception $e) {
            report($e);

            return redirect()->route('tipe.index')->with(['failed' => 'Tipe Gagal Di Update!']);
            // return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            RefTipe::findOrFail(decrypt($id))->delete();

            return redirect()->route('tipe.index')->with('success', 'Tipe berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('tipe.index')->with(['failed' => 'Tipe Yang Dihapus Tidak Ada !']);
        }
    }
}
