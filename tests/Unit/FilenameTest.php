<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Macros\Response\Filename;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Http\Response;

class FilenameTest extends TestCase
{
    public function testItAddsContentDispositionHeader()
    {
        Response::macro('filename', app(Filename::class)());

        $response = new Response();
        $response->filename('filename.csv');

        $this->assertEquals("attachment; filename=\"filename.csv\"; filename*=utf-8''filename.csv", $response->headers->get('Content-Disposition'));
    }

    public function testItAddsContentDispositionHeaderWithNonAsciiCharacters()
    {
        Response::macro('filename', app(Filename::class)());

        $response = new Response();
        $response->filename('AEOEAA.csv', 'ÆØÅ.csv');

        $this->assertEquals("attachment; filename=\"AEOEAA.csv\"; filename*=utf-8''%C3%86%C3%98%C3%85.csv", $response->headers->get('Content-Disposition'));
    }
}
