<?php

namespace App\Repositories;

use App\Contracts\DTO\Response\ArticleContract as ArticleResponseDtoContract;
use App\Contracts\Repositories\ArticleRepositoryContract;
use App\Models\Article;
use App\Models\ArticleSource;
use Illuminate\Support\Carbon;

class ArticleRepository implements ArticleRepositoryContract
{
    /**
     * @param ArticleResponseDtoContract $articleDto
     * @return bool
     */
    public function createFromDto(ArticleResponseDtoContract $articleDto): bool
    {
        $attributes = $articleDto->getAttributes();

        $source = ArticleSource::firstOrCreate(
            ['name' => $attributes['sourceName']],
        );

        $article = Article::firstOrCreate(
            [
                'title' => $attributes['title'],
                'published_at' => $this->prepareDateToDbStore($attributes['publishedAt'])
            ],
            [
                'author' => $attributes['author'],
                'theme' => $attributes['theme'],
                'description' => $attributes['description'],
                'url' => $attributes['url'],
                'image_url' => $attributes['urlToImage'],
                'content' => $attributes['content'],
                'source_id' => $source->id
            ]
        );

        if (is_null($source->fresh()) || is_null($article->fresh())) {
            return false;
        }

        return true;
    }

    /**
     * @param string $theme
     * @return Article
     */
    public function getOldestArticleByTheme(string $theme): ?Article
    {
        return Article::oldestByTheme($theme)->first();
    }

    /**
     * @param string $date
     * @return string
     */
    protected function prepareDateToDbStore(string $date): string
    {
        return Carbon::parse($date)->format('Y-m-d h:m:s');
    }
}
