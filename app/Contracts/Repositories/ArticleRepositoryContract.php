<?php

namespace App\Contracts\Repositories;

use App\Contracts\DTO\Response\ArticleContract as ArticleResponseDtoContract;
use App\Models\Article;

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
}
