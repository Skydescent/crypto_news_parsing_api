<?php

namespace App\Contracts\DTO\Response;

use Illuminate\Validation\ValidationException;

interface ArticleContract
{
    /**
     * @param array $responseArticle
     * @param string $theme
     * @return ArticleContract
     * @throws ValidationException
     */
    public static function create(array $responseArticle, string $theme): self;

    /**
     * @return array
     */
    public function getAttributes(): array;
}
