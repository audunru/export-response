<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Macros\Collection\FlattenArrays;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Support\Collection;

class FlattenArraysTest extends TestCase
{
    public function testItFlattensArrays()
    {
        Collection::macro('flattenArrays', app(FlattenArrays::class)());

        $result = collect([
            ['id' => 1, 'name' => 'Navn', 'data' => ['foo' => 'bar'], 'meta' => []],
            ['id' => 2, 'name' => 'Noe annet', 'data' => ['foo' => 'bar', 'bar' => 'foo']],
        ])->flattenArrays();

        $this->assertEquals(2, $result->count());
        $this->assertEquals(['id' => 1, 'name' => 'Navn', 'data.foo' => 'bar', 'data.bar' => null, 'meta' => null], $result->first());
        $this->assertEquals(['id' => 2, 'name' => 'Noe annet', 'data.foo' => 'bar', 'data.bar' => 'foo', 'meta' => null], $result->last());
    }
}
