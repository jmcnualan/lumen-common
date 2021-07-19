<?php

namespace Dmn\Cmn;

use App\Console\Commands\RabbitConsume;
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
}
