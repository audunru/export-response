<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Macros\JsonResponse\ToXml;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class JsonResponseToXmlTest extends TestCase
{
    public function testItGeneratesXmlFromUnwrappedResponse()
    {
        Collection::macro('toXml', app(ToXml::class)());
        JsonResponse::macro('JsonResponseToXml', app(ToXml::class)());

        $response = $this->getUnwrappedResponse()->toXml('filename.xml');

        $this->assertEquals("attachment; filename=\"filename.xml\"; filename*=utf-8''filename.xml", $response->headers->get('Content-Disposition'));
        $this->assertEquals('<?xml version="1.0"?>
<root><data><id>1</id><name>Navn</name><data><foo>bar</foo></data><meta/></data><data><id>2</id><name>Noe annet</name><data><foo>bar</foo><bar>foo</bar></data></data></root>
', $response->getContent());
    }

    public function testItGeneratesXmlFromWrappedResponse()
    {
        Collection::macro('toXml', app(ToXml::class)());
        JsonResponse::macro('JsonResponseToXml', app(ToXml::class)());

        $response = $this->getWrappedResponse()->toXml('filename.xml', 'data');

        $this->assertEquals("attachment; filename=\"filename.xml\"; filename*=utf-8''filename.xml", $response->headers->get('Content-Disposition'));
        $this->assertEquals('<?xml version="1.0"?>
<root><data><id>1</id><name>Navn</name><data><foo>bar</foo></data><meta/></data><data><id>2</id><name>Noe annet</name><data><foo>bar</foo><bar>foo</bar></data></data></root>
', $response->getContent());
    }
}
