# export-response

Converts Laravel JSON API responses to downloadable CSV, XLSX, or XML files by intercepting `JsonResponse` via middleware or calling macros directly on collections/responses.

## Public API

**Middleware** (attach to routes; trigger when the `Accept` header matches the format):
- `ExportCsv` — converts `JsonResponse` to CSV download
- `ExportXlsx` — converts `JsonResponse` to XLSX download
- `ExportXml` — converts `JsonResponse` to XML download

All three middleware use `timacdonald/has-parameters`, so named parameters are passed via `::with(['key' => 'data'])` rather than positional string args. The optional `key` parameter extracts a nested key from the JSON payload before converting (useful for paginated/wrapped responses).

**Macros registered on Laravel classes:**
- `Collection` / `LazyCollection`: `->flattenArrays()`, `->toCsv($filename)`, `->toXlsx($filename)`
- `JsonResponse`: `->toCsv($filename, $key = null)`, `->toXlsx($filename, $key = null)`, `->toXml($filename, $key = null)`
- `Request`: `->wants(MimeType $mimeType): bool`
- `Response` / `StreamedResponse`: `->contentType(MimeType)`, `->filename(string)`

**`MimeType` enum** (`src/Enums/MimeType.php`): `Csv`, `Xlsx`, `Xml` — used with `$request->wants()`.

**`FilenameGeneratorContract`** (`src/Contracts/FilenameGeneratorContract.php`): implement and bind in config to customise generated download filenames. The default (`src/Services/FilenameGenerator.php`) derives the filename from the route name + Accept-header extension.

## Service Provider

- Config key: `export-response` (publish with `--tag=export-response-config`)
- Registers all macros from `config/export-response.php` at boot; each macro class and the `filename-generator` binding are swappable via config
- Implements `DeferrableProvider`; only `FilenameGeneratorContract` is in `provides()`

## Dependencies

- Internal: none
- External: `timacdonald/has-parameters` (named middleware params), `spatie/simple-excel` (CSV/XLSX streaming, dev dependency only — consumers must require it themselves if needed), `spatie/array-to-xml` (XML, also dev-only)

## Testing

Run from this directory: `composer test`

Feature tests cover full HTTP round-trips with middleware via routes defined in `tests/TestCase.php`. Unit tests cover individual macros and the filename generator. `getLazyResponse()` in `TestCase` demonstrates `LazyCollection` usage with 1 000 rows.

## Conventions / Gotchas

- `spatie/simple-excel` and `spatie/array-to-xml` are **dev dependencies** here — applications consuming this package must add them to their own `composer.json` if they need XLSX/CSV or XML output respectively.
- `flattenArrays()` is a prerequisite step baked into `toCsv`/`toXlsx`; it dot-notation-flattens nested arrays and normalises missing keys to `null` so all rows share the same column set.
- The `StreamedResponse` (`src/Response/StreamedResponse.php`) is a thin `Macroable` wrapper around Symfony's `StreamedResponse`; response macros are registered on both it and `Illuminate\Http\Response`.
- Wrapped/paginated JSON (e.g. `{ "data": [...], "meta": {...} }`) requires passing `key: 'data'` to middleware or the `->toCsv($filename, 'data')` macro overload; without it the entire payload including `meta` is treated as rows.
