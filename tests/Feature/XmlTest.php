<?php

namespace audunru\ExportResponse\Tests\Feature;

use audunru\ExportResponse\Tests\TestCase;

class XmlTest extends TestCase
{
    public function testItGetsXmlFromWrappedResponse()
    {
        $response = $this->get('/wrapped', ['Accept' => 'application/xml']);

        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/xml');

        $this->assertEquals("attachment; filename=\"documents.xml\"; filename*=utf-8''documents.xml", $response->headers->get('Content-Disposition'));
        $this->assertEquals('<?xml version="1.0"?>
<root><data><id>1</id><name>Navn</name><data><foo>bar</foo></data><meta/></data><data><id>2</id><name>Noe annet</name><data><foo>bar</foo><bar>foo</bar></data></data></root>
', $response->getContent());
    }
}
