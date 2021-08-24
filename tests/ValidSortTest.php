<?php

namespace Tests;

use Dmn\Cmn\Rules\ValidSort;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;
use Tests\DataProviders\ValidSortDataProvider;

class ValidSortTest extends TestCase
{
    use ValidSortDataProvider;

    /**
     * @test
     * @dataProvider validSortPayload
     * @param array $payload
     */
    public function validSortTest(array $payload)
    {
        $rules = [
            'sort' => new ValidSort(['id', 'name'])
        ];

        $validator = $this->app['validator']->make($payload, $rules);
        $this->assertTrue($validator->passes());

    }

    /**
     * @test
     * @dataProvider validSortNegativePayload
     * @param array $payload
     * @param array $error
     */
    public function validSortNegativeTest(array $payload, array $error)
    {
        $rules = [
            'sort' => new ValidSort(['id', 'name'])
        ];

        try {
            $validator = $this->app['validator']->make($payload, $rules);
            $validator->validate();
        } catch (ValidationException $e) {
            $this->assertEquals($error, $e->errors());
        }
    }
}