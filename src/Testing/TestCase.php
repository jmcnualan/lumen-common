<?php

namespace Dmn\Cmn\Testing;

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use ScoutElastic\Console\ElasticIndexCreateCommand;
use ScoutElastic\Console\ElasticIndexDropCommand;
use ScoutElastic\Console\ElasticUpdateMappingCommand;

abstract class TestCase extends BaseTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        if (! $this->app) {
            $this->refreshApplication();
        }

        $this->mockEsCommands();

        $this->setUpTraits();
    }

    /**
     * Mock elastic search commands
     */
    protected function mockEsCommands(): void
    {
        $mockDropIndex = Mockery::mock(ElasticIndexDropCommand::class)->makePartial();
        $mockDropIndex->shouldReceive('handle')->andReturn();
        $this->app->instance(ElasticIndexDropCommand::class, $mockDropIndex);
        $mockDropIndex->__construct();

        $mockCreateIndex = Mockery::mock(ElasticIndexCreateCommand::class)->makePartial();
        $mockCreateIndex->shouldReceive('handle')->andReturn();
        $this->app->instance(ElasticIndexCreateCommand::class, $mockCreateIndex);
        $mockCreateIndex->__construct();

        $mockUpdateMapping = Mockery::mock(ElasticUpdateMappingCommand::class)->makePartial();
        $mockUpdateMapping->shouldReceive('handle')->andReturn();
        $this->app->instance(ElasticUpdateMappingCommand::class, $mockUpdateMapping);
        $mockUpdateMapping->__construct();
    }
}
