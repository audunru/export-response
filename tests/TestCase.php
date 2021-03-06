<?php

namespace audunru\ExportResponse\Tests;

use audunru\ExportResponse\ExportServiceProvider;
use audunru\ExportResponse\Middleware\ExportCsv;
use audunru\ExportResponse\Middleware\ExportXlsx;
use audunru\ExportResponse\Middleware\ExportXml;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\SimpleExcel\SimpleExcelReader;

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
        $router->get('/unwrapped', function () {
            return $this->getUnwrappedResponse();
        })->middleware([
            ExportCsv::class,
            ExportXlsx::class,
            ExportXml::class,
        ])->name('documents');

        $router->get('/wrapped', function () {
            return $this->getWrappedResponse();
        })->middleware([
            ExportCsv::with([
                'key' => 'data',
            ]),
            ExportXlsx::with([
                'key' => 'data',
            ]),
            ExportXml::class,
        ])->name('documents');

        $router->get('/lazycsv', function () {
            return $this->getLazyResponse()->toCsv('lazy.csv');
        });

        $router->get('/lazyxlsx', function () {
            return $this->getLazyResponse()->toXlsx('lazy.xlsx');
        });
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

    protected function getUnwrappedResponse(): JsonResponse
    {
        return new JsonResponse([
            [
                'id'   => 1,
                'name' => 'Navn',
                'data' => [
                    'foo' => 'bar',
                ],
                'meta' => [],
            ],
            [
                'id'   => 2,
                'name' => 'Noe annet',
                'data' => [
                    'foo' => 'bar',
                    'bar' => 'foo',
                ],
            ],
        ]);
    }

    protected function getWrappedResponse(): JsonResponse
    {
        return new JsonResponse([
            'data' => [
                [
                    'id'   => 1,
                    'name' => 'Navn',
                    'data' => [
                        'foo' => 'bar',
                    ],
                    'meta' => [],
                ],
                [
                    'id'   => 2,
                    'name' => 'Noe annet',
                    'data' => [
                        'foo' => 'bar',
                        'bar' => 'foo',
                    ],
                ],
            ],
            'meta' => [
                'page' => 1,
            ],
        ]);
    }

    protected function getLazyResponse(): LazyCollection
    {
        return LazyCollection::make(function () {
            for ($id = 1; $id <= 1000; $id++) {
                yield ['id' => $id, 'name' => 'Navn', 'data' => ['foo' => 'bar'], 'meta' => []];
            }
        });
    }

    protected function getExcelReader(string $content): SimpleExcelReader
    {
        $filename = tempnam(sys_get_temp_dir(), '');
        File::put($filename, $content);

        return SimpleExcelReader::create($filename, 'xlsx');
    }
}
