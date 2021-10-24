<?php

namespace audunru\ExportResponse\Tests\Feature;

use audunru\ExportResponse\Tests\TestCase;

class CsvTest extends TestCase
{
    public function testItGetsCsvFromUnwrappedResponse()
    {
        $response = $this->get('/unwrapped', ['Accept' => 'text/csv']);

        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $this->assertEquals("attachment; filename=\"documents.csv\"; filename*=utf-8''documents.csv", $response->headers->get('Content-Disposition'));
        $this->assertEquals('id,name,data.foo,meta,data.bar
1,Navn,bar,,
2,"Noe annet",bar,,foo
', $response->getContent());
    }

    public function testItGetsCsvFromWrappedResponse()
    {
        $response = $this->get('/wrapped', ['Accept' => 'text/csv']);

        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $this->assertEquals("attachment; filename=\"documents.csv\"; filename*=utf-8''documents.csv", $response->headers->get('Content-Disposition'));
        $this->assertEquals('id,name,data.foo,meta,data.bar
1,Navn,bar,,
2,"Noe annet",bar,,foo
', $response->getContent());
    }
}
