<?php

namespace App\Console\Commands;

use App\Jobs\AddNewArticleJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;

class AddNewArticlesByThemes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add_new_articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding new articles to database';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $themes = $this->getThemesFromConfig();

        if (count($themes) == 0) return;

        foreach ($themes as $theme) {

            Queue::push(new AddNewArticleJob($theme));
        }

        Artisan::call('queue:work --stop-when-empty --daemon');
    }

    protected function getThemesFromConfig()
    {
        return config('articles.themes');
    }

    protected function getDelayForJob(): int
    {
        return (int) config('articles.delay_per_article');
    }
}
