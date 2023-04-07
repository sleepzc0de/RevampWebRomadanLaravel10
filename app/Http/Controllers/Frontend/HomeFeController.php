<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuProfile\SejarahModel;
use App\Models\backend\MenuProfile\StrukturOrganisasiModel;
use App\Models\backend\MenuProfile\TentangModel;
use App\Models\backend\MenuProfile\VisiMisiModel;
use App\Models\medsos\medsos;
use Illuminate\Http\Request;

class HomeFeController extends Controller
{
    public function index()
    {
        $medsos = medsos::orderBy("id", "ASC")->take(5)->get();
        $tentang = TentangModel::first()->get();

        // dd($tentang);
        return view('frontend.home_fe', compact(['medsos', 'tentang']));
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
}
