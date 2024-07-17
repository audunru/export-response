<?php

namespace audunru\ExportResponse\Tests\Feature;

use audunru\ExportResponse\Tests\TestCase;

class CsvTest extends TestCase
{
    public function testItGetsCsvFromUnwrappedResponse()
    {
        $response = $this->get('/unwrapped', ['Accept' => 'text/csv']);

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $this->assertEquals("attachment; filename=\"documents.csv\"; filename*=utf-8''documents.csv", $response->headers->get('Content-Disposition'));
        $this->assertEquals('id,name,data.foo,meta,data.bar
1,Navn,bar,,
2,"Noe annet",bar,,foo
', $response->streamedContent());
    }

    public function testItGetsCsvFromWrappedResponse()
    {
        $response = $this->get('/wrapped', ['Accept' => 'text/csv']);

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $this->assertEquals("attachment; filename=\"documents.csv\"; filename*=utf-8''documents.csv", $response->headers->get('Content-Disposition'));
        $this->assertEquals('id,name,data.foo,meta,data.bar
1,Navn,bar,,
2,"Noe annet",bar,,foo
', $response->streamedContent());
    }

    public function testItGetsCsvFromLazyCollection()
    {
        $response = $this->get('/lazycsv', ['Accept' => 'text/csv']);

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $this->assertEquals("attachment; filename=\"lazy.csv\"; filename*=utf-8''lazy.csv", $response->headers->get('Content-Disposition'));
        $content = array_filter(explode("\n", $response->streamedContent()));
        $this->assertEquals(1001, count($content));
        $this->assertEquals('id,name,data.foo,meta', $content[0]);
        $this->assertEquals('1,Navn,bar,', $content[1]);
    }
}
