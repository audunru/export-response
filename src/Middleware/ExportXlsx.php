<?php

namespace audunru\ExportResponse\Middleware;

use audunru\ExportResponse\Contracts\FilenameGeneratorContract;
use audunru\ExportResponse\Enums\MimeType;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TiMacDonald\Middleware\HasParameters;

class ExportXlsx
{
    use HasParameters;

    public function __construct(protected FilenameGeneratorContract $filenameGenerator)
    {
    }

    public function handle(Request $request, Closure $next, string $key = null)
    {
        $response = $next($request);

        if ($request->wants(MimeType::Xlsx()) && $response instanceof JsonResponse) {
            $filename = $this->filenameGenerator->get($request, $response);

            return $response->toXlsx($filename, $key);
        }

        return $response;
    }
}
