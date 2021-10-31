<?php

namespace audunru\ExportResponse\Macros\Collection;

use audunru\ExportResponse\Enums\MimeType;
use audunru\ExportResponse\Response\StreamedResponse;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ToXlsx
{
    public function __invoke()
    {
        return function (string $filename): StreamedResponse {
            return (new StreamedResponse(function () {
                SimpleExcelWriter::create('php://output', 'xlsx')
                    ->addRows($this->flattenArrays())
                    ->close();
            }))->contentType(MimeType::Xlsx())->filename($filename);
        };
    }
}
