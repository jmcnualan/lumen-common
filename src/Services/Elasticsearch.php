<?php

namespace Dmn\Cmn\Services;

use Dmn\Exceptions\Exception as DmnException;
use Dmn\Exceptions\ResourceNotFoundException;
use Elasticsearch\ClientBuilder;
use Exception;
use Illuminate\Support\Arr;

class Elasticsearch
{
    protected $client;

    protected $index;

    /**
     * Construct
     *
     * @param array $config
     */
    public function __construct(protected array $config)
    {
        $this->setClient();
        $this->index  = $config['index'];
    }

    /**
     * Set client
     *
     * @return void
     */
    protected function setClient(): void
    {
        $this->client = ClientBuilder::create()
            ->setHosts(explode(',', $this->config['hosts']))
            ->setHandler($this->config['handler'] ?? null)
            ->build();
    }

    /**
     * Search
     *
     * @param array $params
     *
     * @return array
     * @throws DmnException
     */
    public function search(array $params): array
    {
        $query['index'] = $this->index;
        $query['body'] = $params;

        try {
            return $this->client->search($query);
        } catch (Exception $e) {
            $response = json_decode($e->getMessage(), true);
            throw new DmnException(
                $response['error']['caused_by']['reason'],
            );
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws ResourceNotFoundException
     * @throws DmnException
     */
    public function findOrFail(array $data): array
    {
        try {
            $builder = [
                'index' => $this->index,
                'body' => [
                    'query' => [
                        'match' => $data,
                    ],
                ],
            ];

            $response = $this->client->search($builder)['hits']['hits'];
        } catch (Exception $e) {
            $response = json_decode($e->getMessage(), true);
            throw new DmnException($response['error']['reason']);
        }

        if (empty($response)) {
            throw new ResourceNotFoundException($this->index);
        }
        return Arr::first($response);
    }

    /**
     * @param int $id
     * @param array $body
     * @param array $queryParams
     * @return array
     * @throws DmnException
     */
    public function update(int $id, array $body, array $queryParams = []): array
    {
        try {
            $pathParams = [
                'id' => $id,
                'index' => $this->index,
                'body' => $body,
            ];
            return $this->client->update(array_merge($pathParams, $queryParams));
        } catch (Exception $e) {
            throw new DmnException('Something went wrong on ES Service.', 400, $e);
        }
    }
}