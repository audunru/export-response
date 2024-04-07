<?php

namespace audunru\ExportResponse\Macros\Response;

use audunru\ExportResponse\Response\StreamedResponse;
use Illuminate\Http\Response;

class Filename
{
    public function __invoke()
    {
        return function (string $filename, ?string $filenameStar = null): Response|StreamedResponse {
            $this->headers->set('Content-Disposition', sprintf('attachment; filename="%s"; filename*=utf-8\'\'%s', $filename, urlencode($filenameStar ?? $filename)));

            return $this;
        };
    }
}
