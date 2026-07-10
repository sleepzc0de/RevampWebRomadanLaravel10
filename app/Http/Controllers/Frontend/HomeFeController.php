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
use App\Models\backend\MenuReferensi\RefJenisPeraturan;
use App\Models\backend\RefKategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class HomeFeController extends Controller
{
    private const STATUS_PUBLISHED = 'Published';

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
            ->whereRaw('LOWER(ref_status.nama_status) like ?', ['%'.strtolower($status_berita).'%'])
            ->orderBy('publikasi.updated_at', 'desc')
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
        try {
            Log::info('Fetching struktur organisasi data');
            $tentang = TentangModel::first();

            // Explicitly select columns to avoid duplicate column names
            // Don't use additionalImages relationship ordering - will do in PHP
            $organisasi = StrukturOrganisasiModel::select('struktur_organisasi.*')
                ->with(['additionalImages' => function ($query) {
                    // Select specific columns but NO ordering in the SQL
                    $query->select(
                        'id',
                        'struktur_organisasi_id',
                        'image_path',
                        'sort_order',
                        'created_at',
                        'updated_at'
                    );
                }])
                ->orderBy('id', 'desc')
                ->get();

            Log::info('Successfully fetched struktur organisasi data', ['count' => count($organisasi)]);

            return view('frontend.profile.fe_organisasi', compact(['tentang', 'organisasi']));
        } catch (\Exception $e) {
            Log::error('Error in profile_organisasi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('frontend.profile.fe_organisasi', [
                'tentang' => TentangModel::first(),
                'organisasi' => collect(), // Empty collection if error
            ])->with('error', 'Terjadi kesalahan saat memuat data struktur organisasi.');
        }
    }

    public function profile_tentang()
    {

        // $tentang = TentangModel::first()->get();
        $tentang = TentangModel::latest()->take(1)->get();

        return view('frontend.profile.fe_tentang', compact(['tentang']));
    }

    // PUBLIKASI

    /**
     * Base query publikasi + join referensi (kategori/status/tipe),
     * difilter berdasarkan tipe dan status published.
     */
    private function publikasiPublishedQuery(string $tipe)
    {
        return PublikasiModel::query()
            ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')
            ->where('nama_tipe', strtolower($tipe))
            ->where('ref_status.nama_status', self::STATUS_PUBLISHED);
    }

    public function publikasi_index()
    {
        return view('frontend.publikasi.index', [
            'berita_terkini_publikasi' => $this->publikasiPublishedQuery('Berita')->orderByDesc('id')->take(3)->get(),
            'warta_terkini_publikasi' => $this->publikasiPublishedQuery('Warta')->orderByDesc('id')->take(3)->get(),
            'artikel_terkini_publikasi' => $this->publikasiPublishedQuery('Artikel')->orderByDesc('id')->take(3)->get(),
        ]);
    }

    public function publikasi_berita_kategori(Request $request, $kategori)
    {
        return $this->publikasiKategori($request, 'berita', $kategori);
    }

    public function publikasi_warta_kategori(Request $request, $kategori)
    {
        return $this->publikasiKategori($request, 'warta', $kategori);
    }

    public function publikasi_artikel_kategori(Request $request, $kategori)
    {
        return $this->publikasiKategori($request, 'artikel', $kategori);
    }

    /**
     * Daftar publikasi per-kategori + pencarian (judul/isi), mendukung
     * respons AJAX (partial HTML) maupun full-page. Dipakai bersama oleh
     * berita/warta/artikel — hanya beda nama tipe & view.
     */
    private function publikasiKategori(Request $request, string $tipe, string $kategori)
    {
        try {
            $searchValue = SecurityHelper::sanitizeInput($request->input('cari_'.$tipe));
            $kategori_param = SecurityHelper::sanitizeInput($kategori);
            $isSearch = false;

            $query = $this->publikasiPublishedQuery($tipe);

            if ($kategori_param !== 'all') {
                $query->whereRaw('LOWER(ref_kategori.nama_kategori) = ?', [strtolower($kategori_param)]);
            }

            if ($searchValue) {
                $isSearch = true;
                $query->where(function ($q) use ($searchValue) {
                    $q->where('judul', 'like', '%'.$searchValue.'%')
                        ->orWhere('isi', 'like', '%'.$searchValue.'%');
                });
            }

            // withQueryString() agar parameter tidak hilang saat pindah halaman
            $items = $query->latest()->paginate(9)->withQueryString();

            if ($request->ajax()) {
                $view = view("frontend.publikasi.partials.{$tipe}-list", [
                    $tipe => $items,
                    'isSearch' => $isSearch,
                    'searchValue' => $searchValue,
                ])->render();

                return response($view)->header('Content-Type', 'text/html');
            }

            return view("frontend.publikasi.kategori-{$tipe}", [
                $tipe => $items,
                'searchValue' => $searchValue,
                'isSearch' => $isSearch,
                'kategori' => RefKategori::all(),
            ]);
        } catch (\Exception $e) {
            Log::error('Category search error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memproses pencarian.',
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat memproses permintaan.');
        }
    }

    public function publikasi_index_berita(Request $request)
    {
        return $this->publikasiIndexByTipe($request, 'berita');
    }

    public function publikasi_index_warta(Request $request)
    {
        return $this->publikasiIndexByTipe($request, 'warta');
    }

    public function publikasi_index_artikel(Request $request)
    {
        return $this->publikasiIndexByTipe($request, 'artikel');
    }

    /**
     * Halaman index publikasi per-tipe + pencarian judul (dengan rate limit).
     * Dipakai bersama oleh berita/warta/artikel.
     */
    private function publikasiIndexByTipe(Request $request, string $tipe)
    {
        $inputName = 'cari_'.$tipe;

        $validator = Validator::make($request->all(), [
            $inputName => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input'], 422);
        }

        $searchValue = SecurityHelper::sanitizeInput($request->input($inputName));
        $isSearch = false;

        $query = $this->publikasiPublishedQuery($tipe);

        if ($searchValue) {
            $isSearch = true;
            $query->where('judul', 'like', '%'.$searchValue.'%');
        }

        // Rate limit pencarian per-IP
        if (RateLimiter::tooManyAttempts('search:'.$request->ip(), 60)) {
            return response()->json(['error' => 'Too many search attempts'], 429);
        }
        RateLimiter::hit('search:'.$request->ip());

        $items = $query->latest()->paginate(9);
        $kategori = RefKategori::all();

        if ($request->ajax()) {
            return response()->json([
                'html' => view("frontend.publikasi.partials.{$tipe}-list", [
                    $tipe => $items,
                    'isSearch' => $isSearch,
                    'searchValue' => $searchValue,
                ])->render(),
                'status' => 'success',
            ], 200);
        }

        return view("frontend.publikasi.index-{$tipe}", [
            $tipe => $items,
            'searchValue' => $searchValue,
            'isSearch' => $isSearch,
            'kategori' => $kategori,
        ]);
    }

    public function publikasi_berita($publikasi)
    {
        // Mengambil data berita berdasarkan slug dengan eager loading images
        $data = PublikasiModel::where('slug', $publikasi)
            ->where('nama_tipe', strtolower('Berita'))
            ->with('images')  // Add this line to load all related images
            ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')
            ->firstOrFail();

        // Menambah jumlah views tanpa menyentuh updated_at
        // (urutan "terkini" di beranda memakai updated_at)
        $data->timestamps = false;
        $data->increment('views');

        // Format tanggal
        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y');

        // Menampilkan ke view
        return view('frontend.publikasi.fe_berita', compact(['data', 'tb']));
    }

    public function publikasi_warta($publikasi)
    {
        $data = PublikasiModel::where('slug', $publikasi)
            ->where('nama_tipe', strtolower('Warta'))
            ->with('images')  // Add this line to load all related images
            ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')
            ->firstorFail();

        // Menambah jumlah views tanpa menyentuh updated_at
        // (urutan "terkini" di beranda memakai updated_at)
        $data->timestamps = false;
        $data->increment('views');

        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y', 'j F Y');

        return view('frontend.publikasi.fe_warta', compact(['data', 'tb']));
    }

    public function publikasi_artikel($publikasi)
    {
        $data = PublikasiModel::where('slug', $publikasi)
            ->where('nama_tipe', strtolower('Artikel'))
            ->with('images')  // Add this line to load all related images
            ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')
            ->firstorFail();

        // Menambah jumlah views tanpa menyentuh updated_at
        // (urutan "terkini" di beranda memakai updated_at)
        $data->timestamps = false;
        $data->increment('views');

        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y', 'j F Y');

        return view('frontend.publikasi.fe_artikel', compact(['data', 'tb']));
    }

    // LAYANAN

    public function layanan_layanan()
    {
        // Get the latest layanan with its related additional images
        $layanan = LayananModel::with('additionalImages')->latest()->take(1)->get();

        return view('frontend.layanan.layanan', compact(['layanan']));
    }

    public function kegiatan_index(Request $request)
    {

        // $tentang = TentangModel::first()->get();
        $searchValue = strip_tags($request->input('cari_kegiatan'));
        if ($request->cari_kegiatan) {
            $search = $request->cari_kegiatan;
            $kegiatan = KegiatanModel::where('judul', 'like', '%'.$search.'%')->latest()->paginate(9);
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

        return view('frontend.kegiatan.fe-detail', compact(['data']));
    }

    public function infopublik_index()
    {

        // $tentang = TentangModel::first()->get();

        $info_publik = InfopublikHomeModel::orderBy('id', 'DESC')->take(1)->first();
        $infolist = InformasiPublikModel::orderBy('id', 'ASC')->take(3)->get();

        // dd($infolist);
        return view('frontend.infopublik.index', compact(['info_publik', 'infolist']));
    }

    public function infopublik_peraturan_index(Request $request)
    {
        $kategori = RefKategori::all();
        $jenis_peraturan = RefJenisPeraturan::all();
        $searchValue = strip_tags($request->input('cari_peraturan'));
        $selectedKategori = $request->input('kategori'); // Mengambil nilai checkbox kategori yang dipilih
        $selectedJenisPeraturan = $request->input('jenis_peraturan'); // Mengambil nilai checkbox jenis_peraturan yang dipilih

        $query = PeraturanModel::with('kategori', 'data_jenis_peraturan', 'data_status_peraturan');

        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('nomor_peraturan', 'like', '%'.$searchValue.'%')
                    ->orWhere('judul_peraturan', 'like', '%'.$searchValue.'%')
                    ->orWhereHas('kategori', function ($q) use ($searchValue) {
                        $q->where('nama_kategori', 'like', '%'.$searchValue.'%');
                    })
                    ->orWhereHas('data_jenis_peraturan', function ($q) use ($searchValue) {
                        $q->where('nama_jenis_peraturan', 'like', '%'.$searchValue.'%');
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
        try {
            // Validate input
            $validated = $request->validate([
                'cari_aplikasi' => 'nullable|string|max:100',
            ]);

            // Sanitize search value
            $searchValue = isset($validated['cari_aplikasi']) ?
                strip_tags($validated['cari_aplikasi']) : null;

            // Cache key
            $cacheKey = 'portal_apps_'.md5($searchValue.$request->get('page', 1));

            // Get data with caching
            $data = Cache::remember($cacheKey, now()->addMinutes(1), function () use ($searchValue) {
                $query = AplikasiModel::latest();

                if ($searchValue) {
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('judul_aplikasi', 'like', "%{$searchValue}%")
                            ->orWhere('sub_judul_aplikasi', 'like', "%{$searchValue}%");
                    });
                }

                return $query->paginate(9);
            });

            if ($request->ajax()) {
                // Clear cache if refresh requested
                if ($request->has('refresh')) {
                    Cache::forget($cacheKey);
                    $data = AplikasiModel::latest()->paginate(9);
                }

                return response()->json([
                    'html' => view('frontend.infopublik.partials.applications-grid', compact('data'))->render(),
                    'pagination' => $data->links()->toHtml(),
                    'status' => 'success',
                ]);
            }

            return view('frontend.infopublik.aplikasi-index', compact('data'));

        } catch (\Exception $e) {
            Log::error('Error in infopublik_aplikasi_index: '.$e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat memuat data',
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat memuat data');
        }
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
