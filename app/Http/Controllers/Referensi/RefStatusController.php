<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\backend\ref_status;
use Exception;
use Illuminate\Http\Request;

class RefStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = ref_status::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                // ->addColumn('image_file', function ($query) {
                //     $url = asset('storage/romadan_file_web/' . $query->image_file);
                //     return '<a href="' . $url . '">' . $query->nama_file . '</a>';
                // })
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'edit' => route('status.edit', encrypt($query->id_status)),
                        'destroy' => route('status.destroy', encrypt($query->id_status)),
                    ])->render();
                })

                ->editColumn('created_at', function ($query) {
                    return date('d-M-Y H:i:s', strtotime($query->created_at));
                })

                ->rawColumns(['opsi'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.referensi.status.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.referensi.status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_status' => 'required|unique:ref_status|max:255',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_status' => $request->nama_status,

            ];

            ref_status::create($data);

            // redirect to index
            return redirect()->back()->with(['success' => 'Status Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Status Gagal Ditambahkan!']);
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
        $status = ref_status::findOrFail(decrypt($id));

        return view('backend.referensi.status.edit', compact(['status']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_status' => 'required|max:255',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_status' => $request->nama_status,

            ];
            ref_status::findOrFail(decrypt($id))->update($data);

            return redirect()->route('status.index')->with('success', "Status $request->nama_status berhasil diupdate!");
        } catch (Exception $e) {
            report($e);

            return redirect()->route('status.index')->with(['failed' => 'Status Gagal Di Update!']);
            // return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            ref_status::findOrFail(decrypt($id))->delete();

            return redirect()->route('status.index')->with('success', 'Status berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('status.index')->with(['failed' => 'Status Yang Dihapus Tidak Ada !']);
        }
    }
}
