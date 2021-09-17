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
            $keys = array_keys($this->first());
            $csv->insertOne($keys);

            $this->each(function (array $item) use ($csv) {
                $csv->insertOne($item);
            });

            return $csv->toString();
        };
    }
}
