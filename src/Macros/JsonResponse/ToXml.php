<?php

namespace audunru\ExportResponse\Macros\JsonResponse;

use audunru\ExportResponse\Enums\MimeType;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Spatie\ArrayToXml\ArrayToXml;

class ToXml
{
    public function __invoke()
    {
        return function (string $filename): Response {
            $data = $this->getData(true);
            $data = Arr::isAssoc($data) ? $data : ['data' => $data];
            $xml = ArrayToXml::convert($data);

            return (new Response($xml, Response::HTTP_OK, [
                'Content-Type' => MimeType::Xml(),
            ]))->filename($filename);
        };
    }
}
