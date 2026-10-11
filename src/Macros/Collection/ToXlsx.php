<?php

namespace audunru\ExportResponse\Macros\Collection;

use audunru\ExportResponse\Enums\MimeType;
use audunru\ExportResponse\Response\StreamedResponse;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Cell\StringCell;
use OpenSpout\Common\Entity\Row;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ToXlsx
{
    public function __invoke()
    {
        return function (string $filename): StreamedResponse {
            return (new StreamedResponse(function () {
                // Write strings as StringCell, because Cell::fromValue() makes strings that start with "=" into formulas
                $toRow = fn (array $values): Row => new Row(array_map(
                    fn (mixed $value): Cell => is_string($value) && $value !== '' ? new StringCell($value, null) : Cell::fromValue($value),
                    array_values($values)
                ));

                $writer = SimpleExcelWriter::create('php://output', 'xlsx')->noHeaderRow();
                $isFirstRow = true;

                foreach ($this->flattenArrays() as $row) {
                    if ($isFirstRow) {
                        $writer->addRow($toRow(array_keys($row)));
                        $isFirstRow = false;
                    }
                    $writer->addRow($toRow($row));
                }

                $writer->close();
            }))->contentType(MimeType::Xlsx)->filename($filename);
        };
    }
}
