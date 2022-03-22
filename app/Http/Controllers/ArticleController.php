<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\ArticleRepositoryContract;
use App\DTO\Request\ArticlesFilters;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    public function index(
        Request $request,
        ArticleRepositoryContract $articleRepository
    )
    {
        try {
            $filterDto = ArticlesFilters::create($request->all());
        } catch (ValidationException $e) {
           return response()->json([
                'error_type' => 'Validation errors',
                'errors' => $e->errors()
            ], 400);
        }

        $articles = $articleRepository->getArticlesWithSources($filterDto);

        return response()->json(['result' => $articles], 200);

    }
}
