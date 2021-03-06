<?php

namespace Dmn\Cmn\Testing\Traits;

use ScoutElastic\Console\ElasticIndexCreateCommand;
use ScoutElastic\Console\ElasticIndexDropCommand;
use ScoutElastic\Console\ElasticIndexUpdateCommand;
use ScoutElastic\Console\ElasticMigrateModelCommand;
use ScoutElastic\Console\ElasticUpdateMappingCommand;

trait ScoutESMock
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
        $mockCreateIndex = \Mockery::mock(ElasticIndexCreateCommand::class)->makePartial();
        $mockCreateIndex->shouldReceive('handle')->andReturn();
        $this->app->instance(ElasticIndexCreateCommand::class, $mockCreateIndex);
        $mockCreateIndex->__construct();

        $mockCreateIndex = \Mockery::mock(ElasticIndexUpdateCommand::class)->makePartial();
        $mockCreateIndex->shouldReceive('handle')->andReturn();
        $this->app->instance(ElasticIndexUpdateCommand::class, $mockCreateIndex);
        $mockCreateIndex->__construct();

        $mockDropIndex = \Mockery::mock(ElasticIndexDropCommand::class)->makePartial();
        $mockDropIndex->shouldReceive('handle')->andReturn();
        $this->app->instance(ElasticIndexDropCommand::class, $mockDropIndex);
        $mockDropIndex->__construct();

        $mockUpdateMapping = \Mockery::mock(ElasticUpdateMappingCommand::class)->makePartial();
        $mockUpdateMapping->shouldReceive('handle')->andReturn();
        $this->app->instance(ElasticUpdateMappingCommand::class, $mockUpdateMapping);
        $mockUpdateMapping->__construct();

        $mockUpdateMapping = \Mockery::mock(ElasticMigrateModelCommand::class)->makePartial();
        $mockUpdateMapping->shouldReceive('handle')->andReturn();
        $this->app->instance(ElasticMigrateModelCommand::class, $mockUpdateMapping);
        $mockUpdateMapping->__construct();
    }
}
