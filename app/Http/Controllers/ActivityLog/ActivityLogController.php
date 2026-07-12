<?php

namespace App\Http\Controllers\ActivityLog;

use App\Helpers\ExcelExportHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // NB: jangan tambahkan orderBy pada base query — yajra datatables
        // membungkusnya dalam subquery count(*) untuk SQL Server, dan
        // SQL Server menolak ORDER BY di dalam derived table tanpa TOP/OFFSET.
        // Urutan default (terbaru dulu) sudah diatur di kolom `order` pada
        // konfigurasi DataTable sisi klien (backend/activity-log/index.blade.php).
        $query = Activity::with('causer')->select('activity_log.*');

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('waktu', function ($activity) {
                    return $activity->created_at->locale('id')->isoFormat('D MMM Y HH:mm:ss');
                })
                ->addColumn('pengguna', function ($activity) {
                    return optional($activity->causer)->name ?? 'System';
                })
                ->addColumn('modul', function ($activity) {
                    return ucwords(str_replace('_', ' ', (string) $activity->log_name));
                })
                ->addColumn('event', function ($activity) {
                    $badges = [
                        'created' => 'bg-success',
                        'updated' => 'bg-warning',
                        'deleted' => 'bg-danger',
                    ];
                    $badge = $badges[$activity->event] ?? 'bg-secondary';
                    $label = $activity->event ? ucfirst($activity->event) : '-';

                    return '<span class="badge '.$badge.'">'.e($label).'</span>';
                })
                ->addColumn('deskripsi', function ($activity) {
                    return e($activity->description);
                })
                ->addColumn('detail', function ($activity) {
                    return '<button type="button" class="btn btn-sm btn-light activity-detail-btn" data-properties="'.e($activity->properties->toJson()).'" data-subject="'.e((string) $activity->subject_type).'">Detail</button>';
                })
                ->rawColumns(['event', 'detail'])
                ->addIndexColumn()
                ->make(true);
        }

        $logNames = Activity::query()->distinct()->orderBy('log_name')->pluck('log_name');

        return view('backend.activity-log.index', compact('logNames'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Activity::findOrFail(decrypt($id))->delete();

        return redirect()->route('activity-log.index')->with('success', 'Log aktivitas berhasil dihapus!');
    }

    /**
     * Hapus semua log aktivitas yang lebih lama dari N hari (default konfigurasi).
     */
    public function clean(Request $request)
    {
        $days = (int) $request->input('days', config('activitylog.clean_after_days', 365));

        Activity::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->route('activity-log.index')->with('success', "Log aktivitas lebih dari {$days} hari berhasil dibersihkan!");
    }

    /**
     * Ekspor log aktivitas (maks 5000 baris terbaru) ke Excel.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $query = Activity::with('causer')->select('activity_log.*')->latest();

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        $rows = $query->limit(5000)->get()->map(fn ($activity) => [
            $activity->created_at->format('Y-m-d H:i:s'),
            ucwords(str_replace('_', ' ', (string) $activity->log_name)),
            $activity->event,
            $activity->description,
            optional($activity->causer)->name ?? 'System',
        ]);

        return ExcelExportHelper::stream(
            'log-aktivitas-'.now()->format('Ymd-His').'.xlsx',
            ['Waktu', 'Modul', 'Event', 'Deskripsi', 'Pengguna'],
            $rows
        );
    }
}
