<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Macros\Collection\FlattenArrays;
use audunru\ExportResponse\Macros\Collection\ToCsv;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class JsonResponseToCsvTest extends TestCase
{
    public function testItFlattensArrays()
    {
        Collection::macro('flattenArrays', app(FlattenArrays::class)());
        Collection::macro('toCsv', app(ToCsv::class)());
        JsonResponse::macro('JsonResponseToCsv', app(ToCsv::class)());

        $jsonResponse = new JsonResponse(['data' => [
            ['id' => 1, 'name' => 'Navn', 'data' => ['foo' => 'bar'], 'meta' => []],
            ['id' => 2, 'name' => 'Noe annet', 'data' => ['foo' => 'bar', 'bar' => 'foo']],
        ],
        ]);
        $response = $jsonResponse->toCsv('filename.csv');

        $this->assertEquals("attachment; filename=\"filename.csv\"; filename*=utf-8''filename.csv", $response->headers->get('Content-Disposition'));
        $this->assertEquals('id,name,data.foo,meta,data.bar
1,Navn,bar,,
2,"Noe annet",bar,,foo
', $response->getContent());
    }
}
