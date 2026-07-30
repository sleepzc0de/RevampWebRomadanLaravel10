<?php

namespace App\Http\Controllers\Tim;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuPublikasi\PublikasiModel;
use App\Models\User;
use Illuminate\View\View;

/**
 * Tim Konten Publikasi — daftar siapa saja yang berwenang mengelola konten
 * beserta jabatannya.
 *
 * Sengaja TIDAK memakai tabel terpisah: keanggotaan diturunkan langsung dari
 * role pengguna yang memang menjadi gerbang akses modul publikasi (lihat
 * middleware role pada routes/web.php). Dengan begitu daftar ini tidak pernah
 * basi — begitu role seseorang diubah di User Manajemen, halaman ini ikut
 * berubah, dan tidak ada risiko data ganda yang saling bertentangan.
 */
class TimKontenController extends Controller
{
    /** Role yang membentuk tim konten, diurut dari wewenang tertinggi. */
    private const JABATAN = [
        'ADMINISTRATOR' => [
            'label' => 'Administrator',
            'tugas' => 'Akses penuh seluruh modul CMS dan pengaturan sistem',
            'ikon' => 'ph-shield-star',
            'warna' => 'var(--color-brand-500)',
        ],
        'REDAKTUR' => [
            'label' => 'Redaktur',
            'tugas' => 'Menyetujui dan menerbitkan konten publikasi',
            'ikon' => 'ph-check-circle',
            'warna' => '#059669',
        ],
        'EDITOR' => [
            'label' => 'Editor',
            'tugas' => 'Menyusun dan menyunting materi publikasi',
            'ikon' => 'ph-pencil-line',
            'warna' => '#d69e00',
        ],
        'HUMAS' => [
            'label' => 'Hubungan Masyarakat',
            'tugas' => 'Publikasi dan komunikasi informasi kepada publik',
            'ikon' => 'ph-megaphone',
            'warna' => '#64748b',
        ],
    ];

    public function index(): View
    {
        $roleTim = array_keys(self::JABATAN);

        // 1 query: anggota + role-nya (eager load, tanpa N+1)
        $anggota = User::with('roles')
            ->whereHas('roles', fn ($q) => $q->whereIn('name', $roleTim))
            ->orderBy('name')
            ->get();

        // 2 query agregat: kontribusi menulis & menyunting untuk SEMUA anggota
        // sekaligus. publikasi.penulis/pengedit menyimpan nama pengguna.
        $statMenulis = PublikasiModel::query()
            ->selectRaw('penulis, COUNT(*) AS jumlah, MAX(created_at) AS terakhir')
            ->whereNotNull('penulis')
            ->groupBy('penulis')
            ->get()
            ->keyBy('penulis');

        $statMenyunting = PublikasiModel::query()
            ->selectRaw('pengedit, COUNT(*) AS jumlah')
            ->whereNotNull('pengedit')
            ->groupBy('pengedit')
            ->get()
            ->keyBy('pengedit');

        // Rakit data per anggota
        $anggota = $anggota->map(function (User $u) use ($statMenulis, $statMenyunting) {
            $menulis = $statMenulis->get($u->name);
            $menyunting = $statMenyunting->get($u->name);

            return [
                'nama' => $u->name,
                'email' => $u->email,
                'inisial' => $this->inisial($u->name),
                'role' => $u->roles->pluck('name')->first(),
                'semua_role' => $u->roles->pluck('name')->all(),
                'ditulis' => (int) ($menulis->jumlah ?? 0),
                'disunting' => (int) ($menyunting->jumlah ?? 0),
                'terakhir_menulis' => $menulis->terakhir ?? null,
                'dua_faktor' => $u->two_factor_confirmed_at !== null,
                'bergabung' => $u->created_at,
            ];
        });

        // Kelompokkan mengikuti urutan wewenang, bukan urutan abjad role
        $perJabatan = [];
        foreach (self::JABATAN as $role => $meta) {
            $perJabatan[$role] = [
                'meta' => $meta,
                'anggota' => $anggota->where('role', $role)->values(),
            ];
        }

        return view('backend.tim-konten.index', [
            'perJabatan' => $perJabatan,
            'totalAnggota' => $anggota->count(),
            'totalPublikasiTim' => $anggota->sum('ditulis'),
            'tanpaDuaFaktor' => $anggota->where('dua_faktor', false)->count(),
            'terproduktif' => $anggota->sortByDesc('ditulis')->first(),
        ]);
    }

    private function inisial(string $nama): string
    {
        $bagian = preg_split('/\s+/', trim($nama)) ?: [];
        $inisial = mb_substr($bagian[0] ?? '', 0, 1);

        if (count($bagian) > 1) {
            $inisial .= mb_substr(end($bagian), 0, 1);
        }

        return mb_strtoupper($inisial);
    }
}
