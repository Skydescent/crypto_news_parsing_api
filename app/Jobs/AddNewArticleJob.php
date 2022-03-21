<?php

namespace App\Jobs;

use App\Contracts\Repositories\ArticleRepositoryContract;
use App\Contracts\Services\ArticleServiceContract;
use App\Contracts\Services\GetArticlesFromApiServiceContract;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\DTO\Response\Article as ArticleResponseDTO;
use Illuminate\Validation\ValidationException;

class AddNewArticleJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private string $theme)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ArticleServiceContract $articleService)
    {
        try {
           $articleService->addArticleByTheme($this->theme);

        } catch (RequestException $e) {
            $this->logWithDate(
                'Add new article job failed with request to API exception: ' . $e->getMessage(),
                'debug'
            );
            return;
        } catch (ValidationException $e) {
            $this->logWithDate(
                'Invalid response data: ' . implode(' ' , Arr::flatten($e->errors())),
                'debug'
            );
            return;
        } catch (\Exception $e) {
            $this->logWithDate($e->getMessage());
            return;
        }
    }

    protected function logWithDate(string $message, $method = 'info')
    {
        Log::$method(Carbon::now()->format('Y-m-d h:m:s') . ' ' . $message);
    }
}
