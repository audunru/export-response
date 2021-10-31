<?php

namespace audunru\ExportResponse\Macros\JsonResponse;

use audunru\ExportResponse\Enums\MimeType;
use audunru\ExportResponse\Response\StreamedResponse;
use Illuminate\Support\Arr;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ToXlsx
{
    public function __invoke()
    {
        return function (string $filename, string $key = null): StreamedResponse {
            $data = $this->getData(true);
            $data = ! is_null($key) ? Arr::get($data, $key) : $data;
            $collection = Arr::isAssoc($data) ? collect([$data]) : collect($data);

            return ( new StreamedResponse(function () use ($collection) {
                SimpleExcelWriter::create('php://output', 'xlsx')
                    ->addRows($collection->flattenArrays())
                    ->close();
            }))->filename($filename)->contentType(MimeType::Xlsx());
        };
    }
}
