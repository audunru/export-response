<?php

namespace audunru\ExportResponse;

use audunru\ExportResponse\Contracts\FilenameGeneratorContract;
use audunru\ExportResponse\Response\StreamedResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ExportServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('export-response')
            ->hasConfigFile();
    }

    /**
     * Register any package services.
     */
    public function packageRegistered()
    {
        Collection::make(config('export-response.macros.collection'))
            ->reject(fn ($class, $macro) => Collection::hasMacro($macro))
            ->each(fn ($class, $macro) => Collection::macro($macro, app($class)()));
        Collection::make(config('export-response.macros.collection'))
            ->reject(fn ($class, $macro) => LazyCollection::hasMacro($macro))
            ->each(fn ($class, $macro) => LazyCollection::macro($macro, app($class)()));
        Collection::make(config('export-response.macros.request'))
            ->reject(fn ($class, $macro) => Request::hasMacro($macro))
            ->each(fn ($class, $macro) => Request::macro($macro, app($class)()));
        Collection::make(config('export-response.macros.response'))
            ->reject(fn ($class, $macro) => Response::hasMacro($macro))
            ->each(fn ($class, $macro) => Response::macro($macro, app($class)()));
        Collection::make(config('export-response.macros.response'))
            ->reject(fn ($class, $macro) => StreamedResponse::hasMacro($macro))
            ->each(fn ($class, $macro) => StreamedResponse::macro($macro, app($class)()));
        Collection::make(config('export-response.macros.json-response'))
            ->reject(fn ($class, $macro) => JsonResponse::hasMacro($macro))
            ->each(fn ($class, $macro) => JsonResponse::macro($macro, app($class)()));

        $this->app->bind(
            FilenameGeneratorContract::class,
            config('export-response.filename-generator')
        );
    }
}
