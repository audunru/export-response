<?php

use audunru\ExportResponse\Macros\Collection\FlattenArrays;
use audunru\ExportResponse\Macros\Collection\ToCsv;
use audunru\ExportResponse\Macros\JsonResponse\ToCsv as JsonResponseToCsv;
use audunru\ExportResponse\Macros\JsonResponse\ToXlsx as JsonResponseToXlsx;
use audunru\ExportResponse\Macros\JsonResponse\ToXml as JsonResponseToXml;
use audunru\ExportResponse\Macros\Request\Wants;
use audunru\ExportResponse\Macros\Response\Filename;
use audunru\ExportResponse\Services\FilenameGenerator;

return [
    /*
     * Class that generates the filenames that will be used when the file is downloaded.
     */
    'filename-generator' => FilenameGenerator::class,

    /*
     * You can replace these macros with your own implementations if necessary.
     */
    'macros' => [
        'collection' => [
            'flattenArrays' => FlattenArrays::class,
            'toCsv'         => ToCsv::class,
        ],
        'json-response' => [
            'toCsv'  => JsonResponseToCsv::class,
            'toXlsx' => JsonResponseToXlsx::class,
            'toXml'  => JsonResponseToXml::class,
        ],
        'response' => [
            'filename' => Filename::class,
        ],
        'request' => [
            'wants' => Wants::class,
        ],
    ],
];
