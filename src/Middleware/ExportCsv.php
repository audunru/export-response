<?php

namespace audunru\ExportResponse\Middleware;

use audunru\ExportResponse\Contracts\FilenameGeneratorContract;
use audunru\ExportResponse\Enums\MimeType;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExportCsv
{
    public function __construct(protected FilenameGeneratorContract $filenameGenerator)
    {
    }

    public function handle(Request $request, Closure $next, string $key = 'data')
    {
        $response = $next($request);

        if ($request->wants(MimeType::Csv()) && $response instanceof JsonResponse) {
            $filename = $this->filenameGenerator->get($request, $response);

            return $response->toCsv($filename, $key);
        }

        return $response;
    }
}
