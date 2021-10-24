<?php

namespace audunru\ExportResponse\Macros\JsonResponse;

use audunru\ExportResponse\Enums\MimeType;
use Illuminate\Http\Response;
use Spatie\ArrayToXml\ArrayToXml;

class ToXml
{
    public function __invoke()
    {
        return function (string $filename): Response {
            $data = $this->getData(true);
            $xml = ArrayToXml::convert($data);

            return (new Response($xml, Response::HTTP_OK, [
                'Content-Type' => MimeType::Xml(),
            ]))->filename($filename);
        };
    }
}
