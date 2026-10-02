<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportHelper
{
    /**
     * @param  array<int, string>  $headers
     * @param  iterable<int, array<int, mixed>>  $rows
     */
    public static function stream(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        self::writeRow($sheet, 1, $headers);
        $lastColumn = $sheet->getHighestColumn();
        $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true);

        $rowIndex = 2;
        foreach ($rows as $row) {
            self::writeRow($sheet, $rowIndex, $row);
            $rowIndex++;
        }

        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Tulis satu baris dengan semua nilai string disimpan sebagai TEKS.
     *
     * Sheet::fromArray() memperlakukan string berawalan "=" sebagai formula,
     * padahal sebagian kolom ekspor berisi input pihak luar (mis. header
     * Referer pengunjung anonim). Tanpa ini, isi seperti =HYPERLINK(...) akan
     * dieksekusi saat admin membuka file di Excel (formula/CSV injection).
     *
     * @param  array<int, mixed>  $row
     */
    private static function writeRow(Worksheet $sheet, int $rowIndex, array $row): void
    {
        $columnIndex = 1;

        foreach ($row as $value) {
            $coordinate = Coordinate::stringFromColumnIndex($columnIndex++).$rowIndex;

            if (is_string($value)) {
                $sheet->setCellValueExplicit($coordinate, $value, DataType::TYPE_STRING);
            } else {
                $sheet->setCellValue($coordinate, $value);
            }
        }
    }
}
