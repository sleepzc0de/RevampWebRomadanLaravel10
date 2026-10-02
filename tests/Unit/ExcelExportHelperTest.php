<?php

namespace Tests\Unit;

use App\Helpers\ExcelExportHelper;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class ExcelExportHelperTest extends TestCase
{
    private function exportAndReload(array $headers, array $rows)
    {
        $response = ExcelExportHelper::stream('uji.xlsx', $headers, $rows);

        ob_start();
        $response->sendContent();
        $binary = ob_get_clean();

        $path = tempnam(sys_get_temp_dir(), 'xlsx');
        file_put_contents($path, $binary);

        try {
            return IOFactory::load($path)->getActiveSheet();
        } finally {
            @unlink($path);
        }
    }

    public function test_strings_starting_with_equals_are_stored_as_text_not_formulas(): void
    {
        $payload = '=HYPERLINK("http://evil.example","klik")';

        $sheet = $this->exportAndReload(['Referrer'], [[$payload]]);

        $this->assertSame(DataType::TYPE_STRING, $sheet->getCell('A2')->getDataType());
        $this->assertSame($payload, $sheet->getCell('A2')->getValue());
    }

    public function test_numbers_and_nulls_are_preserved(): void
    {
        $sheet = $this->exportAndReload(['Judul', 'Views', 'Catatan'], [['Berita A', 42, null]]);

        $this->assertSame('Berita A', $sheet->getCell('A2')->getValue());
        $this->assertSame(42, $sheet->getCell('B2')->getValue());
        $this->assertNull($sheet->getCell('C2')->getValue());
    }
}
