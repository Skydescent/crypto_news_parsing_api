<?php

namespace App\DTO\Response;

use App\Contracts\DTO\Response\ArticleContract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Article implements ArticleContract
{
    /**
     * Validation rules for dto
     */
    private const VALIDATION_RULES = [
        'source.name' => 'required|string',
        'author' => 'nullable|string',
        'title' => 'required|string',
        'description' => 'required|string',
        'url' => 'required|string',
        'urlToImage' => 'required|string',
        'publishedAt' => 'required|date',
        'content' => 'required|string'
    ];

    /**
     * @param string $sourceName
     * @param string $title
     * @param string $publishedAt
     * @param string|null $author
     * @param string $theme
     * @param string $description
     * @param string $url
     * @param string $imageUrl
     * @param string $content
     */
    private function __construct(
        private string $sourceName,
        private string $title,
        private string $publishedAt,
        private ?string $author,
        private string $theme,
        private string $description,
        private string $url,
        private string $imageUrl,
        private string $content
    )
    {}

    /**
     * @param array $responseArticle
     * @param string $theme
     * @return Article
     * @throws ValidationException
     */
    public static function create(array $responseArticle, string $theme): self
    {
        $validated =  Validator::make($responseArticle, self::VALIDATION_RULES)->validate();

        return new self(
            $validated['source']['name'],
            $validated['title'],
            $validated['publishedAt'],
            $validated['author'],
            $theme,
            $validated['description'],
            $validated['url'],
            $validated['urlToImage'],
            $validated['content']
        );
    }


    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [
            'sourceName' => $this->sourceName,
            'author' => $this->author,
            'theme' => $this->theme,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'urlToImage' => $this->imageUrl,
            'publishedAt' => $this->publishedAt,
            'content' => $this->content
        ];
    }
}
