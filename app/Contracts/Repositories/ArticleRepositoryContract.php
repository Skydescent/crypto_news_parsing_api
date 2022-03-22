<?php

namespace App\Contracts\Repositories;

use App\Contracts\DTO\Request\ArticlesFiltersContract;
use App\Contracts\DTO\Response\ArticleContract as ArticleResponseDtoContract;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

interface ArticleRepositoryContract
{
    /**
     * @param ArticleResponseDtoContract $articleDto
     * @return bool
     */
    public function createFromDto(ArticleResponseDtoContract $articleDto): bool;

    /**
     * @param string $theme
     * @return ?Article
     */
    public function getOldestArticleByTheme(string $theme): ?Article;

    /**
     * @param ArticlesFiltersContract $filtersDto
     * @return Collection
     */
    public function getArticlesWithSources(ArticlesFiltersContract $filtersDto): Collection;

}
