<?php

namespace App\Contracts\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;

interface GetArticlesFromApiServiceContract
{
    /**
     * @param string $theme
     * @param Carbon|null $fromTo
     * @param int $countArticlesPerQuery
     * @return PromiseInterface|Response
     * @throws RequestException
     */
    public function get(
        string $theme,
        ?Carbon $fromTo,
        int $countArticlesPerQuery
    ): PromiseInterface|Response;
}
