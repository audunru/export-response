<?php

use audunru\ExportResponse\Macros\Collection\FlattenArrays;
use audunru\ExportResponse\Macros\Collection\ToCsv;
use audunru\ExportResponse\Macros\Collection\ToXlsx;
use audunru\ExportResponse\Macros\JsonResponse\ToCsv as JsonResponseToCsv;
use audunru\ExportResponse\Macros\JsonResponse\ToXlsx as JsonResponseToXlsx;
use audunru\ExportResponse\Macros\JsonResponse\ToXml as JsonResponseToXml;
use audunru\ExportResponse\Macros\Request\Wants;
use audunru\ExportResponse\Macros\Response\ContentType;
use audunru\ExportResponse\Macros\Response\Filename;
use audunru\ExportResponse\Services\FilenameGenerator;

return [
    /*
     * Class that generates the filenames that will be used when the file is downloaded.
     */
    'filename-generator' => FilenameGenerator::class,

    /*
     * Prefix CSV string values that start with =, +, -, @, TAB or CR with "'", so that
     * spreadsheet programs do not read them as formulas (CSV injection). This changes the data.
     */
    'escape-csv-formulas' => false,

    /*
     * You can replace these macros with your own implementations if necessary.
     */
    'macros' => [
        'collection' => [
            'flattenArrays' => FlattenArrays::class,
            'toCsv' => ToCsv::class,
            'toXlsx' => ToXlsx::class,
        ],
        'json-response' => [
            'toCsv' => JsonResponseToCsv::class,
            'toXlsx' => JsonResponseToXlsx::class,
            'toXml' => JsonResponseToXml::class,
        ],
        'response' => [
            'contentType' => ContentType::class,
            'filename' => Filename::class,
        ],
        'request' => [
            'wants' => Wants::class,
        ],
    ],
];
