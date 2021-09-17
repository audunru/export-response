<?php

namespace audunru\ExportResponse\Macros\Response;

use Illuminate\Http\Response;

class Filename
{
    public function __invoke()
    {
        return function (string $filename): Response {
            return $this->header('Content-Disposition', sprintf('attachment; filename="%s"; filename*=utf-8\'\'%s', $filename, urlencode($filename)));
        };
    }
}
