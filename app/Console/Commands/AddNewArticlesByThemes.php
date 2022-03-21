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

        foreach ($themes as $key => $theme) {
            $job = (new AddNewArticleJob($theme))->delay($key * 60);
            Queue::push($job);
        }
        Artisan::call('queue:work');
    }

    protected function getThemesFromConfig()
    {
        return config('articles.themes');
    }
}
