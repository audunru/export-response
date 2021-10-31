<?php

namespace audunru\ExportResponse\Macros\JsonResponse;

use audunru\ExportResponse\Enums\MimeType;
use audunru\ExportResponse\Response\StreamedResponse;
use Illuminate\Support\Arr;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ToCsv
{
    public function __invoke()
    {
        return function (string $filename, string $key = null): StreamedResponse {
            $data = $this->getData(true);
            $data = ! is_null($key) ? Arr::get($data, $key) : $data;
            $collection = Arr::isAssoc($data) ? collect([$data]) : collect($data);

            return ( new StreamedResponse(
                function () use ($collection) {
                    SimpleExcelWriter::createWithoutBom('php://output', 'csv')
                        ->addRows($collection->flattenArrays())
                        ->close();
                },
                StreamedResponse::HTTP_OK,
                [
                    'Content-Type'        => MimeType::Csv(),
                ]
            ))->filename($filename);
        };
    }
}
