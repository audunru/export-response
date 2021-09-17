<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Enums\MimeType;
use audunru\ExportResponse\Macros\Request\Wants;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Http\Request;

class WantsTest extends TestCase
{
    public function testItWantsCsv()
    {
        Request::macro('wants', app(Wants::class)());

        $request = new Request();
        $request->headers->set('Accept', 'text/csv');

        $result = $request->wants(MimeType::Csv());

        $this->assertTrue($result);
    }

    public function testItWantsXml()
    {
        Request::macro('wants', app(Wants::class)());

        $request = new Request();
        $request->headers->set('Accept', 'application/xml');

        $result = $request->wants(MimeType::Csv());

        $this->assertFalse($result);
    }
}
