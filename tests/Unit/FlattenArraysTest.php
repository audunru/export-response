<?php

namespace audunru\ExportResponse\Tests\Unit;

use audunru\ExportResponse\Macros\Collection\FlattenArrays;
use audunru\ExportResponse\Tests\Models\Product;
use audunru\ExportResponse\Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

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

    public function testItFlattensArraysInLazyCollection()
    {
        LazyCollection::macro('flattenArrays', app(FlattenArrays::class)());

        $result = LazyCollection::make(function () {
            yield ['id' => 1, 'name' => 'Navn', 'data' => ['foo' => 'bar'], 'meta' => []];
            yield ['id' => 2, 'name' => 'Noe annet', 'data' => ['foo' => 'bar', 'bar' => 'foo']];
        })->flattenArrays();

        $this->assertEquals(2, $result->count());
        $this->assertEquals(['id' => 1, 'name' => 'Navn', 'data.foo' => 'bar', 'data.bar' => null, 'meta' => null], $result->first());
        $this->assertEquals(['id' => 2, 'name' => 'Noe annet', 'data.foo' => 'bar', 'data.bar' => 'foo', 'meta' => null], $result->last());
    }

    public function testItFlattensCollectionOfModels()
    {
        LazyCollection::macro('flattenArrays', app(FlattenArrays::class)());

        $result = collect([
            new Product(['description' => 'Name', 'gross_cost' => 100]),
            new Product(['description' => 'Other name', 'gross_cost' => 200]),
        ])->flattenArrays();

        $this->assertEquals(2, $result->count());
        $this->assertEquals(['description' => 'Name', 'gross_cost' => 100], $result->first());
        $this->assertEquals(['description' => 'Other name', 'gross_cost' => 200], $result->last());
    }
}
