<?php

namespace Dmn\Cmn\Controllers;

use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Get per page
     *
     * @param int $default
     * @param int $max
     *
     * @return int
     */
    protected function getPerPage(int $default = 50, int $max = 100): int
    {
        $default = config('pagination.default_per_page', $default);

        $perPage = request()->get('per_page', $default);

        if ($perPage > $max) {
            $perPage = $max;
        }

        return $perPage;
    }

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
