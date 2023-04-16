<?php

namespace App\Http\Controllers\MenuKegiatan;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuKegiatan\KegiatanModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = KegiatanModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                ->addColumn('image_kegiatan', function ($query) {
                    $url = asset('storage/romadan_gambar_web/' . $query->image);
                    return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center""/></a>';
                })

                ->addColumn('file_kegiatan', function ($query) {
                    $url = asset('storage/romadan_file_web/' . $query->file);
                    return '<a href="' . $url . '">' . $query->judul . '</a>';
                })
                ->addColumn('opsi', function ($query) {
                    $preview = route('kegiatan.show', encrypt($query->id));
                    $edit = route('kegiatan.edit', encrypt($query->id));
                    $hapus = route('kegiatan.destroy', encrypt($query->id));
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

                ->editColumn('tanggal_mulai', function ($query) {
                    return date('d-F-Y H:i', strtotime($query->tanggal_mulai));
                })


                ->rawColumns(['opsi', 'image_kegiatan', 'file_kegiatan'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.kegiatan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'judul' => 'required|unique:kegiatan',
                'tempat' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:1000',
                'file' => 'required|mimes:doc,docx,ppt,pptx,csv,xlx,xls,xlsx,pdf,zip,rar|max:100000',
                'isi' => 'required',
                'tanggal_mulai' => 'required|date|date_format:Y-m-d\TH:i',
            ]);

            //UPLOAD IMAGE
            $image = $request->file('image');
            $image->storeAs('public/romadan_gambar_web', $image->hashName());

            //UPLOAD FILE
            $file = $request->file('file');
            $file->storeAs('public/romadan_file_web', $file->hashName());

            // SLUG

            $slug = Str::slug($request->judul);


            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $request->judul,
                'tempat' => $request->tempat,
                'image' => $image->hashName(),
                'file' => $file->hashName(),
                'isi' => $request->isi,
                'slug' => $slug,
                'tanggal_mulai' => Carbon::parse($request->tanggal_mulai)->format('Y-m-d H:i'),
                'tanggal_mulai' => Carbon::parse($request->tanggal_selesai)->format('Y-m-d H:i'),

            ];


            KegiatanModel::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Data Publikasi Berhasil Disimpan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Data Publikasi Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('kegiatan.index');
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
        try {
            $data_gambar = KegiatanModel::findOrFail(decrypt($id));
            File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);
            KegiatanModel::findOrFail(decrypt($id))->delete();
            return redirect()->route('kegiatan.index')->with('success', "Kegiatan berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('kegiatan.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
