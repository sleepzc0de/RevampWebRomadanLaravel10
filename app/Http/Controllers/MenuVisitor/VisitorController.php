<?php

namespace App\Http\Controllers\MenuVisitor;

use App\Helpers\ExcelExportHelper;
use App\Http\Controllers\Controller;
use App\Models\backend\MenuVisitor\VisitorModel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = VisitorModel::select('*');

            if (! $request->boolean('show_bot')) {
                $query->human();
            }

            if ($request->filled('device_type')) {
                $query->where('device_type', $request->device_type);
            }

            return datatables()->of($query)
                ->addColumn('waktu', function ($visitor) {
                    return $visitor->created_at->locale('id')->isoFormat('D MMM Y HH:mm:ss');
                })
                ->editColumn('ip_address', function ($visitor) {
                    return $visitor->ip_address ?: '-';
                })
                ->editColumn('referrer', function ($visitor) {
                    return $visitor->referrer ? e(str($visitor->referrer)->limit(60)) : '-';
                })
                ->addColumn('perangkat', function ($visitor) {
                    return e(($visitor->browser ?? '-').' / '.($visitor->platform ?? '-').' / '.($visitor->device_type ?? '-'));
                })
                ->addColumn('status', function ($visitor) {
                    return $visitor->is_bot
                        ? '<span class="badge bg-secondary">Bot</span>'
                        : '<span class="badge bg-success">Manusia</span>';
                })
                ->rawColumns(['status'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.visitor.index', [
            'kpi' => $this->kpi(),
            'topPages' => $this->topPages(),
            'dailyTrend' => $this->dailyTrend(),
        ]);
    }

    /**
     * Angka ringkasan utama untuk kartu KPI.
     */
    private function kpi(): array
    {
        $today = now()->startOfDay();

        return [
            'total' => VisitorModel::human()->count(),
            'today' => VisitorModel::human()->where('created_at', '>=', $today)->count(),
            'unique_today' => VisitorModel::human()->where('created_at', '>=', $today)->distinct()->count('ip_address'),
            'bots' => VisitorModel::where('is_bot', true)->count(),
        ];
    }

    /**
     * Halaman terpopuler 30 hari terakhir.
     */
    private function topPages(): array
    {
        return VisitorModel::human()
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('url, COUNT(*) as c')
            ->groupBy('url')
            ->orderByDesc('c')
            ->limit(8)
            ->pluck('c', 'url')
            ->toArray();
    }

    /**
     * Tren kunjungan 7 hari terakhir: satu COUNT per hari (portabel lintas DB).
     * Dulu semua baris 7 hari dimuat ke memori PHP lalu dihitung satu per satu —
     * pada situs ramai itu puluhan ribu baris setiap kali dasbor dibuka.
     */
    private function dailyTrend(): array
    {
        $start = now()->startOfDay()->subDays(6);

        $labels = [];
        $data = [];

        for ($i = 0; $i < 7; $i++) {
            $day = (clone $start)->addDays($i);

            $labels[] = $day->locale('id')->isoFormat('D MMM');
            $data[] = VisitorModel::human()
                ->whereBetween('created_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
                ->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Hapus data kunjungan yang lebih lama dari N hari (default 90 hari).
     */
    public function clean(Request $request)
    {
        // Batas bawah 7 hari: days=0 atau negatif akan mengosongkan seluruh tabel.
        $validated = $request->validate([
            'days' => 'nullable|integer|min:7|max:3650',
        ]);
        $days = (int) ($validated['days'] ?? 90);

        $deleted = VisitorModel::where('created_at', '<', now()->subDays($days))->delete();

        activity('audit')
            ->causedBy($request->user())
            ->withProperties(['days' => $days, 'deleted' => $deleted])
            ->log("Membersihkan data pengunjung lebih dari {$days} hari ({$deleted} baris)");

        return redirect()->route('visitors.index')->with('success', "Data pengunjung lebih dari {$days} hari berhasil dibersihkan!");
    }

    /**
     * Ekspor log pengunjung (maks 5000 baris terbaru) ke Excel.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $query = VisitorModel::select('*')->latest();

        if (! $request->boolean('show_bot')) {
            $query->human();
        }

        $rows = $query->limit(5000)->get()->map(fn ($visitor) => [
            $visitor->created_at->format('Y-m-d H:i:s'),
            $visitor->ip_address,
            $visitor->url,
            $visitor->browser,
            $visitor->platform,
            $visitor->device_type,
            $visitor->referrer,
            $visitor->is_bot ? 'Bot' : 'Manusia',
        ]);

        return ExcelExportHelper::stream(
            'pengunjung-'.now()->format('Ymd-His').'.xlsx',
            ['Waktu', 'IP Address', 'Halaman', 'Browser', 'Platform', 'Perangkat', 'Referrer', 'Status'],
            $rows
        );
    }
}
