<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Macros\Response\Filename;
use audunru\ExportResponse\Services\FilenameGenerator;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;

class FilenameGeneratorTest extends TestCase
{
    public function testItGeneratesAFilename()
    {
        Response::macro('filename', app(Filename::class)());

        $request = new Request();
        $routeResolver = function () {
            $route = new Route('GET', '/test', ['index']);
            $route->name('documents');

            return $route;
        };
        $request->setRouteResolver($routeResolver);
        $request->headers->set('Accept', 'text/csv');

        $response = new JsonResponse();

        $generator = new FilenameGenerator();

        $filename = $generator->get($request, $response);

        $this->assertEquals('documents.csv', $filename);
    }

    public function testItUsesADefaultNameWhenRouteHasNoName()
    {
        Response::macro('filename', app(Filename::class)());

        $request = new Request();
        $routeResolver = function () {
            $route = new Route('GET', '/test', ['index']);

            return $route;
        };
        $request->setRouteResolver($routeResolver);
        $request->headers->set('Accept', 'text/csv');

        $response = new JsonResponse();

        $generator = new FilenameGenerator();

        $filename = $generator->get($request, $response);

        $this->assertEquals('export.csv', $filename);
    }

    public function testItUsesADefaultNameWhenRouteHasNoNameAndExtensionCannotBeFound()
    {
        Response::macro('filename', app(Filename::class)());

        $request = new Request();
        $routeResolver = function () {
            $route = new Route('GET', '/test', ['index']);

            return $route;
        };
        $request->setRouteResolver($routeResolver);

        $response = new JsonResponse();

        $generator = new FilenameGenerator();

        $filename = $generator->get($request, $response);

        $this->assertEquals('export', $filename);
    }
}
