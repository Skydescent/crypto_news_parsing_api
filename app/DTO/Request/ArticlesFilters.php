<?php

namespace App\DTO\Request;

use App\Contracts\DTO\Request\ArticlesFiltersContract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ArticlesFilters implements ArticlesFiltersContract
{
    /**
     *
     */
    private const GROUP_FILTERS = ['source', 'published_at', 'theme'];

    /**
     * @param string|null $source
     * @param string|null $published_at
     * @param string|null $theme
     * @param string|null $searchIn
     */
    private function __construct(
        private ?string $source,
        private ?string $published_at,
        private ?string $theme,
        private ?string $searchIn,
    )
    {}


    /**
     * @param array $filters
     * @return ArticlesFilters
     * @throws ValidationException
     */
    public static function create(array $filters): self
    {
        $validated = Validator::make($filters, self::getValidationRules())
            ->validate();

        $validated = array_merge([
            'source' => null,
            'published_at' => null,
            'theme' => null,
            'search_in' => null
        ], $validated);

        return new self(
            $validated['source'],
            $validated['published_at'],
            $validated['theme'],
            $validated['search_in']
        );
    }

    /**
     * @return array
     */
    public function getGroupWhereFilters(): array
    {
        $groupFilters = [];
        foreach(self::GROUP_FILTERS as $filterName) {
            if (!is_null($this?->$filterName)) {
                $groupFilters[$filterName] = $this->$filterName;
            }
        }
        return $groupFilters;
    }

    /**
     * @return array
     */
    public function getSearchWhereFilter(): array
    {
        if (!is_null($this?->searchIn)) {
            [$field, $search] = explode('_', $this->searchIn);
            return ['field' => $field, 'search' => $search];
        }

        return [];
    }

    /**
     * @return array
     */
    protected static function getValidationRules(): array
    {
        return [
            'source' => 'nullable|string',
            'published_at' => 'nullable|date',
            'theme' => 'nullable|string',
            'search_in' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!str_contains($value, '_')) {
                        $fail('Attribute syntax is invalid');
                    }
                    [$field] = explode('_', $value);

                    if (!in_array($field, ['theme', 'author', 'title', 'description', 'content', 'source'])) {
                        $fail('Field value in not in accepted list');
                    }

                    if ($value === 'foo') {
                        $fail('The '.$attribute.' is invalid.');
                    }
                },
            ]
        ];
    }
}
