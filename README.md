# Export JSON responses from Laravel to CSV

[![Build Status](https://app.travis-ci.com/audunru/export-response.svg?branch=master)](https://app.travis-ci.com/audunru/export-response)
[![Coverage Status](https://coveralls.io/repos/github/audunru/export-response/badge.svg?branch=master)](https://coveralls.io/github/audunru/export-response?branch=master)
[![StyleCI](https://github.styleci.io/repos/407671897/shield?branch=master)](https://github.styleci.io/repos/407671897)

Currently supported:

- JsonResponse to CSV

# Installation

## Step 1: Install with Composer

```bash
composer require audunru/export-response
```

This package depends on [league/csv](https://csv.thephpleague.com/) for the export to CSV. You will have to install this separately. (In the future, this package may support other export formats such as Excel, and not everyone user of this package will need CSV support.)

```bash
composer require league/csv
```

## Step 2: Add middleware to your routes

To support CSV exports for all your API endpoints, add it to `Kernel.php`:

```php
'api' => [
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    \audunru\ExportResponse\Middleware\ExportCsv::class,
],
```

To add it to one particular API resource, you can use this in `api.php`:

```php
Route::apiResource('documents', DocumentController::class)->middleware(ExportCsv::class)->name('documents');
```

In order to retrieve an API response as CSV instead of JSON, send a request to your API with the `Accept` header set to `text/csv`.

# Development

## Testing

Run tests:

```bash
composer test
```
