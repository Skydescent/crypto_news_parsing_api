<?php

namespace App\Contracts\DTO\Request;

use App\DTO\Request\ArticlesFilters;
use Illuminate\Validation\ValidationException;

interface ArticlesFiltersContract
{
    /**
     * @param array $filters
     * @return ArticlesFilters
     * @throws ValidationException
     */
    public static function create(array $filters): self;

    /**
     * @return array
     */
    public function getGroupWhereFilters(): array;

    /**
     * @return array
     */
    public function getSearchWhereFilter(): array;
}
