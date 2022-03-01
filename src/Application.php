<?php

namespace Dmn\Cmn;

use Carbon\Carbon;
use Laravel\Lumen\Application as LumenApplication;

class Application extends LumenApplication
{
    /**
     * {@inheritDoc}
     */
    public function version()
    {
        return @str_replace(
            "\n",
            '',
            file_get_contents(base_path('version'))
        );
    }

    /**
     * Get app information
     *
     * @return void
     */
    public function information()
    {
        return [
            'name' => config('app.name'),
            'version' => $this->version(),
            'timezone' => date_default_timezone_get(),
            'timestamp' => new Carbon(),
            'client_ip' => request()->ip(),
        ];
    }
}
