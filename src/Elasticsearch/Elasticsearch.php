<?php

namespace Dmn\Cmn\Elasticsearch;

use Dmn\Exceptions\ESNoHitsFoundException;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Arr;

class Elasticsearch
{
    protected $client;

    /**
     * Elasticsearch constructor
     */
    public function __construct()
    {
        $client = config('elastic_search.handler') ?? ClientBuilder::create()
            ->setHosts(config('elastic_search.client.hosts', []))
            ->build();

        $this->client = $client;
    }

    /**
     * @param string $index
     * @param string $uuid
     * @return array
     * @throws ESNoHitsFoundException
     */
    public function findByUuid(string $index, string $uuid): array
    {
        $builder = [
        'index' => $index,
            'body' => [
                'query' => [
                    'match' => ['uuid' => $uuid],
                ],
            ],
        ];

        $games = $this->client->search($builder)['hits']['hits'];
        if (empty($games)) {
            throw new ESNoHitsFoundException($index);
        }

        return Arr::first($games)['_source'];
    }
}
