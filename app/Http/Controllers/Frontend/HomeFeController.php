<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuFAQ\FAQModel;
use App\Models\backend\MenuInformasiPublik\AplikasiModel;
use App\Models\backend\MenuInformasiPublik\InfopublikHomeModel;
use App\Models\backend\MenuInformasiPublik\InformasiPublikModel;
use App\Models\backend\MenuInformasiPublik\PeraturanModel;
use App\Models\backend\MenuKegiatan\KegiatanModel;
use App\Models\backend\MenuLayanan\LayananModel;
use App\Models\backend\MenuProfile\SejarahModel;
use App\Models\backend\MenuProfile\StrukturOrganisasiModel;
use App\Models\backend\MenuProfile\TentangModel;
use App\Models\backend\MenuProfile\VisiMisiModel;
use App\Models\backend\MenuPublikasi\PublikasiModel;
use App\Models\backend\MenuReferensi\ref_jenis_peraturan;
use App\Models\backend\publikasi\berita;
use App\Models\backend\ref_kategori;
use App\Models\medsos\medsos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Hash;

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

    // PUBLIKASI

    public function publikasi_index()
    {
        $berita_terkini_publikasi = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Berita')->where('nama_status', 'Tayang')->orderBy("id", "DESC")->take(3)->get();
        // dd($berita_terkini_publikasi);

        $warta_terkini_publikasi = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Warta')->where('nama_status', 'Tayang')->orderBy("id", "DESC")->take(3)->get();
        // dd($warta_terkini_publikasi);

        $artikel_terkini_publikasi =
            PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Artikel')->where('nama_status', 'Tayang')->orderBy("id", "DESC")->take(3)->get();
        // dd($artikel_terkini_publikasi);

        return view('frontend.publikasi.index', compact([
            'berita_terkini_publikasi',
            'warta_terkini_publikasi',
            'artikel_terkini_publikasi'
        ]));
    }

    public function publikasi_index_berita(Request $request)
    {

        $searchValue = strip_tags($request->input('cari_berita_terkini'));
        if ($request->cari_berita_terkini) {
            $search = $request->cari_berita_terkini;
            $berita = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Berita')->where('judul', 'like', "%" . $search . "%")->where('nama_status', 'Tayang')->latest()->paginate(9);
        } else {
            $berita = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Berita')->where('nama_status', 'Tayang')->latest()->paginate(9);
            // return redirect()->back()->with('message', 'Empty Search');
        }

        $kategori = ref_kategori::all();
        // dd($kategori);


        return view('frontend.publikasi.index-berita', compact(['berita', 'searchValue', 'kategori']));
    }

    public function publikasi_berita_kategori(Request $request, $kategori)
    {

        $searchValue = strip_tags($request->input('cari_berita_terkini'));
        if ($request->cari_berita_terkini) {
            $search = $request->cari_berita_terkini;
            $berita = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Berita')->where('judul', 'like', "%" . $search . "%")->where('ref_kategori.nama_kategori', strip_tags(strtolower($kategori)))->latest()->paginate(9);
        } else {
            $berita = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Berita')->where('ref_kategori.nama_kategori', strip_tags(strtolower($kategori)))->latest()->paginate(9);
            // return redirect()->back()->with('message', 'Empty Search');
        }

        $kategori = ref_kategori::all();


        return view('frontend.publikasi.kategori-berita', compact(['berita', 'searchValue', 'kategori']));
    }

    public function publikasi_warta_kategori(Request $request, $kategori)
    {

        $searchValue = strip_tags($request->input('cari_warta'));
        if ($request->cari_warta) {
            $search = $request->cari_warta;
            $warta = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Warta')->where('judul', 'like', "%" . $search . "%")->where('ref_kategori.nama_kategori', strip_tags(strtolower($kategori)))->latest()->paginate(9);
        } else {
            $warta = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Warta')->where('ref_kategori.nama_kategori', strip_tags(strtolower($kategori)))->latest()->paginate(9);
            // return redirect()->back()->with('message', 'Empty Search');
        }

        $kategori = ref_kategori::all();


        return view('frontend.publikasi.kategori-warta', compact(['warta', 'searchValue', 'kategori']));
    }

    public function publikasi_artikel_kategori(Request $request, $kategori)
    {

        $searchValue = strip_tags($request->input('cari_artikel'));
        if ($request->cari_artikel) {
            $search = $request->cari_artikel;
            $artikel = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Artikel')->where('judul', 'like', "%" . $search . "%")->where('ref_kategori.nama_kategori', strip_tags(strtolower($kategori)))->latest()->paginate(9);
        } else {
            $artikel = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Artikel')->where('ref_kategori.nama_kategori', strip_tags(strtolower($kategori)))->latest()->paginate(9);
            // return redirect()->back()->with('message', 'Empty Search');
        }

        $kategori = ref_kategori::all();
        


        return view('frontend.publikasi.kategori-artikel', compact(['artikel', 'searchValue', 'kategori']));
    }



    public function publikasi_index_warta(Request $request)
    {
        $searchValue = strip_tags($request->input('cari_warta'));
        if ($request->cari_warta) {
            $search = $request->cari_warta;
            $warta = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Warta')->where('judul', 'like', "%" . $search . "%")->latest()->paginate(9);
        } else {
            $warta = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Warta')->latest()->paginate(9);
            // return redirect()->back()->with('message', 'Empty Search');
        }

        $kategori = ref_kategori::all();
        return view('frontend.publikasi.index-warta', compact(['warta', 'searchValue', 'kategori']));
    }

    public function publikasi_index_artikel(Request $request)
    {

        $searchValue = strip_tags($request->input('cari_artikel'));
        if ($request->cari_artikel) {
            $search = $request->cari_artikel;
            $artikel = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Artikel')->where('judul', 'like', "%" . $search . "%")->latest()->paginate(9);
        } else {
            $artikel = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_status', 'publikasi.status', '=', 'ref_status.id_status')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')->where('nama_tipe', 'Artikel')->latest()->paginate(9);
            // return redirect()->back()->with('message', 'Empty Search');
        }

        $kategori = ref_kategori::all();


        return view('frontend.publikasi.index-artikel', compact(['searchValue', 'artikel', 'kategori']));
    }

    public function publikasi_berita($publikasi)
    {
        $data = PublikasiModel::where('slug', $publikasi)->where('nama_tipe', 'Berita')->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')->firstorFail();
        // dd($data);

        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y', 'j F Y');

        return view('frontend.publikasi.fe_berita', compact(['data','tb']));
    }

    public function publikasi_warta($publikasi)
    {
        $data = PublikasiModel::where('slug', $publikasi)->where('nama_tipe', 'Warta')->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')->firstorFail();
        // dd($data);

        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y', 'j F Y');

        return view('frontend.publikasi.fe_warta', compact(['data','tb']));
    }

    public function publikasi_artikel($publikasi)
    {
        $data = PublikasiModel::where('slug', $publikasi)->where('nama_tipe', 'Artikel')->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')->firstorFail();
        // dd($data);

        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y', 'j F Y');

        return view('frontend.publikasi.fe_artikel', compact(['data','tb']));
    }

    // LAYANAN

    public function layanan_layanan()
    {

        // $tentang = TentangModel::first()->get();
        $layanan = LayananModel::latest()->take(1)->get();
        return view('frontend.layanan.layanan', compact(['layanan']));
    }

    public function kegiatan_index(Request $request)
    {

        // $tentang = TentangModel::first()->get();
        $searchValue = strip_tags($request->input('cari_kegiatan'));
        if ($request->cari_kegiatan) {
            $search = $request->cari_kegiatan;
            $kegiatan = KegiatanModel::where('judul', 'like', "%" . $search . "%")->latest()->paginate(9);
        } else {
            $kegiatan = KegiatanModel::latest()->paginate(9);
            // return redirect()->back()->with('message', 'Empty Search');
        }

        return view('frontend.kegiatan.fe-index', compact(['kegiatan', 'searchValue']));
    }

    public function kegiatan_detail($kegiatan, $ranstring)
    {

        $data = KegiatanModel::where('slug', $kegiatan)->where('static_random_string',$ranstring)->firstorFail();

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

    public function infopublik_peraturan_index(Request $request)
    {
        $kategori = ref_kategori::all();
        $jenis_peraturan = ref_jenis_peraturan::all();
        $searchValue = strip_tags($request->input('cari_peraturan'));
        $selectedKategori = $request->input('kategori'); // Mengambil nilai checkbox kategori yang dipilih
        $selectedJenisPeraturan =  $request->input('jenis_peraturan'); // Mengambil nilai checkbox jenis_peraturan yang dipilih

        $query = PeraturanModel::with('kategori', 'data_jenis_peraturan', 'data_status_peraturan');

        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('nomor_peraturan', 'like', '%' . $searchValue . '%')
                    ->orWhere('judul_peraturan', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('kategori', function ($q) use ($searchValue) {
                        $q->where('nama_kategori', 'like', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('data_jenis_peraturan', function ($q) use ($searchValue) {
                        $q->where('nama_jenis_peraturan', 'like', '%' . $searchValue . '%');
                    });
            });
        }

        if ($selectedKategori) {
            $query->orWhereHas('kategori', function ($q) use ($selectedKategori) {
                $q->whereIn('nama_kategori', $selectedKategori);
            });
            // Menggunakan kolom yang sesuai di tabel PeraturanModel
        }

        if ($selectedJenisPeraturan) {
            $query->orWhereHas('data_jenis_peraturan', function ($q) use ($selectedJenisPeraturan) {
                $q->whereIn('nama_jenis_peraturan', $selectedJenisPeraturan);
            });
            // Menggunakan kolom yang sesuai di tabel PeraturanModel
        }

        $peraturan = $query->latest()->paginate(9);
        // dd($peraturan);

        return view('frontend.infopublik.peraturan-index', compact(['peraturan', 'searchValue', 'kategori', 'jenis_peraturan', 'selectedKategori', 'selectedJenisPeraturan']));
    }

    public function infopublik_peraturan_detail($peraturan)
    {

        $data = PeraturanModel::where('slug', $peraturan)->firstorFail();
        return view('frontend.infopublik.peraturan-detail', compact(['data']));
    }

    public function infopublik_pedoman_index()
    {
        return view('frontend.infopublik.pedoman-index');
    }

    public function infopublik_aplikasi_index(Request $request)
    {
        $searchValue = strip_tags($request->input('cari_aplikasi'));
        if ($request->cari_aplikasi) {
            $search = $request->cari_aplikasi;
            $data = AplikasiModel::where('judul_aplikasi', 'like', "%" . $search . "%")->orWhere('sub_judul_aplikasi', 'like', "%" . $search . "%")->latest()->paginate(9);
        } else {

            $data = AplikasiModel::latest()->paginate(9);
        }
        return view('frontend.infopublik.aplikasi-index', compact('data'));
    }


    public function faq_index()
    {

        $tentang = TentangModel::latest()->take(1)->get();
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
