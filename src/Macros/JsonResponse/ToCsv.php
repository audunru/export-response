<?php

namespace audunru\ExportResponse\Macros\JsonResponse;

use audunru\ExportResponse\Enums\MimeType;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ToCsv
{
    public function __invoke()
    {
        return function (string $filename, string $key = null): Response {
            $data = $this->getData(true);
            $data = ! is_null($key) ? Arr::get($data, $key) : $data;
            $collection = Arr::isAssoc($data) ? collect([$data]) : collect($data);

            ob_start();
            SimpleExcelWriter::createWithoutBom('php://output', 'csv')
                ->addRows($collection->flattenArrays()->toArray())
                ->close();
            $csv = ob_get_contents();
            ob_end_clean();

            return (new Response($csv, Response::HTTP_OK, [
                'Content-Type' => MimeType::Csv(),
            ]))->filename($filename);
        };
    }
}
