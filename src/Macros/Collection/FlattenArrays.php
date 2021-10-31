<?php

namespace audunru\ExportResponse\Macros\Collection;

use Illuminate\Support\Arr;
use Illuminate\Support\Enumerable;

class FlattenArrays
{
    public function __invoke()
    {
        return function (): Enumerable {
            $replaceIfEmpty = function (mixed $value, mixed $replace = null) {
                return empty($value) ? $replace : $value;
            };

            $items = $this->map(function (mixed $item) use ($replaceIfEmpty) {
                if (is_array($item)) {
                    return array_map($replaceIfEmpty, Arr::dot($item));
                }

                return $item;
            });

            $keys = $items->reduce(function (array $carry, $item) {
                return array_merge($carry, array_fill_keys(array_keys($item), null));
            }, []);

            return $items->map(function (array $item) use ($keys) {
                return array_merge($keys, $item);
            });
        };
    }
}
