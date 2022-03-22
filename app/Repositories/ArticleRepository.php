<?php

namespace App\Repositories;

use App\Contracts\DTO\Request\ArticlesFiltersContract;
use App\Contracts\DTO\Response\ArticleContract as ArticleResponseDtoContract;
use App\Contracts\Repositories\ArticleRepositoryContract;
use App\Models\Article;
use App\Models\ArticleSource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
     * @return null|Article
     */
    public function getOldestArticleByTheme(string $theme): ?Article
    {
        return Article::oldestByTheme($theme)->first();
    }


    /**
     * @param ArticlesFiltersContract $filtersDto
     * @return Collection
     */
    public function getArticlesWithSources(ArticlesFiltersContract $filtersDto): Collection
    {
       $baseQuery = Article::select(
            'theme',
            'author',
            'title',
            'description',
            'url',
            'image_url',
            'published_at',
            'content',
            'article_sources.name as source'
        )
        ->leftJoin('article_sources', 'articles.source_id', '=', 'article_sources.id');

       if (!empty($filtersDto->getGroupWhereFilters())) {
           $this->groupQuery($baseQuery, $filtersDto->getGroupWhereFilters());
       }

       if(!empty($filtersDto->getSearchWhereFilter())) {
           ['field' => $field, 'search' => $search] = $filtersDto->getSearchWhereFilter();
            $this->searchQuery($baseQuery, $field, $search);
       }

        return $baseQuery->get();
    }

    /**
     * @param Builder $query
     * @param array $groupFilters
     * @return void
     */
    protected function groupQuery(Builder $query, array $groupFilters)
    {
        $whereConditions = array_map(
            function ($filterName) use ($groupFilters) {
                if($filterName === 'source') {
                    return ['article_sources.name', $groupFilters[$filterName]];
                }
                return [$filterName, $groupFilters[$filterName]];
            },
            array_keys($groupFilters)
        );

        $query->where($whereConditions);
    }

    /**
     * @param Builder $query
     * @param $field
     * @param $search
     * @return void
     */
    protected function searchQuery(Builder $query, $field, $search)
    {
        if ($field === 'source') {
            $query->where('article_sources.name', 'like', "%$search%");
        } else {
            $query->where($field, 'like', "%$search%");
        }
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
