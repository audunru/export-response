<?php

namespace audunru\ExportResponse\Macros\Response;

use audunru\ExportResponse\Enums\MimeType;
use audunru\ExportResponse\Response\StreamedResponse;
use Illuminate\Http\Response;

class ContentType
{
    public function __invoke()
    {
        return function (MimeType $mimeType): Response|StreamedResponse {
            $this->headers->set('Content-Type', $mimeType);

            return $this;
        };
    }
}
