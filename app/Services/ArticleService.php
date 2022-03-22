<?php

namespace App\Services;

use App\Contracts\Repositories\ArticleRepositoryContract;
use App\Contracts\Services\ArticleServiceContract;
use App\Contracts\Services\GetArticlesFromApiServiceContract;
use App\DTO\Response\Article as ArticleResponseDTO;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class ArticleService implements ArticleServiceContract
{
    public function __construct(
       private ArticleRepositoryContract $articleRepository,
       private GetArticlesFromApiServiceContract $articleApiService
    )
    {}

    /**
     * @param string $theme
     * @throws RequestException
     * @throws ValidationException
     */
    public function addArticleByTheme(string $theme)
    {

        $result = $this->articleApiService->get($theme, Carbon::now());

        if (empty($result->json('articles'))) {
            throw new \Exception("There is not new articles by the theme: $theme");
        }

        $article = $result['articles'][0];
        $articleDto = ArticleResponseDTO::create($article, $theme);

        if (!$this->articleRepository->createFromDto($articleDto)) {
            throw new \Exception('Problems with saving in database');
        }
    }
}
