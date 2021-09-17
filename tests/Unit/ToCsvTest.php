<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Macros\Collection\ToCsv;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Support\Collection;

class ToCsvTest extends TestCase
{
    public function testItTransformsCollectionToCsv()
    {
        Collection::macro('toCsv', app(ToCsv::class)());

        $result = collect([
            ['id' => 1, 'name' => 'Ola'],
            ['id' => 2, 'name' => 'Per'],
        ])->toCsv();

        $this->assertEquals('id,name
1,Ola
2,Per
', $result);
    }
}
