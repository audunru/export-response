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
    public function test_it_generates_a_filename()
    {
        Response::macro('filename', app(Filename::class)());

        $request = new Request;
        $routeResolver = function () {
            $route = new Route('GET', '/test', ['index']);
            $route->name('documents');

            return $route;
        };
        $request->setRouteResolver($routeResolver);
        $request->headers->set('Accept', 'text/csv');

        $response = new JsonResponse;

        $generator = new FilenameGenerator;

        $filename = $generator->get($request, $response);

        $this->assertEquals('documents.csv', $filename);
    }

    public function test_it_uses_a_default_name_when_route_has_no_name()
    {
        Response::macro('filename', app(Filename::class)());

        $request = new Request;
        $routeResolver = function () {
            $route = new Route('GET', '/test', ['index']);

            return $route;
        };
        $request->setRouteResolver($routeResolver);
        $request->headers->set('Accept', 'text/csv');

        $response = new JsonResponse;

        $generator = new FilenameGenerator;

        $filename = $generator->get($request, $response);

        $this->assertEquals('export.csv', $filename);
    }

    public function test_it_uses_a_default_name_when_route_has_no_name_and_extension_cannot_be_found()
    {
        Response::macro('filename', app(Filename::class)());

        $request = new Request;
        $routeResolver = function () {
            $route = new Route('GET', '/test', ['index']);

            return $route;
        };
        $request->setRouteResolver($routeResolver);

        $response = new JsonResponse;

        $generator = new FilenameGenerator;

        $filename = $generator->get($request, $response);

        $this->assertEquals('export', $filename);
    }
}
