<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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
        $medsos = medsos::orderBy("id", "ASC")->take(5)->get();
        $tentang = TentangModel::first()->get();
        $berita_terkini = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->select('publikasi.*', 'ref_kategori.nama_kategori')->orderBy("id", "DESC")->take(3)->get();

        // dd($berita_terkini);
        return view('frontend.home_fe', compact(['medsos', 'tentang', 'berita_terkini']));
    }

    public function profile_visi_misi()
    {
        $medsos = medsos::orderBy("id", "ASC")->take(5)->get();
        $tentang = TentangModel::first()->get();
        $visimisi = VisiMisiModel::firstorFail()->get();
        return view('frontend.profile.fe_visi_misi', compact(['medsos', 'tentang', 'visimisi']));
    }

    public function profile_sejarah()
    {
        $medsos = medsos::orderBy("id", "ASC")->take(5)->get();
        $tentang = TentangModel::first()->get();
        $sejarah = SejarahModel::firstorFail()->get();
        return view('frontend.profile.fe_sejarah', compact(['medsos', 'tentang', 'sejarah']));
    }

    public function profile_organisasi()
    {
        $medsos = medsos::orderBy("id", "ASC")->take(5)->get();
        $tentang = TentangModel::first()->get();
        $organisasi = StrukturOrganisasiModel::firstorFail()->get();
        return view('frontend.profile.fe_organisasi', compact(['medsos', 'tentang', 'organisasi']));
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
        $medsos = medsos::orderBy("id", "ASC")->take(5)->get();
        $tentang = TentangModel::first()->get();
        $layanan = LayananModel::firstorFail()->get();
        return view('frontend.layanan.layanan', compact(['medsos', 'tentang', 'layanan']));
    }
}
