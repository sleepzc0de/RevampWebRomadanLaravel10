<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuFAQ\FAQModel;
use App\Models\backend\MenuInformasiPublik\AplikasiModel;
use App\Models\backend\MenuInformasiPublik\PeraturanModel;
use App\Models\backend\MenuLayanan\LayananModel;
use App\Models\backend\MenuPublikasi\PublikasiModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeBeController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return $this->contentActivityTable();
        }

        return view('backend.dashboard.dashboard', [
            'kpi' => $this->kpi(),
            'byTipe' => $this->countByTipe(),
            'monthly' => $this->monthlyTrend(),
            'topViewed' => $this->topViewed(),
            'recent' => $this->recentPublikasi(),
        ]);
    }

    /**
     * Angka ringkasan utama. Semua via agregat (bukan ->all()->count()).
     */
    private function kpi(): array
    {
        // Satu query untuk breakdown status (case-insensitive)
        $byStatus = PublikasiModel::selectRaw('LOWER(status) as s, COUNT(*) as c')
            ->groupBy(DB::raw('LOWER(status)'))
            ->pluck('c', 's');

        return [
            'publikasi_total' => (int) $byStatus->sum(),
            'published' => (int) ($byStatus['published'] ?? 0),
            'draft' => (int) ($byStatus['draft'] ?? 0),
            'trashed' => PublikasiModel::onlyTrashed()->count(),
            'views_total' => (int) PublikasiModel::sum('views'),
            'peraturan' => PeraturanModel::count(),
            'aplikasi' => AplikasiModel::count(),
            'faq' => FAQModel::count(),
            'layanan' => LayananModel::count(),
            'users' => User::count(),
        ];
    }

    /**
     * Jumlah publikasi per tipe (Berita/Warta/Artikel) — satu query.
     */
    private function countByTipe(): array
    {
        $rows = PublikasiModel::join('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->selectRaw('ref_tipe.nama_tipe as tipe, COUNT(*) as c')
            ->groupBy('ref_tipe.nama_tipe')
            ->pluck('c', 'tipe');

        // Normalisasi label agar konsisten (huruf kapital di depan)
        $out = ['Berita' => 0, 'Warta' => 0, 'Artikel' => 0];
        foreach ($rows as $tipe => $c) {
            $key = ucfirst(strtolower($tipe));
            if (array_key_exists($key, $out)) {
                $out[$key] = (int) $c;
            }
        }

        return $out;
    }

    /**
     * Tren jumlah publikasi 6 bulan terakhir.
     * Bucket dilakukan di PHP agar portabel lintas DB (MySQL/SQL Server/PgSQL).
     */
    private function monthlyTrend(): array
    {
        $start = Carbon::now()->startOfMonth()->subMonths(5);

        $dates = PublikasiModel::where('created_at', '>=', $start)
            ->orderBy('created_at')
            ->pluck('created_at');

        // Siapkan 6 bucket kosong
        $buckets = [];
        for ($i = 0; $i < 6; $i++) {
            $m = (clone $start)->addMonths($i);
            $buckets[$m->format('Y-m')] = [
                'label' => $m->locale('id')->isoFormat('MMM Y'),
                'count' => 0,
            ];
        }

        foreach ($dates as $d) {
            $key = Carbon::parse($d)->format('Y-m');
            if (isset($buckets[$key])) {
                $buckets[$key]['count']++;
            }
        }

        return [
            'labels' => array_values(array_column($buckets, 'label')),
            'data' => array_values(array_column($buckets, 'count')),
        ];
    }

    private function topViewed()
    {
        // Join ref_tipe (bukan select kolom nama_tipe langsung) agar portabel
        // & menghindari tabrakan nama kolom `tipe` dengan relasi tipe().
        return PublikasiModel::leftJoin('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->orderByDesc('publikasi.views')
            ->take(5)
            ->get(['publikasi.judul', 'publikasi.slug', 'publikasi.views', 'ref_tipe.nama_tipe']);
    }

    private function recentPublikasi()
    {
        return PublikasiModel::leftJoin('ref_tipe', 'publikasi.tipe', '=', 'ref_tipe.id_tipe')
            ->latest('publikasi.created_at')
            ->take(6)
            ->get([
                'publikasi.id', 'publikasi.judul', 'publikasi.status',
                'publikasi.views', 'publikasi.created_at', 'ref_tipe.nama_tipe',
            ]);
    }

    /**
     * Tabel "Content Activity" (DataTables server-side) — tetap seperti semula.
     */
    private function contentActivityTable()
    {
        $query = PublikasiModel::with(['kategori', 'status', 'tipe'])->select('*');

        return datatables()->of($query)
            ->addColumn('image_publikasi', function ($query) {
                $url = asset('storage/romadan_gambar_web/'.$query->image);

                return '<a href="'.$url.'"><img src="'.$url.'" border="0" width="100" class="img-rounded" align="center""/></a>';
            })
            ->addColumn('opsi', function ($query) {
                return view('components.datatable-actions', [
                    'preview' => route('publikasi.show', encrypt($query->id)),
                    'edit' => route('publikasi.edit', encrypt($query->id)),
                    'destroy' => route('publikasi.destroy', encrypt($query->id)),
                ])->render();
            })
            ->editColumn('created_at', function ($query) {
                return Carbon::parse($query->created_at)->locale('id')->isoFormat('D-MMMM-Y HH:mm:ss').' WIB';
            })
            ->rawColumns(['opsi', 'image_publikasi'])
            ->addIndexColumn()
            ->make(true);
    }
}
