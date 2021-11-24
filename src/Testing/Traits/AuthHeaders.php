<?php

namespace Dmn\Cmn\Testing\Traits;

use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

trait AuthHeaders
{
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
     * @param array $claims
     * @return array
     */
    protected function authHeaders(
        string $sub = '1',
        Carbon $dateTime = null,
        int $expiration = 60,
        string $jti = 'jfh3jsuc52kc82452m',
        string $prv = 'js852mdl93j5hn398jdjs852mdl93j5hn398jd',
        string $issuer = 'issuer',
        string $aud = 'aud',
        array $claims = []
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
            ->claims($claims)
            ->make();

        return [
            'Authorization' => 'Bearer ' . JWTAuth::encode($payload)->get()
        ];
    }
}
