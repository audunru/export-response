<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Macros\Collection\FlattenArrays;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;

class JsonResponseToXlsxTest extends TestCase
{
    public function testItGeneratesCsvFromUnwrappedResponse()
    {
        Collection::macro('flattenArrays', app(FlattenArrays::class)());

        $response = new TestResponse($this->getUnwrappedResponse()->toXlsx('filename.xlsx'));

        $this->assertEquals("attachment; filename=\"filename.xlsx\"; filename*=utf-8''filename.xlsx", $response->headers->get('Content-Disposition'));

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

    public function testItGeneratesCsvFromWrappedResponse()
    {
        Collection::macro('flattenArrays', app(FlattenArrays::class)());

        $response = new TestResponse($this->getWrappedResponse()->toXlsx('filename.xlsx', 'data'));

        $this->assertEquals("attachment; filename=\"filename.xlsx\"; filename*=utf-8''filename.xlsx", $response->headers->get('Content-Disposition'));

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
}
