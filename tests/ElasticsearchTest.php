<?php

namespace Tests;

use Dmn\Cmn\Services\Elasticsearch;
use Dmn\Exceptions\ResourceNotFoundException;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use GuzzleHttp\Ring\Client\MockHandler;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;

class ElasticsearchTest extends TestCase
{
    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $config = require __DIR__ . '/../src/config/elasticsearch.php';
        $app['config']->set('elasticsearch', $config);
    }

    /**
     * @param string $file
     */
    private function setEsEsHandler(string $file): void
    {
        $handler = new MockHandler([
            'status' => 200,
            'transfer_stats' => [
                'total_time' => 100
            ],
            'body' => fopen(__DIR__ . "/Responses/Elasticsearch/$file.json", 'r'),
            'effective_url' => 'localhost'
        ]);

        $this->app['config']->set('elasticsearch.connections.table1.handler', $handler);
    }

    /**
     * Return exception error on ES
     */
    private function setEsEsHandlerToError(): void
    {
        $handler = new MockHandler([new Missing404Exception()]);

        $this->app['config']->set('elasticsearch.connections.table1.handler', $handler);
    }

    /**
     * @test
     */
    public function esFindByUuidNotFound(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        $this->setEsEsHandler('table1_no_hits');
        $es = (new Elasticsearch(config('elasticsearch.connections.table1')));
        $es->findOrFail(['uuid' => '0ba74cd0-e5d2-4547-b715-447925943ab1']);
    }

    /**
     * @test
     */
    public function esFindByUuidError(): void
    {
        $this->expectException(\Exception::class);

        $this->setEsEsHandlerToError();
        $es = (new Elasticsearch(config('elasticsearch.connections.table1')));
        $es->findOrFail(['uuid' => '0ba74cd0-e5d2-4547-b715-447925943ab1']);
    }

    /**
     * @test
     */
    public function esFindByUuid(): void
    {
        $this->setEsEsHandler('table1');
        $es = (new Elasticsearch(config('elasticsearch.connections.table1')));
        $response = $es->findOrFail(['uuid' => '0ba74cd0-e5d2-4547-b715-447925943ab1']);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('_source', $response);
    }

    /**
     * @test
     */
    public function search(): void
    {
        $this->setEsEsHandler('table1');
        $es = (new Elasticsearch(config('elasticsearch.connections.table1')));
        $params['bool']['must'][] = [
            'terms' => [
                'field1' => 'value1',
            ],
        ];
        $response = $es->search($params);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('hits', $response);
    }

    /**
     * @test
     */
    public function searchError(): void
    {
        $this->expectException(\Exception::class);

        $this->setEsEsHandlerToError();
        $es = (new Elasticsearch(config('elasticsearch.connections.table1')));
        $params['bool']['must'][] = [
            'terms' => [
                'field1' => 'value1',
            ],
        ];
        $es->search($params);
    }

    /**
     * @test 
     * @testdox It should successfully update es document
     */
    public function esUpdate(): void
    {
        $this->setEsEsHandler('update_table1');
        $es = (new Elasticsearch(config('elasticsearch.connections.table1')));
        $body = [
            'script' => [
                'lang' => 'painless',
                'source' => "doc['my_field'].value * params['field2']}",
                'params' => [
                    "field2" => 2
                ]
            ]

        ];
        $response = $es->update(1, $body);
        $this->assertEquals($response['result'], 'updated');
    }

    /**
     * @test
     * @testdox It should throw error when trying update non-existing es document
     */
    public function esUpdateError(): void
    {
        $this->expectException(\Exception::class);
        $this->setEsEsHandlerToError();

        $es = (new Elasticsearch(config('elasticsearch.connections.table1')));
        $body = [
            'script' => [
                'lang' => 'painless',
                'source' => "doc['my_field'].value * params['field2']",
                'params' => [
                    "field2" => 2
                ]
            ]

        ];
        $es->update(1, $body);
    }
}