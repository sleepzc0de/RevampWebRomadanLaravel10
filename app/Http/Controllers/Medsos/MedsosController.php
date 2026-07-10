<?php

namespace App\Http\Controllers\Medsos;

use App\Http\Controllers\Controller;
use App\Models\medsos\medsos;
use Exception;
use Illuminate\Http\Request;

class MedsosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = medsos::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                // ->addColumn('image_file', function ($query) {
                //     $url = asset('storage/romadan_file_web/' . $query->image_file);
                //     return '<a href="' . $url . '">' . $query->nama_file . '</a>';
                // })
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'edit' => route('medsos.edit', encrypt($query->id)),
                        'destroy' => route('medsos.destroy', encrypt($query->id)),
                    ])->render();
                })

                ->editColumn('created_at', function ($query) {
                    return date('d-M-Y H:i:s', strtotime($query->created_at));
                })

                ->rawColumns(['opsi'])
                ->addIndexColumn()
                ->make(true);
        }

        $data = medsos::all();

        return view('backend.medsos.index', compact(['data']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.medsos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_medsos' => 'required|unique:medsos|max:255',
                'link_medsos' => 'required|max:1000',
                'logo_medsos' => 'required|max:255',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_medsos' => $request->nama_medsos,
                'link_medsos' => $request->link_medsos,
                'logo_medsos' => $request->logo_medsos,

            ];

            medsos::create($data);

            // redirect to index
            return redirect()->back()->with(['success' => 'Medsos Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Medsos Gagal Ditambahkan!']);
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
        $medsos2 = medsos::findOrFail(decrypt($id));

        return view('backend.medsos.edit', compact(['medsos2']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_medsos' => 'required|max:255',
                'link_medsos' => 'required|max:1000',
                'logo_medsos' => 'required|max:255',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_medsos' => $request->nama_medsos,
                'link_medsos' => $request->link_medsos,
                'logo_medsos' => $request->logo_medsos,

            ];
            medsos::findOrFail(decrypt($id))->update($data);

            return redirect()->route('medsos.index')->with('success', "medsos $request->nama_medsos berhasil diupdate!");
        } catch (Exception $e) {
            report($e);

            return redirect()->route('medsos.index')->with(['failed' => 'medsos Gagal Di Update!']);
            // return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            medsos::findOrFail(decrypt($id))->delete();

            return redirect()->route('medsos.index')->with('success', 'medsos berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('medsos.index')->with(['failed' => 'medsos Yang Dihapus Tidak Ada !']);
        }
    }
}
