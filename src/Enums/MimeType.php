<?php

namespace audunru\ExportResponse\Enums;

enum MimeType: string
{
    case Csv = 'text/csv';
    case Xlsx = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    case Xml = 'application/xml';
}
