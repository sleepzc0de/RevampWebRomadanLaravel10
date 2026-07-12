<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\SecurityHelper;
use App\Helpers\VisitorHelper;
use App\Http\Controllers\Controller;
use App\Models\backend\MenuFAQ\FAQModel;
use App\Models\backend\MenuInformasiPublik\AplikasiModel;
use App\Models\backend\MenuInformasiPublik\InfopublikHomeModel;
use App\Models\backend\MenuInformasiPublik\InformasiPublikModel;
use App\Models\backend\MenuInformasiPublik\PedomanModel;
use App\Models\backend\MenuInformasiPublik\PeraturanModel;
use App\Models\backend\MenuKegiatan\KegiatanModel;
use App\Models\backend\MenuLayanan\LayananModel;
use App\Models\backend\MenuProfile\SejarahModel;
use App\Models\backend\MenuProfile\StrukturOrganisasiModel;
use App\Models\backend\MenuProfile\TentangModel;
use App\Models\backend\MenuProfile\VisiMisiModel;
use App\Models\backend\MenuPublikasi\PublikasiModel;
use App\Models\backend\MenuReferensi\RefJenisPeraturan;
use App\Models\backend\MenuVisitor\VisitorModel;
use App\Models\backend\RefKategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HomeFeController extends Controller
{
    private const STATUS_PUBLISHED = 'Published';

    public function index()
    {
        //
        $status_berita = 'Published';

        $tentang = TentangModel::latest()->take(1)->get();

        $beritaPublishedQuery = fn () => PublikasiModel::join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_status', 'publikasi.status', '=', 'ref_status.nama_status')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_status.nama_status', 'ref_tipe.nama_tipe')
            ->where('nama_tipe', strtolower('Berita'))
            ->whereRaw('LOWER(ref_status.nama_status) like ?', ['%'.strtolower($status_berita).'%']);

        // Hero: 3 berita terbaru
        $berita_terkini = $beritaPublishedQuery()
            ->orderBy('publikasi.updated_at', 'desc')
            ->take(3)
            ->get();

        // Section kedua: 3 berita terpopuler (dilihat dari jumlah views)
        $berita_terpopuler = $beritaPublishedQuery()
            ->orderBy('publikasi.views', 'desc')
            ->take(3)
            ->get();

        return view('frontend.home_fe', compact(['tentang', 'berita_terkini', 'berita_terpopuler']));
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
            ->where('status', self::STATUS_PUBLISHED)
            ->with('images')  // Add this line to load all related images
            ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')
            ->firstOrFail();

        $this->incrementViewsOncePerIp($data);

        // Format tanggal
        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y');

        // Menampilkan ke view
        return view('frontend.publikasi.fe_berita', compact(['data', 'tb']));
    }

    public function publikasi_warta($publikasi)
    {
        $data = PublikasiModel::where('slug', $publikasi)
            ->where('nama_tipe', strtolower('Warta'))
            ->where('status', self::STATUS_PUBLISHED)
            ->with('images')  // Add this line to load all related images
            ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')
            ->firstorFail();

        $this->incrementViewsOncePerIp($data);

        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y', 'j F Y');

        return view('frontend.publikasi.fe_warta', compact(['data', 'tb']));
    }

    public function publikasi_artikel($publikasi)
    {
        $data = PublikasiModel::where('slug', $publikasi)
            ->where('nama_tipe', strtolower('Artikel'))
            ->where('status', self::STATUS_PUBLISHED)
            ->with('images')  // Add this line to load all related images
            ->join('ref_kategori', 'publikasi.kategori', '=', 'ref_kategori.id_kategori')
            ->join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->select('publikasi.*', 'ref_kategori.nama_kategori', 'ref_tipe.nama_tipe')
            ->firstorFail();

        $this->incrementViewsOncePerIp($data);

        $tb = Carbon::parse($data->created_at)->translatedFormat('d F Y', 'j F Y');

        return view('frontend.publikasi.fe_artikel', compact(['data', 'tb']));
    }

    /**
     * Tambah jumlah views, tapi hanya sekali per (IP, halaman) dalam 24 jam
     * terakhir — mencegah views naik terus saat pengunjung yang sama
     * me-refresh halaman berkali-kali. Deteksi "sudah pernah dilihat" memakai
     * log kunjungan (tabel visitors) yang sudah tercatat lebih dulu oleh
     * middleware LogVisitor pada request-request sebelumnya.
     * Bot/crawler tidak dihitung sama sekali.
     */
    private function incrementViewsOncePerIp(PublikasiModel $data): void
    {
        if (VisitorHelper::isBot(request()->userAgent())) {
            return;
        }

        $alreadyViewed = VisitorModel::where('ip_address', request()->ip())
            ->where('url', request()->path())
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if ($alreadyViewed) {
            return;
        }

        // Menambah jumlah views tanpa menyentuh updated_at
        // (urutan "terkini" di beranda memakai updated_at)
        $data->timestamps = false;
        $data->increment('views');
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

    public function infopublik_pedoman_index(Request $request)
    {
        $kategori = RefKategori::all();
        $searchValue = strip_tags((string) $request->input('cari_pedoman'));
        $selectedKategori = $request->input('kategori');

        $query = PedomanModel::with('dataKategori');

        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('judul_pedoman', 'like', '%'.$searchValue.'%')
                    ->orWhere('deskripsi', 'like', '%'.$searchValue.'%')
                    ->orWhereHas('dataKategori', function ($q) use ($searchValue) {
                        $q->where('nama_kategori', 'like', '%'.$searchValue.'%');
                    });
            });
        }

        if ($selectedKategori) {
            $query->whereHas('dataKategori', function ($q) use ($selectedKategori) {
                $q->whereIn('nama_kategori', $selectedKategori);
            });
        }

        $pedoman = $query->latest()->paginate(9);

        return view('frontend.infopublik.pedoman-index', compact(['pedoman', 'searchValue', 'kategori', 'selectedKategori']));
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

    private const SEARCH_LIMIT_PER_TYPE = 15;

    private const SEARCH_PER_PAGE = 10;

    /**
     * Pencarian global lintas seluruh tipe konten publik.
     */
    public function globalSearch(Request $request)
    {
        $query = SecurityHelper::sanitizeInput((string) $request->input('q', ''));

        $results = collect();

        if (mb_strlen($query) >= 2) {
            $throttleKey = 'global-search:'.$request->ip();

            if (RateLimiter::tooManyAttempts($throttleKey, 30)) {
                abort(429);
            }
            RateLimiter::hit($throttleKey, 60);

            $results = collect()
                ->merge($this->searchPublikasi($query))
                ->merge($this->searchPeraturan($query))
                ->merge($this->searchPedoman($query))
                ->merge($this->searchAplikasi($query))
                ->merge($this->searchFaq($query))
                ->merge($this->searchKegiatan($query))
                ->merge($this->searchLayanan($query))
                ->sortByDesc('date')
                ->values();
        }

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = self::SEARCH_PER_PAGE;

        $paginated = new LengthAwarePaginator(
            $results->slice(($page - 1) * $perPage, $perPage)->values(),
            $results->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('frontend.search.index', [
            'query' => $query,
            'results' => $paginated,
        ]);
    }

    private function searchPublikasi(string $q): Collection
    {
        return PublikasiModel::join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->where('publikasi.status', self::STATUS_PUBLISHED)
            ->where(function ($query) use ($q) {
                $query->where('judul', 'like', "%{$q}%")->orWhere('sub_judul', 'like', "%{$q}%");
            })
            ->orderByDesc('publikasi.id')
            ->limit(self::SEARCH_LIMIT_PER_TYPE)
            ->get(['publikasi.judul', 'publikasi.slug', 'publikasi.sub_judul', 'publikasi.created_at', 'ref_tipe.nama_tipe'])
            ->map(function ($item) {
                $namaTipe = (string) $item['nama_tipe'];
                $routeName = match ($namaTipe) {
                    'warta' => 'warta-fe',
                    'artikel' => 'artikel-fe',
                    default => 'berita-fe',
                };

                return [
                    'type' => ucfirst($namaTipe),
                    'title' => $item->judul,
                    'excerpt' => $item->sub_judul,
                    'url' => route($routeName, $item->slug),
                    'date' => $item->created_at,
                ];
            });
    }

    private function searchPeraturan(string $q): Collection
    {
        return PeraturanModel::where(function ($query) use ($q) {
            $query->where('judul_peraturan', 'like', "%{$q}%")->orWhere('nomor_peraturan', 'like', "%{$q}%");
        })
            ->orderByDesc('id')
            ->limit(self::SEARCH_LIMIT_PER_TYPE)
            ->get(['judul_peraturan', 'slug', 'nomor_peraturan', 'created_at'])
            ->map(fn ($item) => [
                'type' => 'Peraturan',
                'title' => $item->judul_peraturan,
                'excerpt' => $item->nomor_peraturan,
                'url' => route('informasi-publik-peraturan-detail-fe', $item->slug),
                'date' => $item->created_at,
            ]);
    }

    private function searchPedoman(string $q): Collection
    {
        return PedomanModel::where(function ($query) use ($q) {
            $query->where('judul_pedoman', 'like', "%{$q}%")->orWhere('deskripsi', 'like', "%{$q}%");
        })
            ->orderByDesc('id')
            ->limit(self::SEARCH_LIMIT_PER_TYPE)
            ->get(['judul_pedoman', 'deskripsi', 'created_at'])
            ->map(fn ($item) => [
                'type' => 'Pedoman',
                'title' => $item->judul_pedoman,
                'excerpt' => Str::limit(strip_tags((string) $item->deskripsi), 140),
                'url' => route('informasi-publik-pedoman-index-fe'),
                'date' => $item->created_at,
            ]);
    }

    private function searchAplikasi(string $q): Collection
    {
        return AplikasiModel::where(function ($query) use ($q) {
            $query->where('judul_aplikasi', 'like', "%{$q}%")->orWhere('sub_judul_aplikasi', 'like', "%{$q}%");
        })
            ->orderByDesc('id')
            ->limit(self::SEARCH_LIMIT_PER_TYPE)
            ->get(['judul_aplikasi', 'sub_judul_aplikasi', 'created_at'])
            ->map(fn ($item) => [
                'type' => 'Aplikasi',
                'title' => $item->judul_aplikasi,
                'excerpt' => $item->sub_judul_aplikasi,
                'url' => route('informasi-publik-aplikasi-index-fe'),
                'date' => $item->created_at,
            ]);
    }

    private function searchFaq(string $q): Collection
    {
        return FAQModel::where(function ($query) use ($q) {
            $query->where('faq_judul', 'like', "%{$q}%")->orWhere('faq_isi', 'like', "%{$q}%");
        })
            ->orderByDesc('id')
            ->limit(self::SEARCH_LIMIT_PER_TYPE)
            ->get(['faq_judul', 'faq_isi', 'created_at'])
            ->map(fn ($item) => [
                'type' => 'FAQ',
                'title' => $item->faq_judul,
                'excerpt' => Str::limit(strip_tags((string) $item->faq_isi), 140),
                'url' => route('faq-index-fe'),
                'date' => $item->created_at,
            ]);
    }

    private function searchKegiatan(string $q): Collection
    {
        return KegiatanModel::where(function ($query) use ($q) {
            $query->where('judul', 'like', "%{$q}%")->orWhere('tempat', 'like', "%{$q}%");
        })
            ->orderByDesc('id')
            ->limit(self::SEARCH_LIMIT_PER_TYPE)
            ->get(['judul', 'tempat', 'slug', 'static_random_string', 'created_at'])
            ->map(fn ($item) => [
                'type' => 'Kegiatan',
                'title' => $item->judul,
                'excerpt' => $item->tempat,
                'url' => route('kegiatan-detail-fe', [$item->slug, $item->static_random_string]),
                'date' => $item->created_at,
            ]);
    }

    private function searchLayanan(string $q): Collection
    {
        return LayananModel::where('judul', 'like', "%{$q}%")
            ->orderByDesc('id')
            ->limit(self::SEARCH_LIMIT_PER_TYPE)
            ->get(['judul', 'layanan', 'created_at'])
            ->map(fn ($item) => [
                'type' => 'Layanan',
                'title' => $item->judul,
                'excerpt' => Str::limit(strip_tags((string) $item->layanan), 140),
                'url' => route('layanan-fe'),
                'date' => $item->created_at,
            ]);
    }

    /**
     * Sitemap XML untuk seluruh konten publik. Di-cache 1 jam karena
     * mengagregasi banyak query dan tidak butuh presisi real-time.
     */
    public function sitemap()
    {
        $urls = Cache::remember('sitemap_urls', now()->addHour(), function () {
            return collect()
                ->merge($this->sitemapStaticUrls())
                ->merge($this->sitemapPublikasiUrls())
                ->merge($this->sitemapPeraturanUrls())
                ->merge($this->sitemapKegiatanUrls());
        });

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'text/xml');
    }

    private function sitemapStaticUrls(): Collection
    {
        $routes = [
            ['name' => 'homefe', 'priority' => '1.0'],
            ['name' => 'layanan-fe', 'priority' => '0.7'],
            ['name' => 'informasi-publik-index-fe', 'priority' => '0.7'],
            ['name' => 'informasi-publik-peraturan-index-fe', 'priority' => '0.7'],
            ['name' => 'informasi-publik-pedoman-index-fe', 'priority' => '0.7'],
            ['name' => 'informasi-publik-aplikasi-index-fe', 'priority' => '0.6'],
            ['name' => 'publikasi-index-fe', 'priority' => '0.8'],
            ['name' => 'publikasi-index-berita-fe', 'priority' => '0.7'],
            ['name' => 'publikasi-index-warta-fe', 'priority' => '0.7'],
            ['name' => 'publikasi-index-artikel-fe', 'priority' => '0.7'],
            ['name' => 'faq-index-fe', 'priority' => '0.6'],
            ['name' => 'kegiatan-index-fe', 'priority' => '0.7'],
            ['name' => 'visi-misi-fe', 'priority' => '0.5'],
            ['name' => 'sejarah-fe', 'priority' => '0.5'],
            ['name' => 'organisasi-fe', 'priority' => '0.5'],
            ['name' => 'tentang-fe', 'priority' => '0.5'],
        ];

        return collect($routes)->map(fn ($r) => [
            'loc' => route($r['name']),
            'lastmod' => null,
            'priority' => $r['priority'],
        ]);
    }

    private function sitemapPublikasiUrls(): Collection
    {
        return PublikasiModel::join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->where('publikasi.status', self::STATUS_PUBLISHED)
            ->get(['publikasi.slug', 'publikasi.updated_at', 'ref_tipe.nama_tipe'])
            ->map(function ($item) {
                $routeName = match ($item['nama_tipe']) {
                    'warta' => 'warta-fe',
                    'artikel' => 'artikel-fe',
                    default => 'berita-fe',
                };

                return [
                    'loc' => route($routeName, $item->slug),
                    'lastmod' => optional($item->updated_at)->toAtomString(),
                    'priority' => '0.8',
                ];
            });
    }

    private function sitemapPeraturanUrls(): Collection
    {
        return PeraturanModel::get(['slug', 'updated_at'])->map(fn ($item) => [
            'loc' => route('informasi-publik-peraturan-detail-fe', $item->slug),
            'lastmod' => optional($item->updated_at)->toAtomString(),
            'priority' => '0.6',
        ]);
    }

    private function sitemapKegiatanUrls(): Collection
    {
        return KegiatanModel::get(['slug', 'static_random_string', 'updated_at'])->map(fn ($item) => [
            'loc' => route('kegiatan-detail-fe', [$item->slug, $item->static_random_string]),
            'lastmod' => optional($item->updated_at)->toAtomString(),
            'priority' => '0.6',
        ]);
    }
}
