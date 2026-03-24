<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Enums\MimeType;
use audunru\ExportResponse\Macros\Response\ContentType;
use audunru\ExportResponse\Response\StreamedResponse;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Http\Response;

class ContentTypeTest extends TestCase
{
    public function test_it_adds_content_type_header_to_response()
    {
        Response::macro('contentType', app(ContentType::class)());

        $response = new Response;
        $response->contentType(MimeType::Xml);

        $this->assertEquals('application/xml', $response->headers->get('Content-Type'));
    }

    public function test_it_adds_content_type_header_to_streamed_response()
    {
        StreamedResponse::macro('contentType', app(ContentType::class)());

        $response = new StreamedResponse;
        $response->contentType(MimeType::Csv);

        $this->assertEquals('text/csv', $response->headers->get('Content-Type'));
    }
}
