<?php

namespace audunru\ExportResponse\Macros\Collection;

use audunru\ExportResponse\Enums\MimeType;
use audunru\ExportResponse\Response\StreamedResponse;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ToCsv
{
    public function __invoke()
    {
        return function (string $filename): StreamedResponse {
            return (new StreamedResponse(function () {
                SimpleExcelWriter::createWithoutBom('php://output', 'csv')
                    ->addRows($this->flattenArrays())
                    ->close();
            }))->contentType(MimeType::Csv())->filename($filename);
        };
    }
}
