<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Models\Login\LoginModel;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = LoginModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                ->addColumn('image_loggambar', function ($query) {
                    $url = asset('storage/romadan_gambar_web/' . $query->image);
                    return '<a href="' . $url . '"><img src="' . $url . '" border="0" width="100" class="img-rounded" align="center""/></a>';
                })
                ->addColumn('opsi', function ($query) {
                    $preview = route('loggambar.show', encrypt($query->id));
                    $edit = route('loggambar.edit', encrypt($query->id));
                    $hapus = route('loggambar.destroy', encrypt($query->id));
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


                ->rawColumns(['opsi', 'image_loggambar'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.loggambar.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('backend.loggambar.create');
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
                'nama_gambar' => 'required|unique:login_gambar',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:1000',
            ]);

            //UPLOAD IMAGE
            $image = $request->file('image');
            $image->storeAs('public/romadan_gambar_web', $image->hashName());

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_gambar' => $request->nama_gambar,
                'image' => $image->hashName(),

            ];


            LoginModel::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Data Gambar Berhasil Disimpan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Data Gambar Gagal Disimpan! error :' . $e->getMessage()]);
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
        // $data = LoginModel::findOrFail(decrypt($id));
        // dd($data);

        return redirect()->route('loggambar.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loggambars = LoginModel::findOrFail(decrypt($id));
        // dd($berita);
        return view('backend.loggambar.edit', compact('loggambars'));
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
                'nama_gambar' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:1000',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nama_gambar' => $request->nama_gambar,

            ];
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg|max:1000',
                ], [
                    'image.mimes' => 'Gambar hanya diperbolehkaan berekstensi JPEG, JPG, PNG',
                ]);

                //UPLOAD IMAGE
                $image = $request->file('image');
                $image->storeAs('public/romadan_gambar_web', $image->hashName());

                $data_gambar = LoginModel::findOrFail(decrypt($id));
                File::delete(public_path('storage/romadan_gambar_web/') . $data_gambar->image);

                $data = [
                    'image' => $image->hashName(),
                ];
            }

            LoginModel::findOrFail(decrypt($id))->update($data);
            // $berita = Berita::find($id)->update($data);
            return redirect()->route('loggambar.index')->with('success', "Gambar $request->nama_gambar berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('loggambar.index')->with(['failed' => 'Data Gambar Gagal Di Update! error :' . $e->getMessage()]);
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
            LoginModel::findOrFail(decrypt($id))->delete();
            return redirect()->route('loggambar.index')->with('success', "Gambar berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('loggambar.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
