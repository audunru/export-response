<?php

namespace audunru\ExportResponse\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface FilenameGeneratorContract
{
    public function get(Request $request, JsonResponse $response): string;
}
