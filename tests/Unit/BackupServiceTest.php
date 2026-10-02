<?php

namespace Tests\Unit;

use App\Services\BackupService;
use App\Services\SqlServerDumpFormatter;
use Illuminate\Support\Facades\Storage;
use ReflectionMethod;
use Tests\TestCase;
use ZipArchive;

class BackupServiceTest extends TestCase
{
    public function test_identifier_is_bracket_quoted_and_escapes_closing_bracket(): void
    {
        $this->assertSame('[publikasi]', SqlServerDumpFormatter::identifier('publikasi'));
        $this->assertSame('[a]]b]', SqlServerDumpFormatter::identifier('a]b'));
    }

    public function test_literal_formats_each_php_type_for_tsql(): void
    {
        $this->assertSame('NULL', SqlServerDumpFormatter::literal(null));
        $this->assertSame('1', SqlServerDumpFormatter::literal(true));
        $this->assertSame('0', SqlServerDumpFormatter::literal(false));
        $this->assertSame('42', SqlServerDumpFormatter::literal(42));
        $this->assertSame("N'Biro BMN'", SqlServerDumpFormatter::literal('Biro BMN'));
        $this->assertSame("N''", SqlServerDumpFormatter::literal(''));
    }

    public function test_literal_doubles_single_quotes_so_data_cannot_break_out_of_the_string(): void
    {
        $this->assertSame("N'it''s'", SqlServerDumpFormatter::literal("it's"));
        $this->assertSame("N'x''); DROP TABLE users;--'", SqlServerDumpFormatter::literal("x'); DROP TABLE users;--"));
    }

    public function test_literal_keeps_utf8_text_and_hex_encodes_binary(): void
    {
        $this->assertSame("N'Kementerian Keuangan — Jakarta'", SqlServerDumpFormatter::literal('Kementerian Keuangan — Jakarta'));
        $this->assertSame('0x00ff80', SqlServerDumpFormatter::literal("\x00\xFF\x80"));
    }

    public function test_float_literal_round_trips(): void
    {
        $this->assertSame(0.1, (float) SqlServerDumpFormatter::literal(0.1));
        $this->assertSame(1.5e25, (float) SqlServerDumpFormatter::literal(1.5e25));
    }

    public function test_insert_lists_columns_explicitly(): void
    {
        $sql = SqlServerDumpFormatter::insert('publikasi', ['id' => 7, 'judul' => "Berita 'Baru'", 'deleted_at' => null]);

        $this->assertSame(
            "INSERT INTO [publikasi] ([id], [judul], [deleted_at]) VALUES (7, N'Berita ''Baru''', NULL);",
            $sql
        );
    }

    public function test_database_only_backup_contains_just_the_dump(): void
    {
        // Ganti langkah dump (butuh mysqldump/SQL Server sungguhan) dengan berkas palsu.
        $service = new class extends BackupService
        {
            protected function backupDatabase(string $outputPath)
            {
                file_put_contents($outputPath, '-- dump uji');

                return true;
            }
        };

        $name = $service->createBackup(databaseOnly: true);
        $zipPath = storage_path('app/backups/'.$name);

        $zip = new ZipArchive;
        $zip->open($zipPath);

        try {
            $this->assertSame(1, $zip->numFiles, 'backup db-only tidak boleh membawa kode/aplikasi');
            $this->assertStringEndsWith('_database.sql', $zip->getNameIndex(0));
            $this->assertSame('-- dump uji', $zip->getFromIndex(0));
        } finally {
            $zip->close();
            @unlink($zipPath);
        }
    }

    public function test_uploaded_files_are_added_to_the_backup_zip(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('romadan_gambar_web/foto.jpg', 'isi-gambar');
        Storage::disk('public')->put('romadan_file_web/laporan.pdf', 'isi-pdf');

        $zipPath = tempnam(sys_get_temp_dir(), 'bk').'.zip';
        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE);

        $method = new ReflectionMethod(BackupService::class, 'addUploadedFiles');
        $method->invoke(new BackupService, $zip);
        $zip->close();

        $check = new ZipArchive;
        $check->open($zipPath);

        try {
            $this->assertSame('isi-gambar', $check->getFromName('uploads/romadan_gambar_web/foto.jpg'));
            $this->assertSame('isi-pdf', $check->getFromName('uploads/romadan_file_web/laporan.pdf'));
        } finally {
            $check->close();
            @unlink($zipPath);
        }
    }
}
