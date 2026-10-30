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
                // Prefix strings that a spreadsheet program can read as a formula with "'" to prevent CSV injection
                $escape = fn (mixed $value): mixed => is_string($value) && ! is_numeric($value) && preg_match('/^[=+\-@\t\r]/', $value)
                    ? "'".$value
                    : $value;
                $toRow = fn (array $values): array => array_map($escape, array_values($values));

                $writer = SimpleExcelWriter::createWithoutBom('php://output', 'csv')->noHeaderRow();
                $isFirstRow = true;

                foreach ($this->flattenArrays() as $row) {
                    if ($isFirstRow) {
                        $writer->addRow($toRow(array_keys($row)));
                        $isFirstRow = false;
                    }
                    $writer->addRow($toRow($row));
                }

                $writer->close();
            }))->contentType(MimeType::Csv)->filename($filename);
        };
    }
}
