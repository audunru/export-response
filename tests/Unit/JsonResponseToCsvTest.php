<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Macros\Collection\FlattenArrays;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;

class JsonResponseToCsvTest extends TestCase
{
    public function testItGeneratesCsvFromUnwrappedResponse()
    {
        Collection::macro('flattenArrays', app(FlattenArrays::class)());

        $response = new TestResponse($this->getUnwrappedResponse()->toCsv('filename.csv'));

        $this->assertEquals("attachment; filename=\"filename.csv\"; filename*=utf-8''filename.csv", $response->headers->get('Content-Disposition'));
        $this->assertEquals('id,name,data.foo,meta,data.bar
1,Navn,bar,,
2,"Noe annet",bar,,foo
', $response->streamedContent());
    }

    public function testItGeneratesCsvFromWrappedResponse()
    {
        Collection::macro('flattenArrays', app(FlattenArrays::class)());

        $response = new TestResponse($this->getWrappedResponse()->toCsv('filename.csv', 'data'));

        $this->assertEquals("attachment; filename=\"filename.csv\"; filename*=utf-8''filename.csv", $response->headers->get('Content-Disposition'));
        $this->assertEquals('id,name,data.foo,meta,data.bar
1,Navn,bar,,
2,"Noe annet",bar,,foo
', $response->streamedContent());
    }
}
