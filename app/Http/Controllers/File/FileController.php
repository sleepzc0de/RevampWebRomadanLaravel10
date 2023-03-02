<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Models\backend\file;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = file::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                ->addColumn('image_file', function ($query) {
                    $url = asset('storage/romadan_file_web/' . $query->image_file);
                    return '<a href="' . $url . '">' . $query->nama_file . '</a>';
                })
                ->addColumn('opsi', function ($query) {
                    $preview = route('file.show', encrypt($query->id));
                    $edit = route('file.edit', encrypt($query->id));
                    $hapus = route('file.destroy', encrypt($query->id));
                    return '<div class="d-inline-flex">
											<div class="dropdown">
												<a href="#" class="text-body" data-bs-toggle="dropdown">
													<i class="ph-list"></i>
												</a>

												<div class="dropdown-menu dropdown-menu-end">
													<a href="' . $preview . '" class="dropdown-item">
														<i class="ph-detective me-2"></i>
														Preview
													</a>
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


                ->rawColumns(['opsi', 'image_file'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.file.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.file.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_file' => 'required|unique:file',
                // 'sub_judul' => 'required',
                // 'kategori' => 'required',
                'image_file' => 'required|mimes:csv,xlx,xls,xlsx,pdf|max:100000',
                'isi_file' => 'required',
            ]);

            //UPLOAD IMAGE
            $image = $request->file('image_file');
            $image->storeAs('public/romadan_file_web', $image->hashName());

            // // SLUG

            // $slug = Str::slug($request->judul);


            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_file' => $request->nama_file,
                'isi_file' => $request->isi_file,
                'image_file' => $image->hashName(),
                'pembuat_file' => Auth::user()->name,

            ];


            file::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Data File Berhasil Disimpan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
