<?php

namespace App\Contracts\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Validation\ValidationException;

interface ArticleServiceContract
{
    /**
     * @param string $theme
     * @throws RequestException
     * @throws ValidationException
     */
    public function addArticleByTheme(string $theme);
}
