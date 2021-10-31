<?php

namespace audunru\ExportResponse\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self Csv()
 * @method static self Xlsx()
 * @method static self Xml()
 */
class MimeType extends Enum
{
    protected static function values(): array
    {
        return [
            'Csv'  => 'text/csv',
            'Xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Xml'  => 'application/xml',
        ];
    }
}
