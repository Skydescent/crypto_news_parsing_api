<?php

namespace App\Contracts\Services;

use App\Contracts\Repositories\ArticleRepositoryContract;
use Illuminate\Http\Client\RequestException;
use Illuminate\Validation\ValidationException;

interface ArticleServiceContract
{
    /**
     * @param string $theme
     * @return bool
     * @throws RequestException
     * @throws ValidationException
     */
    public function addArticleByTheme(string $theme): bool;
}
