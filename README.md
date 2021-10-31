# Export JSON responses from Laravel

[![Build Status](https://app.travis-ci.com/audunru/export-response.svg?branch=master)](https://app.travis-ci.com/audunru/export-response)
[![Coverage Status](https://coveralls.io/repos/github/audunru/export-response/badge.svg?branch=master)](https://coveralls.io/github/audunru/export-response?branch=master)
[![StyleCI](https://github.styleci.io/repos/407671897/shield?branch=master)](https://github.styleci.io/repos/407671897)

Currently supported:

- CSV
- XML

# Installation

## Step 1: Install with Composer

```bash
composer require audunru/export-response
```

This package depends on [league/csv](https://csv.thephpleague.com/). You will have to install this separately if you want to export to CSV:

```bash
composer require league/csv
```

This package depends on [spatie/array-to-xml](https://github.com/spatie/array-to-xml). You will have to install this separately if you want to export to XML:

```bash
composer require spatie/array-to-xml
```

## Step 2: Add middleware to your routes

To support CSV and XML exports for all your API endpoints, add it to `Kernel.php`:

```php
'api' => [
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    \audunru\ExportResponse\Middleware\ExportCsv::class,
    \audunru\ExportResponse\Middleware\ExportXml::class,
],
```

To add it to one particular API resource, you can use this in `api.php`:

```php
Route::apiResource('documents', DocumentController::class)
    ->middleware([
        ExportCsv::class,
        ExportXml::class
    ])
    ->name('documents');
```

The `ExportCsv` middleware allows you to specify an array key which will be used to retrieve the data. "Dot" notation is supported.

```php
Route::apiResource('documents', DocumentController::class)
    ->middleware([
        ExportCsv::with([
            'key' => 'data',
        ]),
        ExportXml::class
    ])
    ->name('documents');
```

You can also add the middleware to the `$middlewareGroups` and `$routeMiddleware` arrays in `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'api' => [
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // Add ExportCsv middleware to all requests. "data" is the name of
        // the key to retrieve using "dot" notation.
        'csv:data',
    ],
];

protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    'csv' => \audunru\ExportResponse\Middleware\ExportCsv::class,
];
```

## Step 3: Exporting a response

In order to retrieve an API response as CSV instead of JSON, send a request to your API with the `Accept` header set to `text/csv`.

For XML, set the header to `application/xml`.

# Configuration

Publish the configuration file by running:

```php
php artisan vendor:publish --tag=export-response-config
```

# Development

## Testing

Run tests:

```bash
composer test
```
