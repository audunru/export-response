<?php

namespace audunru\ExportResponse\Tests\Feature;

use audunru\ExportResponse\Tests\TestCase;

class JsonTest extends TestCase
{
    public function testItGetsJson()
    {
        $response = $this->get('/wrapped', ['Accept' => '	application/json']);

        $response
          ->assertStatus(200)
          ->assertJson(['data' => [
              [
                  'id'   => 1,
                  'name' => 'Navn',
                  'data' => [
                      'foo' => 'bar',
                  ],
                  'meta' => [],
              ],
              [
                  'id'   => 2,
                  'name' => 'Noe annet',
                  'data' => [
                      'foo' => 'bar',
                      'bar' => 'foo',
                  ],
              ],
          ]]);
    }
}
