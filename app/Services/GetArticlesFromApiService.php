<?php

namespace App\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GetArticlesFromApiService
{
    public function get(): PromiseInterface|Response
    {
        //API 21676f01bbf342c3a12593de23877a43

        //themes Bitcoin, Litecoin, Ripple, Dash, Ethereum

        return Http::withHeaders([
            'X-Api-Key:' => '21676f01bbf342c3a12593de23877a43',
        ])->get('https://newsapi.org/v2/everything', [
            'q' => 'bitcoin AND litecoin AND ripple AND dash AND ethereum',
        ]);

    }
}
