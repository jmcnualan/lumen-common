<?php

namespace Tests\DataProviders;

trait ValidSortDataProvider
{
    /**
     * @return array
     */
    public function validSortPayload(): array
    {
        return [
            [
                [
                    'sort' => ['id' => 'desc'],
                ],
            ],
            [
                [
                    'sort' => [],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function validSortNegativePayload(): array
    {
        return [
            [
                [
                    'sort' => 'field1',
                ],
                [
                    'sort' => ['Invalid sort format.']
                ],
            ],
            [
                [
                    'sort' => ['field1' => 'desc'],
                ],
                [
                    'sort' => ['Invalid sort format.']
                ],
            ],
            [
                [
                    'sort' => ['id' => 'test'],
                ],
                [
                    'sort' => ['Invalid sort format.']
                ],
            ],
            [
                [
                    'sort' => ['field1' => null],
                ],
                [
                    'sort' => ['Invalid sort format.']
                ],
            ],
        ];
    }
}
