<?php

namespace Tests;

use Dmn\Cmn\Elasticsearch\Elasticsearch;
use Dmn\Exceptions\ESNoHitsFoundException;
use GuzzleHttp\Ring\Client\MockHandler;
use Elasticsearch\ClientBuilder;
use Orchestra\Testbench\TestCase;

class ElasticsearchTest extends TestCase
{
    /**
     * @test
     */
    public function esFindByUuidError(): void
    {
        $this->expectException(ESNoHitsFoundException::class);
        $this->mockEsEs('table1_no_hits');
        (new Elasticsearch())->findByUuid('table1', '0ba74cd0-e5d2-4547-b715-447925943ab1');
    }

    /**
     * @test
     */
    public function esFindByUuid(): void
    {
        $this->mockEsEs('table1');
        $response = (new Elasticsearch())->findByUuid('table1', '0ba74cd0-e5d2-4547-b715-447925943ab1');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('updated_at', $response);
    }

    /**
     * @param string $file
     */
    private function mockEsEs(string $file): void
    {
        $handler = new MockHandler([
            'status' => 200,
            'transfer_stats' => [
                'total_time' => 100
            ],
            'body' => fopen(__DIR__ . "/Responses/Elasticsearch/$file.json", 'r'),
            'effective_url' => 'localhost'
        ]);
        $builder = ClientBuilder::create();
        $builder->setHosts(['test']);
        $builder->setHandler($handler);
        $client = $builder->build();

        $this->app['config']->set('elastic_search.handler', $client);
    }

    /**
     * @test
     */
    public function testOriginalConstructor(): void
    {
        $this->app['config']->set('elastic_search.handler', null);
        $this->assertInstanceOf(Elasticsearch::class, (new Elasticsearch()));
    }
}