<?php

namespace Dmn\Cmn;

use Dmn\Cmn\Console\Commands\RabbitConsume;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Boot
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            RabbitConsume::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->instance(
            'path.config',
            app()->basePath() . DIRECTORY_SEPARATOR . 'config'
        );
    }
}
