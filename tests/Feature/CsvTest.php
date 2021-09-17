<?php

namespace audunru\ExportResponse\Tests\Feature;

use audunru\ExportResponse\Tests\TestCase;

class CsvTest extends TestCase
{
    public function testItGetsCsv()
    {
        $response = $this->get('/documents', ['Accept' => 'text/csv']);

        $response
            ->assertStatus(200);

        $this->assertEquals("attachment; filename=\"documents.csv\"; filename*=utf-8''documents.csv", $response->headers->get('Content-Disposition'));
        $this->assertEquals('id,name,data.foo,meta,data.bar
1,Navn,bar,,
2,"Noe annet",bar,,foo
', $response->getContent());
    }

    public function testItGetsJson()
    {
        $response = $this->get('/documents', ['Accept' => '	application/json']);

        $response
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'id'   => 1,
                    'name' => 'Navn',
                    'data' => [
                        'foo' => 'bar',
                    ],
                    'meta' => [],
                ],
                [
                    'id'   => 2,
                    'name' => 'Noe annet',
                    'data' => [
                        'foo' => 'bar',
                        'bar' => 'foo',
                    ],
                ],
            ]]);
    }
}
