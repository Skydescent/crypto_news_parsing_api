<?php

namespace Tests;

use App\Contracts\Services\GetArticlesFromApiServiceContract;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class GetArticlesFromApiServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        app()->withFacades();
        $this->service = app(GetArticlesFromApiServiceContract::class);
    }

    /**
     * Test GetArticlesFromApiServiceTest throws exception on invalid key.
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function test_that_service_throws_exception_on_invalid_key()
    {
        Config::set('news_api.key', 'invalid_value');

        $this->expectException(RequestException::class);
        $this->service->get('bitcoin');
    }

    public function test_that_service_returns_expected_result()
    {
        $themes = ['bitcoin', 'litecoin', 'ripple', 'dash', 'ethereum'];
        $randomTheme = $themes[array_rand($themes)];

        $randDate = Carbon::today()->subDays(rand(0, 30));

        $resultsCount = rand(1, 5);

        $result = $this->service->get($randomTheme, $randDate, $resultsCount);

        $this->assertNotEmpty($result->json('articles'));

        $articles = $result->json('articles');
        $this->assertCount($resultsCount, $articles);

        $randomArticle = $articles[array_rand($articles)];

        $this->assertNotEmpty($randomArticle['title']);
        $this->assertNotEmpty($randomArticle['content']);
        $this->isArticleContainsTheme($randomArticle, $randomTheme);

        $this->assertNotEmpty($randomArticle['publishedAt']);

        $this->assertTrue( $randDate->gte(Carbon::parse($randomArticle['publishedAt'])));
    }

    protected function isArticleContainsTheme(array $article, string $theme)
    {
        $this->assertTrue($theme !== '');
        $isContainsTheme = str_contains(strtolower($article['title']), strtolower($theme)) ||
            str_contains(strtolower($article['content']), strtolower($theme));

        $this->assertTrue($isContainsTheme);
    }


}
