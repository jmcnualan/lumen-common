<?php

namespace Dmn\Cmn\Testing;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use ScoutElastic\Console\ElasticIndexCreateCommand;
use ScoutElastic\Console\ElasticIndexDropCommand;
use ScoutElastic\Console\ElasticIndexUpdateCommand;
use ScoutElastic\Console\ElasticMigrateModelCommand;
use ScoutElastic\Console\ElasticUpdateMappingCommand;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

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

    /**
     * Auth header
     *
     * @param string $sub
     * @param Carbon $dateTime
     * @param int $expiration Minutes
     * @param string $jti
     * @param string $prv
     * @param string $issuer
     * @param string $aud
     *
     * @return array
     */
    protected function authHeaders(
        string $sub = '1',
        Carbon $dateTime = null,
        int $expiration = 60,
        string $jti = 'jfh3jsuc52kc82452m',
        string $prv = 'js852mdl93j5hn398jdjs852mdl93j5hn398jd',
        string $issuer = 'issuer',
        string $aud = 'aud'
    ): array {
        if (true === is_null($dateTime)) {
            $dateTime = new Carbon();
        }

        $payload = JWTFactory::sub($sub)
            ->aud($aud)
            ->iss($issuer)
            ->iat($dateTime)
            ->exp($dateTime->copy()->addMinutes($expiration))
            ->nbf($dateTime)
            ->jti($jti)
            ->prv($prv)
            ->make();

        return [
            'Authorization' => 'Bearer ' . JWTAuth::encode($payload)->get()
        ];
    }
}
