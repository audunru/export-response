<?php

namespace audunru\ExportResponse\Tests\Feature;

use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Spatie\SimpleExcel\SimpleExcelReader;

class XlsxTest extends TestCase
{
    public function testItGetsXlsxFromUnwrappedResponse()
    {
        $response = $this->get('/unwrapped', ['Accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->assertEquals("attachment; filename=\"documents.xlsx\"; filename*=utf-8''documents.xlsx", $response->headers->get('Content-Disposition'));

        $reader = $this->getExcelReader($response->streamedContent());
        $headers = $reader->getHeaders();
        $rows = $reader->getRows()->toArray();

        $this->assertEquals(['id', 'name', 'data.foo', 'meta', 'data.bar'], $headers);
        $this->assertEquals(2, count($rows));
        $this->assertEquals([
            'id'       => 1,
            'name'     => 'Navn',
            'data.foo' => 'bar',
            'meta'     => '',
            'data.bar' => '',
        ], $rows[0]);
        $this->assertEquals([
            'id'       => 2,
            'name'     => 'Noe annet',
            'data.foo' => 'bar',
            'meta'     => '',
            'data.bar' => 'foo',
        ], $rows[1]);
    }

    public function testItGetsXlsxFromWrappedResponse()
    {
        $response = $this->get('/wrapped', ['Accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->assertEquals("attachment; filename=\"documents.xlsx\"; filename*=utf-8''documents.xlsx", $response->headers->get('Content-Disposition'));

        $reader = $this->getExcelReader($response->streamedContent());
        $headers = $reader->getHeaders();
        $rows = $reader->getRows()->toArray();

        $this->assertEquals(['id', 'name', 'data.foo', 'meta', 'data.bar'], $headers);
        $this->assertEquals(2, count($rows));
        $this->assertEquals([
            'id'       => 1,
            'name'     => 'Navn',
            'data.foo' => 'bar',
            'meta'     => '',
            'data.bar' => '',
        ], $rows[0]);
        $this->assertEquals([
            'id'       => 2,
            'name'     => 'Noe annet',
            'data.foo' => 'bar',
            'meta'     => '',
            'data.bar' => 'foo',
        ], $rows[1]);
    }

    private function getExcelReader(string $content): SimpleExcelReader
    {
        $filename = tempnam(sys_get_temp_dir(), '');
        File::put($filename, $content);

        return SimpleExcelReader::create($filename, 'xlsx');
    }
}
