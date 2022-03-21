<?php

namespace App\Services;

use App\Contracts\Services\GetArticlesFromApiServiceContract;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class GetArticlesFromApiService implements GetArticlesFromApiServiceContract
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
        ?Carbon $fromTo = null,
        int $countArticlesPerQuery = 1
    ): PromiseInterface|Response
    {
        ['key' => $key, 'base_url' => $base_url] = $this->setUpConfigs();

        $fromTo = $fromTo ?? Carbon::now();

        return Http::withHeaders([
                'X-Api-Key' => $key,
            ])
            ->get($base_url, [
                'q' => $theme,
                'pageSize' => $countArticlesPerQuery,
                'language' => config('news_api.result_lang'),
                'to' => $this->convertDateToApiStandard($fromTo)
            ])
            ->throw();
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function setUpConfigs(): array
    {
        if (is_null(config('news_api.base_url')) || is_null(config('news_api.key'))) {
            throw new Exception('News api configs not set');
        }

        return [
            'key' => config('news_api.key'),
            'base_url' => config('news_api.base_url')
        ];
    }

    /**
     * @param Carbon $date
     * @return string
     */
    protected function convertDateToApiStandard(Carbon $date): string
    {
        return $date->toIso8601String();
    }
}
