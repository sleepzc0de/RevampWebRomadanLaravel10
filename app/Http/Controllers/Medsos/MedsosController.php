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
                    $edit = route('medsos.edit', encrypt($query->id));
                    $hapus = route('medsos.destroy', encrypt($query->id));
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

        $data = medsos::all();
        return view('backend.medsos.index',compact(['data']));
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
                'nama_medsos' => 'required|unique:medsos',
                'link_medsos' => 'required',
                'logo_medsos' => 'required',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_medsos' => $request->nama_medsos,
                'link_medsos' => $request->link_medsos,
                'logo_medsos' => $request->logo_medsos,

            ];


            medsos::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Medsos Berhasil Ditambahkan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Medsos Gagal Ditambahkan! error :' . $e->getMessage()]);
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
        $medsos = medsos::findOrFail(decrypt($id));
        return view('backend.medsos.edit', compact(['medsos']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_medsos' => 'required',
                'link_medsos' => 'required',
                'logo_medsos' => 'required',
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
            return redirect()->route('medsos.index')->with(['failed' => 'medsos Gagal Di Update! error :' . $e->getMessage()]);
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
            return redirect()->route('medsos.index')->with('success', "medsos berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('medsos.index')->with(['failed' => 'medsos Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
