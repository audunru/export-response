<?php

namespace audunru\ExportResponse\Macros\Collection;

use Spatie\SimpleExcel\SimpleExcelWriter;

class ToCsv
{
    public function __invoke()
    {
        return function (): string {
            ob_start();
            SimpleExcelWriter::createWithoutBom('php://output', 'csv')
                ->addRows($this->flattenArrays()->toArray())
                ->close();
            $csv = ob_get_contents();
            ob_end_clean();

            return $csv;
        };
    }
}
