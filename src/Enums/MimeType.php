<?php

namespace audunru\ExportResponse\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self Csv()
 * @method static self Xml()
 */
class MimeType extends Enum
{
    protected static function values(): array
    {
        return [
            'Csv' => 'text/csv',
            'Xml' => 'application/xml',
        ];
    }
}
