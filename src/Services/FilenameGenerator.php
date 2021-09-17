<?php

namespace audunru\ExportResponse\Services;

use audunru\ExportResponse\Contracts\FilenameGeneratorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Testing\MimeType;

class FilenameGenerator implements FilenameGeneratorContract
{
    public function get(Request $request, JsonResponse $response): string
    {
        $name = $request->route()->getName();
        $mimeType = $request->header('Accept');

        $extension = MimeType::search($mimeType);

        return sprintf('%s.%s', $name, $extension);
    }
}
