<?php

namespace audunru\ExportResponse\Macros\Request;

use audunru\ExportResponse\Enums\MimeType;

class Wants
{
    public function __invoke()
    {
        return function (MimeType $mimeType): bool {
            return $mimeType->value === $this->header('Accept');
        };
    }
}
