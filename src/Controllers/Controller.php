<?php

namespace Dmn\Cmn\Controllers;

use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Get per page
     *
     * @param int $limit
     * @param int $maxPerPage
     *
     * @return int
     */
    protected function getPerPage(int $limit = 50, int $maxPerPage = 100): int
    {
        $default = config('pagination.default_per_page', $limit);

        $perPage = request()->get('per_page', $default);

        if ($perPage > $maxPerPage) {
            $perPage = $maxPerPage;
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
