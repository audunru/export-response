<?php

namespace audunru\ExportResponse\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self Csv()
 */
class MimeType extends Enum
{
    protected static function values(): array
    {
        return [
            'Csv' => 'text/csv',
        ];
    }
}
