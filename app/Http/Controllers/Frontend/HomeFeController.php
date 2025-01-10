<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\SecurityHelper;
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
use App\Models\backend\ref_kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class HomeFeController extends Controller
{
    public function index()
    {
        //
        $status_berita = 'Published';

        $tentang = TentangModel::latest()->take(1)->get();

        $berita_terkini = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')
            ->where('nama_tipe', strtolower('Berita'))
            ->whereRaw('LOWER(ref_status.nama_status) like ?', ["%" . strtolower($status_berita) . "%"])
            ->take(3)
            ->get();


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
        $status_artikel = 'Published';
        $status_warta = 'Published';
        $status_berita = 'Published';

        $berita_terkini_publikasi = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')
            ->where('nama_tipe', strtolower('Berita'))
            ->whereRaw('LOWER(ref_status.nama_status) like ?', ["%" . strtolower($status_berita) . "%"])
            ->orderBy("id", "DESC")->take(3)->get();
        // dd($berita_terkini_publikasi);

        $warta_terkini_publikasi = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')
            ->where('nama_tipe', strtolower('Warta'))
            ->whereRaw('LOWER(ref_status.nama_status) like ?', ["%" . strtolower($status_warta) . "%"])
            ->orderBy("id", "DESC")->take(3)->get();
        // dd($warta_terkini_publikasi);

        $artikel_terkini_publikasi =
            PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')
            ->where('nama_tipe', strtolower('Artikel'))
            ->whereRaw('LOWER(ref_status.nama_status) like ?', ["%" . strtolower($status_artikel) . "%"])
            ->orderBy("id", "DESC")->take(3)->get();
        // dd($artikel_terkini_publikasi);

        return view('frontend.publikasi.index', compact([
            'berita_terkini_publikasi',
            'warta_terkini_publikasi',
            'artikel_terkini_publikasi'
        ]));
    }

    public function publikasi_berita_kategori(Request $request, $kategori)
    {
        try {
            DB::enableQueryLog();

            $status_berita = 'Published';
            // Menggunakan SecurityHelper untuk sanitasi input
            $searchValue = SecurityHelper::sanitizeInput($request->input('cari_berita'));
            $kategori_param = SecurityHelper::sanitizeInput($kategori);
            $isSearch = false;

            Log::info('Search request received', [
                'search_value' => SecurityHelper::escapeOutput($searchValue),
                'kategori' => SecurityHelper::escapeOutput($kategori_param),
                'is_ajax' => $request->ajax(),
                'request_all' => $request->all()
            ]);

            $query = PublikasiModel::query()
                ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
                ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
                ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
                ->select(
                    'publikasi.*',
                    'ref_kategori.nama_kategori',
                    'ref_status.nama_status',
                    'ref_tipe.nama_tipe'
                )
                ->where('nama_tipe', '=', 'berita')
                ->where('ref_status.nama_status', '=', $status_berita);

            if ($kategori_param !== 'all') {
                // Menggunakan parameter binding untuk mencegah SQL injection
                $query->whereRaw('LOWER(ref_kategori.nama_kategori) = ?', [strtolower($kategori_param)]);
            }

            if ($searchValue) {
                $isSearch = true;
                // Menggunakan parameter binding untuk pencarian
                $searchValue = '%' . $searchValue . '%';
                $query->where(function($q) use ($searchValue) {
                    $q->where('judul', 'like', $searchValue)
                      ->orWhere('isi', 'like', $searchValue);
                });
            }

            Log::info('SQL Query:', [
                'query' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            $berita = $query->latest()->paginate(9);

            Log::info('Query results:', [
                'count' => $berita->count(),
                'total' => $berita->total()
            ]);

            if ($request->ajax()) {
                // Menggunakan SecurityHelper untuk escape output pada view
                $view = view('frontend.publikasi.partials.berita-list',
                    [
                        'berita' => $berita->map(function($item) {
                            // Escape semua output yang akan ditampilkan
                            $item->judul = SecurityHelper::escapeOutput($item->judul);
                            $item->isi = SecurityHelper::escapeOutput($item->isi);
                            $item->nama_kategori = SecurityHelper::escapeOutput($item->nama_kategori);
                            return $item;
                        }),
                        'isSearch' => $isSearch,
                        'searchValue' => SecurityHelper::escapeOutput($searchValue)
                    ]
                )->render();

                return response($view)->header('Content-Type', 'text/html');
            }

            $kategori_list = ref_kategori::all()->map(function($item) {
                // Escape output untuk daftar kategori
                $item->nama_kategori = SecurityHelper::escapeOutput($item->nama_kategori);
                return $item;
            });

            return view('frontend.publikasi.kategori-berita', [
                'berita' => $berita->map(function($item) {
                    // Escape semua output yang akan ditampilkan
                    $item->judul = SecurityHelper::escapeOutput($item->judul);
                    $item->isi = SecurityHelper::escapeOutput($item->isi);
                    $item->nama_kategori = SecurityHelper::escapeOutput($item->nama_kategori);
                    return $item;
                }),
                'searchValue' => SecurityHelper::escapeOutput($searchValue),
                'isSearch' => $isSearch,
                'kategori' => $kategori_list,
            ]);

        } catch (\Exception $e) {
            Log::error('Category search error', [
                'message' => SecurityHelper::escapeOutput($e->getMessage()),
                'trace' => $e->getTraceAsString(),
                'sql' => DB::getQueryLog()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memproses pencarian.',
                    'details' => SecurityHelper::escapeOutput($e->getMessage())
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat memproses permintaan.');
        }
    }

    public function publikasi_warta_kategori(Request $request, $kategori)
    {
        try {
            // Enable query logging for debugging
            DB::enableQueryLog();

            $status_warta = 'Published';
            $searchValue = strip_tags($request->input('cari_warta'));
            $kategori_param = strip_tags($kategori);
            $isSearch = false;

            Log::info('Search request received', [
                'search_value' => $searchValue,
                'kategori' => $kategori_param,
                'is_ajax' => $request->ajax(),
                'request_all' => $request->all()
            ]);

            $query = PublikasiModel::query()
                ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
                ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
                ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
                ->select(
                    'publikasi.*',
                    'ref_kategori.nama_kategori',
                    'ref_status.nama_status',
                    'ref_tipe.nama_tipe'
                )
                ->where('nama_tipe', '=', 'warta')
                ->where('ref_status.nama_status', '=', $status_warta);

            // Add category filter if not "View All"
            if ($kategori_param !== 'all') {
                $query->whereRaw('LOWER(ref_kategori.nama_kategori) = ?', [strtolower($kategori_param)]);
            }

            // Add search filter if search term exists
            if ($searchValue) {
                $isSearch = true;
                $query->where(function($q) use ($searchValue) {
                    $q->where('judul', 'like', '%' . $searchValue . '%')
                      ->orWhere('isi', 'like', '%' . $searchValue . '%');
                });
            }

            // Log the final SQL query
            Log::info('SQL Query:', [
                'query' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            $warta = $query->latest()->paginate(9);

            Log::info('Query results:', [
                'count' => $warta->count(),
                'total' => $warta->total()
            ]);

            if ($request->ajax()) {
                $view = view('frontend.publikasi.partials.warta-list',
                    compact('warta', 'isSearch', 'searchValue')
                )->render();

                return response($view)->header('Content-Type', 'text/html');
            }

            $kategori_list = ref_kategori::all();

            return view('frontend.publikasi.kategori-warta', [
                'warta' => $warta,
                'searchValue' => $searchValue,
                'isSearch' => $isSearch,
                'kategori' => $kategori_list,
            ]);

        } catch (\Exception $e) {
            Log::error('Category search error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sql' => DB::getQueryLog()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memproses pencarian.',
                    'details' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat memproses permintaan.');
        }
    }


    public function publikasi_artikel_kategori(Request $request, $kategori)
    {
        try {
            // Enable query logging for debugging
            DB::enableQueryLog();

            $status_artikel = 'Published';
            $searchValue = strip_tags($request->input('cari_artikel'));
            $kategori_param = strip_tags($kategori);
            $isSearch = false;

            Log::info('Search request received', [
                'search_value' => $searchValue,
                'kategori' => $kategori_param,
                'is_ajax' => $request->ajax(),
                'request_all' => $request->all()
            ]);

            $query = PublikasiModel::query()
                ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
                ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
                ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
                ->select(
                    'publikasi.*',
                    'ref_kategori.nama_kategori',
                    'ref_status.nama_status',
                    'ref_tipe.nama_tipe'
                )
                ->where('nama_tipe', '=', 'artikel')
                ->where('ref_status.nama_status', '=', $status_artikel);

            // Add category filter if not "View All"
            if ($kategori_param !== 'all') {
                $query->whereRaw('LOWER(ref_kategori.nama_kategori) = ?', [strtolower($kategori_param)]);
            }

            // Add search filter if search term exists
            if ($searchValue) {
                $isSearch = true;
                $query->where(function($q) use ($searchValue) {
                    $q->where('judul', 'like', '%' . $searchValue . '%')
                      ->orWhere('isi', 'like', '%' . $searchValue . '%');
                });
            }

            // Log the final SQL query
            Log::info('SQL Query:', [
                'query' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            $artikel = $query->latest()->paginate(9);

            Log::info('Query results:', [
                'count' => $artikel->count(),
                'total' => $artikel->total()
            ]);

            if ($request->ajax()) {
                $view = view('frontend.publikasi.partials.artikel-list',
                    compact('artikel', 'isSearch', 'searchValue')
                )->render();

                return response($view)->header('Content-Type', 'text/html');
            }

            $kategori_list = ref_kategori::all();

            return view('frontend.publikasi.kategori-artikel', [
                'artikel' => $artikel,
                'searchValue' => $searchValue,
                'isSearch' => $isSearch,
                'kategori' => $kategori_list,
            ]);

        } catch (\Exception $e) {
            Log::error('Category search error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sql' => DB::getQueryLog()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memproses pencarian.',
                    'details' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat memproses permintaan.');
        }
    }



    public function publikasi_index_berita(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'cari_berita' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input'], 422);
        }

        $status_berita = 'Published';
        $searchValue = SecurityHelper::sanitizeInput($request->input('cari_berita'));
        $isSearch = false;

        // Use query builder with parameterized queries
        $query = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')
            ->where('nama_tipe', '=', 'berita')
            ->where('ref_status.nama_status', '=', $status_berita);

        if ($searchValue) {
            $isSearch = true;
            $query->where('judul', 'like', '%' . $searchValue . '%');
        }

        // Add rate limiting
        if (RateLimiter::tooManyAttempts('search:'.$request->ip(), 60)) {
            return response()->json(['error' => 'Too many search attempts'], 429);
        }
        RateLimiter::hit('search:'.$request->ip());

        $berita = $query->latest()->paginate(9);
        $kategori = ref_kategori::all();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.publikasi.partials.berita-list',
                    compact('berita', 'isSearch', 'searchValue'))->render(),
                'status' => 'success'
            ], 200);
        }

        return view('frontend.publikasi.index-berita',
            compact('searchValue', 'isSearch', 'berita', 'kategori'));
    }

    public function publikasi_index_warta(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'cari_warta' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input'], 422);
        }

        $status_warta = 'Published';
        $searchValue = SecurityHelper::sanitizeInput($request->input('cari_warta'));
        $isSearch = false;

        // Use query builder with parameterized queries
        $query = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')
            ->where('nama_tipe', '=', 'warta')
            ->where('ref_status.nama_status', '=', $status_warta);

        if ($searchValue) {
            $isSearch = true;
            $query->where('judul', 'like', '%' . $searchValue . '%');
        }

        // Add rate limiting
        if (RateLimiter::tooManyAttempts('search:'.$request->ip(), 60)) {
            return response()->json(['error' => 'Too many search attempts'], 429);
        }
        RateLimiter::hit('search:'.$request->ip());

        $warta = $query->latest()->paginate(9);
        $kategori = ref_kategori::all();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.publikasi.partials.warta-list',
                    compact('warta', 'isSearch', 'searchValue'))->render(),
                'status' => 'success'
            ], 200);
        }

        return view('frontend.publikasi.index-warta',
            compact('searchValue', 'isSearch', 'warta', 'kategori'));
    }



    public function publikasi_index_artikel(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'cari_artikel' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input'], 422);
        }

        $status_artikel = 'Published';
        $searchValue = SecurityHelper::sanitizeInput($request->input('cari_artikel'));
        $isSearch = false;

        // Use query builder with parameterized queries
        $query = PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')
            ->where('nama_tipe', '=', 'artikel')
            ->where('ref_status.nama_status', '=', $status_artikel);

        if ($searchValue) {
            $isSearch = true;
            $query->where('judul', 'like', '%' . $searchValue . '%');
        }

        // Add rate limiting
        if (RateLimiter::tooManyAttempts('search:'.$request->ip(), 60)) {
            return response()->json(['error' => 'Too many search attempts'], 429);
        }
        RateLimiter::hit('search:'.$request->ip());

        $artikel = $query->latest()->paginate(9);
        $kategori = ref_kategori::all();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.publikasi.partials.artikel-list',
                    compact('artikel', 'isSearch', 'searchValue'))->render(),
                'status' => 'success'
            ], 200);
        }

        return view('frontend.publikasi.index-artikel',
            compact('searchValue', 'isSearch', 'artikel', 'kategori'));
    }

    public function publikasi_berita($publikasi)
    {
        // Mengambil data berita berdasarkan slug
        $data = PublikasiModel::where('slug', $publikasi)
            ->where('nama_tipe', strtolower('Berita'))
            ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')
            ->firstOrFail();

        // Menambah jumlah views
        $data->increment('views');

        // Format tanggal
        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y');

        // Menampilkan ke view
        return view('frontend.publikasi.fe_berita', compact(['data', 'tb']));
    }


    public function publikasi_warta($publikasi)
    {
        $data = PublikasiModel::where('slug', $publikasi)->where('nama_tipe',  strtolower('Warta'))->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')->firstorFail();
        // dd($data);

         // Menambah jumlah views
         $data->increment('views');

        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y', 'j F Y');

        return view('frontend.publikasi.fe_warta', compact(['data', 'tb']));
    }

    public function publikasi_artikel($publikasi)
    {
        $data = PublikasiModel::where('slug', $publikasi)->where('nama_tipe',  strtolower('Artikel'))
            ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')->firstorFail();
        // dd($data->isi);

         // Menambah jumlah views
         $data->increment('views');

        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y', 'j F Y');

        return view('frontend.publikasi.fe_artikel', compact(['data', 'tb']));
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

        $data = KegiatanModel::where('slug', $kegiatan)->where('static_random_string', $ranstring)->firstorFail();

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
        // return view('frontend.infopublik.pedoman-index');
        return back();
    }

    public function infopublik_aplikasi_index(Request $request)
    {
        $searchValue = strip_tags($request->input('cari_aplikasi'));
        $data = null;
        $isSearch = false;

        if ($request->cari_aplikasi) {
            $isSearch = true; // Tandai bahwa pencarian dilakukan
            $search = $request->cari_aplikasi;
            $data = AplikasiModel::where('judul_aplikasi', 'like', "%" . $search . "%")
                ->orWhere('sub_judul_aplikasi', 'like', "%" . $search . "%")
                ->latest()->paginate(9);
        } else {
            $data = AplikasiModel::latest()->paginate(9);
        }

        return view('frontend.infopublik.aplikasi-index', compact('data', 'isSearch'));
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
