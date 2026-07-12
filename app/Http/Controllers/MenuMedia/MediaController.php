<?php

namespace App\Http\Controllers\MenuMedia;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuMedia\MediaModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Tabel & kolom yang menyimpan nama file — dipakai untuk mengecek apakah
     * sebuah file di Media Library masih dipakai oleh konten manapun,
     * sebelum mengizinkan file itu dihapus.
     */
    private const FILE_SOURCES = [
        ['publikasi', 'image', 'Publikasi'],
        ['publikasi', 'file', 'Publikasi'],
        ['publikasi_images', 'image_path', 'Publikasi (galeri)'],
        ['kegiatan', 'image', 'Kegiatan'],
        ['kegiatan', 'file', 'Kegiatan'],
        ['peraturan', 'file', 'Peraturan'],
        ['pedoman', 'file', 'Pedoman'],
        ['aplikasi', 'image', 'Link Aplikasi'],
        ['login_gambar', 'image', 'Gambar Login'],
        ['layanan', 'image', 'Layanan'],
        ['layanan_images', 'image_path', 'Layanan (galeri)'],
        ['tentang', 'image', 'Tentang Biro'],
        ['tentang_images', 'image_path', 'Tentang Biro (galeri)'],
        ['visimisi', 'image', 'Visi & Misi'],
        ['visimisi_images', 'image', 'Visi & Misi (galeri)'],
        ['sejarah', 'image', 'Sejarah'],
        ['struktur_pejabat', 'foto', 'Struktur Organisasi (foto pejabat)'],
        ['developers', 'photo', 'Tim Pengembang'],
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $usedBy = $this->usedFilenames();

            $query = MediaModel::select('*');

            if ($request->filled('type')) {
                $query->where('mime_type', 'like', $request->type.'/%');
            }

            return datatables()->of($query)
                ->addColumn('preview', function ($media) {
                    if ($media->isImage()) {
                        $blobUrl = route('media.blob', [$media->folder, $media->filename]);

                        return '<img data-blob-src="'.e($blobUrl).'" class="img-rounded" width="60" height="60" style="object-fit:cover;border-radius:8px;background:#eef1f4;">';
                    }

                    return '<i class="ph-file-text" style="font-size:1.75rem;"></i>';
                })
                ->addColumn('ukuran', function ($media) {
                    return $this->formatBytes($media->size);
                })
                ->addColumn('digunakan', function ($media) use ($usedBy) {
                    $usage = $usedBy[$media->filename] ?? null;

                    return $usage
                        ? '<span class="badge bg-success">'.e($usage).'</span>'
                        : '<span class="badge bg-secondary">Tidak terpakai</span>';
                })
                ->addColumn('waktu', function ($media) {
                    return $media->created_at->locale('id')->isoFormat('D MMM Y HH:mm');
                })
                ->addColumn('opsi', function ($media) use ($usedBy) {
                    if (isset($usedBy[$media->filename])) {
                        return '<button type="button" class="btn btn-sm btn-light" disabled title="Masih dipakai, tidak bisa dihapus"><i class="ph-lock"></i></button>';
                    }

                    return '<button type="button" class="btn btn-sm btn-outline-danger media-delete-btn" data-id="'.encrypt($media->id).'" data-name="'.e($media->filename).'"><i class="ph-trash"></i></button>';
                })
                ->rawColumns(['preview', 'digunakan', 'opsi'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.media.index', [
            'kpi' => $this->kpi(),
        ]);
    }

    public function sync(Request $request)
    {
        Artisan::call('media:sync');

        return redirect()->route('media.index')->with('success', trim(Artisan::output()));
    }

    public function destroy(string $id)
    {
        try {
            $media = MediaModel::findOrFail(decrypt($id));

            $usedBy = $this->usedFilenames();
            if (isset($usedBy[$media->filename])) {
                return redirect()->route('media.index')->with(['failed' => 'File ini masih dipakai oleh '.$usedBy[$media->filename].', tidak bisa dihapus.']);
            }

            Storage::disk($media->disk)->delete($media->folder.'/'.$media->filename);
            $media->delete();

            return redirect()->route('media.index')->with('success', 'File berhasil dihapus.');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('media.index')->with(['failed' => 'Gagal menghapus file.']);
        }
    }

    private function kpi(): array
    {
        return [
            'total' => MediaModel::count(),
            'images' => MediaModel::where('mime_type', 'like', 'image/%')->count(),
            'documents' => MediaModel::where('mime_type', 'not like', 'image/%')->orWhereNull('mime_type')->count(),
            'total_size' => $this->formatBytes((int) MediaModel::sum('size')),
        ];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' B';
        }
        if ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 1).' KB';
        }

        return round($bytes / (1024 * 1024), 1).' MB';
    }

    /**
     * @return array<string, string> [nama_file => "Dipakai di ..."]
     */
    private function usedFilenames(): array
    {
        $used = [];

        foreach (self::FILE_SOURCES as [$table, $column, $label]) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
                continue;
            }

            DB::table($table)->whereNotNull($column)->pluck($column)->each(function ($value) use (&$used, $label) {
                if ($value) {
                    $used[$value] = $label;
                }
            });
        }

        // sejarah.media -> JSON array berisi item bertipe image dengan key "path".
        // Beberapa data lama tersimpan ter-encode ganda (json_encode dua kali),
        // jadi decode diulang sekali lagi bila hasil pertama masih berupa string.
        if (Schema::hasTable('sejarah') && Schema::hasColumn('sejarah', 'media')) {
            DB::table('sejarah')->whereNotNull('media')->pluck('media')->each(function ($json) use (&$used) {
                $items = json_decode((string) $json, true);
                if (is_string($items)) {
                    $items = json_decode($items, true);
                }
                if (! is_array($items)) {
                    return;
                }
                foreach ($items as $item) {
                    if (is_array($item) && ($item['type'] ?? null) === 'image' && ! empty($item['path'])) {
                        $used[$item['path']] = 'Sejarah (galeri)';
                    }
                }
            });
        }

        return $used;
    }
}
