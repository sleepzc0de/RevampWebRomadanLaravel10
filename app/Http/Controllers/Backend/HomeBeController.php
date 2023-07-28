<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuKegiatan\KegiatanModel;
use App\Models\backend\MenuPublikasi\PublikasiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeBeController extends Controller
{
    public function index()
    {
        // $jumlah_artikel = PublikasiModel::all()->count();
        $jumlah_berita = PublikasiModel::where('nama_tipe', 'Berita')->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')->count();

        $jumlah_artikel =
            PublikasiModel::where('nama_tipe', 'Artikel')->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')->count();

        $jumlah_warta =
            PublikasiModel::where('nama_tipe', 'Warta')->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')->count();

        $jumlah_kegiatan = KegiatanModel::all()->count();


        // dd($jumlah_berita);

        $data = [
            'jumlah_berita' => $jumlah_berita,
            'jumlah_artikel' => $jumlah_artikel,
            'jumlah_warta' => $jumlah_warta,
            'jumlah_kegiatan' => $jumlah_kegiatan,
        ];

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
                    // $date = date('d-F-Y H:i:s', strtotime($query->created_at));
                    $date = Carbon::parse($query->created_at)->locale('id')->isoFormat('D-MMMM-Y HH:mm:ss');

                    return $date . " WIB";
                })


                ->rawColumns(['opsi', 'image_publikasi'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.dashboard.dashboard', compact('data'));
    }
}
