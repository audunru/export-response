<?php

namespace audunru\ExportResponse\Macros\JsonResponse;

use audunru\ExportResponse\Enums\MimeType;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ToXlsx
{
    public function __invoke()
    {
        return function (string $filename, string $key = null): Response {
            $data = $this->getData(true);
            $data = ! is_null($key) ? Arr::get($data, $key) : $data;
            $collection = Arr::isAssoc($data) ? collect([$data]) : collect($data);

            ob_start();
            SimpleExcelWriter::create('php://output', 'xlsx')
                ->addRows($collection->flattenArrays()->toArray())
                ->close();
            $xlsx = ob_get_contents();
            ob_end_clean();

            return (new Response($xlsx, Response::HTTP_OK, [
                'Content-Type' => MimeType::Xlsx(),
            ]))->filename($filename);
        };
    }
}
