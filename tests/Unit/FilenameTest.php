<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Macros\Response\Filename;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Http\Response;

class FilenameTest extends TestCase
{
    public function test_it_adds_content_disposition_header()
    {
        Response::macro('filename', app(Filename::class)());

        $response = new Response;
        $response->filename('filename.csv');

        $this->assertEquals('attachment; filename=filename.csv', $response->headers->get('Content-Disposition'));
    }

    public function test_it_adds_content_disposition_header_with_non_ascii_characters()
    {
        Response::macro('filename', app(Filename::class)());

        $response = new Response;
        $response->filename('AEOEAA.csv', 'ÆØÅ.csv');

        $this->assertEquals("attachment; filename=AEOEAA.csv; filename*=utf-8''%C3%86%C3%98%C3%85.csv", $response->headers->get('Content-Disposition'));
    }

    public function test_it_derives_ascii_fallback_for_single_non_ascii_filename()
    {
        Response::macro('filename', app(Filename::class)());

        $response = new Response;
        $response->filename('ÆØÅ.csv');

        $this->assertEquals("attachment; filename=AEOA.csv; filename*=utf-8''%C3%86%C3%98%C3%85.csv", $response->headers->get('Content-Disposition'));
    }

    public function test_it_neutralizes_double_quote_injection_in_filename()
    {
        Response::macro('filename', app(Filename::class)());

        $response = new Response;
        $response->filename('a"b.csv');

        $header = $response->headers->get('Content-Disposition');
        $this->assertEquals('attachment; filename="a\"b.csv"', $header);
        $this->assertStringNotContainsString("\r", $header);
        $this->assertStringNotContainsString("\n", $header);
    }
}
