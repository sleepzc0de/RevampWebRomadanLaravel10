<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Models\backend\file as BackendFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = BackendFile::select('*');
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
                'image_file' => 'required|mimes:csv,xlx,xls,xlsx,pdf,zip,rar|max:100000',
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


            BackendFile::create($data);

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
        $file = BackendFile::findOrFail(decrypt($id));
        return view('backend.file.edit', compact(['file']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nama_file' => 'required',
                'image_file' => 'mimes:csv,xlx,xls,xlsx,pdf,zip,rar|max:250000',
                'isi_file' => 'required',
            ]);

            // // SLUG

            // $slug = Str::slug($request->judul);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_file' => $request->nama_file,
                'isi_file' => $request->isi_file

            ];
            if ($request->hasFile('image_file')) {
                $request->validate([
                    'image_file' => 'mimes:csv,xlx,xls,xlsx,pdf,zip,rar|max:250000',
                ], [
                    'image_file.mimes' => 'File hanya diperbolehkaan berekstensi CSV, XLX, XLS, XLSX, PDF, ZIP, RAR',
                ]);

                //UPLOAD IMAGE
                $image = $request->file('image_file');
                $image->storeAs('public/romadan_file_web', $image->hashName());

                $data_gambar = BackendFile::findOrFail(decrypt($id));
                File::delete(public_path('storage/romadan_file_web/') . $data_gambar->image_file);

                $data = [
                    'image_file' => $image->hashName(),
                ];
            }

            BackendFile::findOrFail(decrypt($id))->update($data);
            // $berita = Berita::find($id)->update($data);
            return redirect()->route('file.index')->with('success', "File $request->nama_file berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('file.index')->with(['failed' => 'Data File Gagal Di Update! error :' . $e->getMessage()]);
            // return redirect()->back()->with(['failed' => 'Data File Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // $data = [
            //     'status' => 3,
            // // ];
            // BackendFile::findOrFail(decrypt($id))->update($data);
            BackendFile::findOrFail(decrypt($id))->delete();
            return redirect()->route('file.index')->with('success', "File berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('file.index')->with(['failed' => 'File Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }

    public function fileSampah(Request $request)
    {
        $query = BackendFile::onlyTrashed()->select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                ->addColumn('image_file', function ($query) {
                    $url = asset('storage/romadan_file_web/' . $query->image_file);
                    return '<a href="' . $url . '">' . $query->nama_file . '</a>';
                })
                ->addColumn('opsi', function ($query) {
                    $restore = route('file.restore', encrypt($query->id));
                    $paksahapus = route('file.force-delete-sampah', encrypt($query->id));
                    return '<div class="d-inline-flex">
											<div class="dropdown">
												<a href="#" class="text-body" data-bs-toggle="dropdown">
													<i class="ph-list"></i>
												</a>

												<div class="dropdown-menu dropdown-menu-end">
													<form action="' . $restore . '" method="POST">
													' . @csrf_field() . '
													<button type="submit" name="submit" class="dropdown-item"> <i class="ph-trash me-2"></i> Restore</button>
													</form>
													<form action="' . $paksahapus . '" method="POST">
													' . @csrf_field() . '
													' . @method_field('DELETE') . '
													<button type="submit" name="submit" class="dropdown-item"> <i class="ph-trash me-2"></i> Paksa Hapus</button>
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
        return view('backend.file.sampah');
    }

    public function restore($id)
    {
        try {
            // $data = [
            //     'status' => 2,
            // ];
            // BackendFile::onlyTrashed()->findOrFail(decrypt($id))->update($data);
            BackendFile::onlyTrashed()->findOrFail(decrypt($id))->restore();
            return redirect()->route('file.sampah')->with('success', "Data File berhasil direstore!, silahkan cek pada berita aktif yah guys!");
        } catch (Exception $e) {
            return redirect()->route('file.sampah')->with(['failed' => 'Data File GAGAL di Restore ! error :' . $e->getMessage()]);
        }
    }

    public function restoreAll()
    {
        try {
            // $data = [
            //     'status' => 2,
            // ];
            // BackendFile::onlyTrashed()->update($data);
            BackendFile::onlyTrashed()->restore();
            return redirect()->route('file.sampah')->with('success', "Semua Data File berhasil direstore!, silahkan cek pada file aktif yah guys!");
        } catch (Exception $e) {
            return redirect()->route('file.sampah')->with(['failed' => 'Semua Data File GAGAL di Restore ! error :' . $e->getMessage()]);
        }
    }

    public function forceDeleteSampah($id)
    {
        try {
            $data_file = BackendFile::withTrashed()->findOrFail(decrypt($id));
            File::delete(public_path('storage/romadan_file_web/') . $data_file->image_file);
            BackendFile::withTrashed()->findOrFail(decrypt($id))->forceDelete();
            return redirect()->route('file.sampah')->with('success', "File Berhasil dihapus PERMANEN");
        } catch (Exception $e) {
            return redirect()->route('file.sampah')->with(['failed' => 'File Gagal dihapus Permanen ! error :' . $e->getMessage()]);
        }
    }
}
