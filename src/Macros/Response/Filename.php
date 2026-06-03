<?php

namespace audunru\ExportResponse\Macros\Response;

use audunru\ExportResponse\Response\StreamedResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\HeaderUtils;

class Filename
{
    public function __invoke()
    {
        return function (string $filename, ?string $filenameStar = null): Response|StreamedResponse {
            $name = $filenameStar ?? $filename;
            $fallback = $filenameStar !== null ? $filename : Str::ascii($filename);

            $this->headers->set('Content-Disposition', HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $name,
                $fallback
            ));

            return $this;
        };
    }
}
