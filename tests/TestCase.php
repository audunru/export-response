<?php

namespace audunru\ExportResponse\Tests;

use audunru\ExportResponse\ExportServiceProvider;
use audunru\ExportResponse\Middleware\ExportCsv;
use Illuminate\Http\JsonResponse;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @SuppressWarnings("unused")
     */
    protected function getPackageProviders($app)
    {
        return [ExportServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', 'true' === env('APP_DEBUG'));
        $app['config']->set('app.key', substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 5)), 0, 32));
        $app->register(ExportServiceProvider::class);
    }

    protected function defineRoutes($router)
    {
        $router->get('/documents', function () {
            $jsonResponse = new JsonResponse(['data' => [
                ['id' => 1, 'name' => 'Navn', 'data' => ['foo' => 'bar'], 'meta' => []],
                ['id' => 2, 'name' => 'Noe annet', 'data' => ['foo' => 'bar', 'bar' => 'foo']],
            ],
            ]);

            return $jsonResponse;
        })->middleware(ExportCsv::class)->name('documents');
    }

    /**
     * @SuppressWarnings("unused")
     */
    protected function getPackageAliases($app)
    {
        return [];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
