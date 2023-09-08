<?php

namespace App\Http\Controllers\MenuPublikasi;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuPublikasi\PublikasiModel;
use App\Models\backend\ref_kategori;
use App\Models\backend\ref_status;
use App\Models\backend\ref_tipe;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PublikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = PublikasiModel::with(['kategori', 'status', 'tipe'])->select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                ->addColumn('image_publikasi', function ($query) {
                    $url = asset('storage/romadan_gambar_web/' . $query->image);
                    return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center""/></a>';
                })
                ->addColumn('opsi', function ($query) {
                    $preview = route('publikasi.show', encrypt($query->id));
                    $edit = route('publikasi.edit', encrypt($query->id));
                    $hapus = route('publikasi.destroy', encrypt($query->id));
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


                ->rawColumns(['opsi', 'image_publikasi'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.publikasi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $kategori = ModelsRef_kategori::get();
        $kategori = ref_kategori::get();
        $tipe = ref_tipe::get();
        return view('backend.publikasi.create', compact(['kategori', 'tipe']));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            // VALIDASI DATA
            $request->validate([
                'judul' => 'required|unique:publikasi',
                'sub_judul' => 'required',
                'kategori' => 'required',
                'tipe' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:1000',
                'isi' => 'required',
            ]);

            //UPLOAD IMAGE
            $image = $request->file('image');
            $image->storeAs('public/romadan_gambar_web', $image->hashName());

            // SLUG

            $slug = Str::slug($request->judul);


            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $request->judul,
                'sub_judul' => $request->sub_judul,
                'kategori' => $request->kategori,
                'tipe' => $request->tipe,
                'image' => $image->hashName(),
                'isi' => $request->isi,
                'status' => 2,
                'slug' => $slug,
                'penulis' => Auth::user()->name,

            ];


            PublikasiModel::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Data Publikasi Berhasil Disimpan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Data Publikasi Gagal Disimpan! error :' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PublikasiModel::findOrFail(decrypt($id));
        // dd($data);

        return view('backend.publikasi.show', compact(['data']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategori = ref_kategori::all();
        $status = ref_status::all();
        $tipe = ref_tipe::all();
        $publikasi = PublikasiModel::with(['kategori', 'status', 'tipe'])->findOrFail(decrypt($id));
        // dd($berita);
        return view('backend.publikasi.edit', compact(['publikasi', 'kategori', 'status', 'tipe']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'judul' => 'required',
                'sub_judul' => 'required',
                'kategori' => 'required',
                'tipe' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,svg|max:1000',
                'isi' => 'required',
            ]);

            // SLUG

            // $slug = Str::slug($request->judul);
             $slug = Str::slug($request->judul).'-'.Str::random(10).uniqid().Str::random(4);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'judul' => $request->judul,
                'sub_judul' => $request->sub_judul,
                'kategori' => $request->kategori,
                'tipe' => $request->tipe,
                'isi' => $request->isi,
                'status' => $request->status,
                'slug' => $slug,
                'pengedit' => Auth::user()->name,

            ];
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,svg|max:1000',
                ], [
                    'image.mimes' => 'Gambar hanya diperbolehkaan berekstensi JPEG, JPG, PNG, SVG',
                ]);

                //UPLOAD IMAGE
                $image = $request->file('image');
                $image->storeAs('public/romadan_gambar_web', $image->hashName());

                $data_gambar = PublikasiModel::findOrFail(decrypt($id));
                File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);

                $data = [
                    'image' => $image->hashName(),
                ];
            }

            PublikasiModel::findOrFail(decrypt($id))->update($data);
            // $berita = Berita::find($id)->update($data);
            return redirect()->route('publikasi.index')->with('success', "Publikasi $request->judul berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('publikasi.index')->with(['failed' => 'Data Publikasi Gagal Di Update! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = [
                'status' => 3,
            ];
            PublikasiModel::findOrFail(decrypt($id))->update($data);
            PublikasiModel::findOrFail(decrypt($id))->delete($data);
            return redirect()->route('publikasi.index')->with('success', "Publikasi berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('publikasi.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }

    public function publikasiSampah(Request $request)
    {
        $query = PublikasiModel::onlyTrashed()->with(['kategori', 'status', 'tipe']);
        // dd($query);
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('image_publikasi', function ($query) {
                    $url = asset('storage/romadan_gambar_web/' . $query->image);
                    return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center""/></a>';
                })
                ->addColumn('opsi', function ($query) {
                    // $preview = route('berita.show', $query->id);
                    $restore = route('publikasi.restore', encrypt($query->id));
                    $paksahapus = route('publikasi.force-delete', encrypt($query->id));
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
                ->rawColumns(['opsi', 'image_publikasi'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.publikasi.sampah', compact('query'));
    }

    public function restorePublikasi($id)
    {
        try {
            $data = [
                'status' => 2,
            ];
            PublikasiModel::onlyTrashed()->findOrFail(decrypt($id))->update($data);
            PublikasiModel::onlyTrashed()->findOrFail(decrypt($id))->restore();
            return redirect()->route('publikasi.sampah')->with('success', "Data publikasi berhasil direstore!, silahkan cek pada publikasi aktif yah guys!");
        } catch (Exception $e) {
            return redirect()->route('publikasi.sampah')->with(['failed' => 'Data publikasi GAGAL di Restore ! error :' . $e->getMessage()]);
        }
    }

    public function restoreAllPublikasi()
    {

        $dataterhapus = PublikasiModel::onlyTrashed()->with(['kategori', 'status', 'tipe'])->get();

        if (count($dataterhapus) > 0) {
            try {
                $data = [
                    'status' => 2,
                ];
                PublikasiModel::onlyTrashed()->update($data);
                PublikasiModel::onlyTrashed()->restore();
                return redirect()->route('publikasi.sampah')->with('success', "Semua Data publikasi berhasil direstore!, silahkan cek pada publikasi aktif yah guys!");
            } catch (Exception $e) {
                return redirect()->route('publikasi.sampah')->with(['failed' => 'Semua Data publikasi GAGAL di Restore ! error :' . $e->getMessage()]);
            }
        }
        return redirect()->route('publikasi.sampah')->with(['failed' => 'Data yang direstore gak ada :( ']);
    }
    public function forceDeletePublikasi($id)
    {
        try {
            $data_gambar = PublikasiModel::withTrashed()->findOrFail(decrypt($id));
            File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);
            PublikasiModel::withTrashed()->findOrFail(decrypt($id))->forceDelete();
            return redirect()->route('publikasi.sampah')->with('success', "Data Berhasil dihapus PERMANEN");
        } catch (Exception $e) {
            return redirect()->route('publikasi.sampah')->with(['failed' => 'Data GAGAL dihapus Permanen ! error :' . $e->getMessage()]);
        }
    }
}
