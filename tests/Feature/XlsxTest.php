<?php

namespace audunru\ExportResponse\Tests\Feature;

use audunru\ExportResponse\Tests\TestCase;

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

    public function testItGetsXlsxFromLazyCollection()
    {
        $response = $this->get('/lazyxlsx', ['Accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->assertEquals("attachment; filename=\"lazy.xlsx\"; filename*=utf-8''lazy.xlsx", $response->headers->get('Content-Disposition'));
        $reader = $this->getExcelReader($response->streamedContent());
        $headers = $reader->getHeaders();
        $rows = $reader->getRows()->toArray();

        $this->assertEquals(['id', 'name', 'data.foo', 'meta'], $headers);
        $this->assertEquals(1000, count($rows));
        $this->assertEquals([
            'id'       => 1,
            'name'     => 'Navn',
            'data.foo' => 'bar',
            'meta'     => '',
        ], $rows[0]);
        $this->assertEquals([
            'id'       => 1000,
            'name'     => 'Navn',
            'data.foo' => 'bar',
            'meta'     => '',
        ], $rows[999]);
    }
}
