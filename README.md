# Export JSON responses from Laravel

[![Build Status](https://github.com/audunru/export-response/actions/workflows/validate.yml/badge.svg)](https://github.com/audunru/export-response/actions/workflows/validate.yml)
[![Coverage Status](https://coveralls.io/repos/github/audunru/export-response/badge.svg?branch=master)](https://coveralls.io/github/audunru/export-response?branch=master)
[![StyleCI](https://github.styleci.io/repos/407671897/shield?branch=master)](https://github.styleci.io/repos/407671897)

Currently supported:

- CSV
- XLSX
- XML

# Installation

## Step 1: Install with Composer

```bash
composer require audunru/export-response
```

Depending on which formats you want to export to, you will have to install additional packages:

| Format | Package                                                       |
| ------ | ------------------------------------------------------------- |
| CSV    | [spatie/simple-excel](https://github.com/spatie/simple-excel) |
| XLSX   | [spatie/simple-excel](https://github.com/spatie/simple-excel) |
| XML    | [spatie/array-to-xml](https://github.com/spatie/array-to-xml) |

## Step 2: Add middleware to your routes

To allow exports for all your API endpoints, add middleware to `Kernel.php`:

```php
'api' => [
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    \audunru\ExportResponse\Middleware\ExportCsv::class,
    \audunru\ExportResponse\Middleware\ExportXlsx::class,
    \audunru\ExportResponse\Middleware\ExportXml::class,
],
```

To add it to one particular API resource, you can use this in `api.php`:

```php
Route::apiResource('documents', DocumentController::class)
    ->middleware([
        ExportCsv::class,
        ExportXlsx::class,
        ExportXml::class
    ])
    ->name('documents');
```

You can specify an array key which will be used to retrieve the data. "Dot" notation is supported.

```php
Route::apiResource('documents', DocumentController::class)
    ->middleware([
        ExportCsv::with([
            'key' => 'data',
        ]),
        ExportXlsx::with([
            'key' => 'data',
        ]),
        ExportXml::class::with([
            'key' => 'data',
        ]),
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

### Exporting from controller

Instead of using middleware, you can perform the export in the controller:

```php
class ProductController extends Controller
{
    public function csv()
    {
        $products = Product::all();

        return $products->toCsv('filename.csv');
    }
```

Lazy collections are also supported:

```php
class ProductController extends Controller
{
    public function csv()
    {
        $products = Product::lazy();

        return $products->toCsv('filename.csv');
    }
```

Please use lazy collections when you can. During testing, using `Product::lazy()` to export 10,000 products took about 2MB of memory, compared to 44 MB of memory using `Product::all()`. Both exports took the same amount of time (around 45 seconds).

## Step 3: Exporting a response

In order to retrieve an API response as CSV instead of JSON, send a request to your API with the `Accept` header set to `text/csv`.

For XML, set the header to `application/xml`.

For XLSX, set the header to `application/vnd.openxmlformats-officedocument.spreadsheetml.sheet`.

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
