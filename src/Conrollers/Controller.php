<?php

namespace Dmn\Cmn\Controllers;

use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * No content
     *
     * @return Response
     */
    protected function noContent(): Response
    {
        return response('', 204);
    }
}
