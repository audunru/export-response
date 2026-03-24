<?php

namespace audunru\ExportResponse\Tests\Feature;

use audunru\ExportResponse\Tests\TestCase;

class JsonTest extends TestCase
{
    public function test_it_gets_json()
    {
        $response = $this->get('/wrapped', ['Accept' => '	application/json']);

        $response
            ->assertOk()
            ->assertJson(['data' => [
                [
                    'id' => 1,
                    'name' => 'Navn',
                    'data' => [
                        'foo' => 'bar',
                    ],
                    'meta' => [],
                ],
                [
                    'id' => 2,
                    'name' => 'Noe annet',
                    'data' => [
                        'foo' => 'bar',
                        'bar' => 'foo',
                    ],
                ],
            ]]);
    }
}
