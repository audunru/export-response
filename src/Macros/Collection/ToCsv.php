<?php

namespace audunru\ExportResponse\Macros\Collection;

use League\Csv\Writer;
use SplTempFileObject;

class ToCsv
{
    public function __invoke()
    {
        return function (): string {
            $csv = Writer::createFromFileObject(new SplTempFileObject());
            $header = array_keys($this->first());
            $csv->insertOne($header);

            $this->each(function (array $row) use ($csv) {
                $csv->insertOne($row);
            });

            return $csv->toString();
        };
    }
}
