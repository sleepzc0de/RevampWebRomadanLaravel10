<?php

namespace App\Services;

/**
 * Pembentuk pernyataan T-SQL untuk dump data SQL Server lewat PHP (dipakai
 * BackupService pada Linux, tempat BACKUP DATABASE native tidak bisa menulis
 * ke disk server aplikasi). Murni string-in/string-out agar mudah diuji.
 */
class SqlServerDumpFormatter
{
    /**
     * Kutip identifier: [nama] dengan "]" digandakan.
     */
    public static function identifier(string $name): string
    {
        return '['.str_replace(']', ']]', $name).']';
    }

    /**
     * Ubah satu nilai PHP menjadi literal T-SQL.
     */
    public static function literal(mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            // 17 digit signifikan = round-trip penuh untuk float64
            return sprintf('%.17G', $value);
        }

        $value = (string) $value;

        // Data biner (varbinary/image) bukan UTF-8 valid → heksadesimal.
        if ($value !== '' && ! mb_check_encoding($value, 'UTF-8')) {
            return '0x'.bin2hex($value);
        }

        return "N'".str_replace("'", "''", $value)."'";
    }

    /**
     * INSERT lengkap dengan daftar kolom (tidak bergantung urutan kolom tabel).
     *
     * @param  array<string, mixed>  $row
     */
    public static function insert(string $table, array $row): string
    {
        $columns = implode(', ', array_map(self::identifier(...), array_keys($row)));
        $values = implode(', ', array_map(self::literal(...), array_values($row)));

        return 'INSERT INTO '.self::identifier($table)." ({$columns}) VALUES ({$values});";
    }
}
