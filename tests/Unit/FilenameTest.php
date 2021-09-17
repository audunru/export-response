<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Macros\Response\Filename;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Http\Response;

class FilenameTest extends TestCase
{
    public function testItWantsCsv()
    {
        Response::macro('filename', app(Filename::class)());

        $response = new Response();
        $response->filename('filename.csv');

        $this->assertEquals("attachment; filename=\"filename.csv\"; filename*=utf-8''filename.csv", $response->headers->get('Content-Disposition'));
    }
}
