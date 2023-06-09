<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuFAQ\FAQModel;
use App\Models\backend\MenuInformasiPublik\InfopublikHomeModel;
use App\Models\backend\MenuInformasiPublik\InformasiPublikModel;
use App\Models\backend\MenuKegiatan\KegiatanModel;
use App\Models\backend\MenuLayanan\LayananModel;
use App\Models\backend\MenuProfile\SejarahModel;
use App\Models\backend\MenuProfile\StrukturOrganisasiModel;
use App\Models\backend\MenuProfile\TentangModel;
use App\Models\backend\MenuProfile\VisiMisiModel;
use App\Models\backend\MenuPublikasi\PublikasiModel;
use App\Models\backend\publikasi\berita;
use App\Models\medsos\medsos;
use Illuminate\Http\Request;

class HomeFeController extends Controller
{
    public function index()
    {
        // 
        $tentang = TentangModel::latest()->take(1)->get();
        $berita_terkini = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Berita')->where('nama_status', 'Tayang')->orderBy("id", "DESC")->take(3)->get();


        // dd($berita_terkini);
        return view('frontend.home_fe', compact(['tentang', 'berita_terkini']));
    }

    public function profile_visi_misi()
    {

        $tentang = TentangModel::first();
        $visimisi = VisiMisiModel::latest()->take(1)->get();
        return view('frontend.profile.fe_visi_misi', compact(['tentang', 'visimisi']));
    }

    public function profile_sejarah()
    {

        $tentang = TentangModel::first();
        $sejarah = SejarahModel::latest()->take(1)->get();
        return view('frontend.profile.fe_sejarah', compact(['tentang', 'sejarah']));
    }

    public function profile_organisasi()
    {

        $tentang = TentangModel::first();
        $organisasi = StrukturOrganisasiModel::latest()->take(1)->get();
        return view('frontend.profile.fe_organisasi', compact(['tentang', 'organisasi']));
    }

    public function profile_tentang()
    {

        // $tentang = TentangModel::first()->get();
        $tentang = TentangModel::latest()->take(1)->get();
        return view('frontend.profile.fe_tentang', compact(['tentang']));
    }

    public function publikasi_berita($publikasi)
    {
        $data = PublikasiModel::where('slug', $publikasi)->where('nama_tipe', 'Berita')->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')->firstorFail();
        // dd($data);

        return view('frontend.publikasi.fe_berita', compact(['data']));
    }

    public function publikasi_artikel($publikasi)
    {
        $data = PublikasiModel::where('slug', $publikasi)->where('nama_tipe', 'Artikel')->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->select('publikasi.*', 'ref_kategori.nama_kategori')->firstorFail();
        // dd($data);

        return view('frontend.publikasi.fe_artikel', compact(['data']));
    }

    public function layanan_layanan()
    {

        // $tentang = TentangModel::first()->get();
        $layanan = LayananModel::latest()->take(1)->get();
        return view('frontend.layanan.layanan', compact(['layanan']));
    }

    public function kegiatan_index(Request $request)
    {

        // $tentang = TentangModel::first()->get();

        if ($request->cari_kegiatan) {
            $search = $request->cari_kegiatan;
            $kegiatan = KegiatanModel::where('judul', 'like', "%" . $search . "%")->latest()->paginate(9);
        } else {
            $kegiatan = KegiatanModel::latest()->paginate(9);
            // return redirect()->back()->with('message', 'Empty Search');
        }

        return view('frontend.kegiatan.fe-index', compact(['kegiatan']));
    }

    public function kegiatan_detail($kegiatan)
    {
        $data = KegiatanModel::where('slug', $kegiatan)->firstorFail();

        // dd($data);

        return view('frontend.kegiatan.fe-detail', compact(['data',]));
    }

    public function infopublik_index()
    {

        // $tentang = TentangModel::first()->get();

        $info_publik = InfopublikHomeModel::orderBy("id", "DESC")->take(1)->first();
        $infolist =  InformasiPublikModel::orderBy("id", "ASC")->take(3)->get();
        // dd($infolist);
        return view('frontend.infopublik.index', compact(['info_publik', 'infolist']));
    }

    public function infopublik_peraturan_index()
    {
        $tanggal = date(now());
        dd($tanggal);
        return view('frontend.infopublik.peraturan-index');
    }

    public function infopublik_pedoman_index()
    {
        return view('frontend.infopublik.pedoman-index');
    }


    public function faq_index()
    {

        $tentang = TentangModel::first()->get();
        $faq = FAQModel::all();
        // dd($faq);
        return view('frontend.faq.fe-index', compact(['tentang', 'faq']));
    }




    // public function kegiatan_search(Request $request)
    // {
    //     $search = $request->search;
    //     
    //     $tentang = TentangModel::first()->get();
    //     $kegiatan = KegiatanModel::where('judul', 'like', "%" . $search . "%")->paginate(1);
    //     return view('frontend.kegiatan.index', compact([, 'tentang', 'kegiatan']));
    // }
}
