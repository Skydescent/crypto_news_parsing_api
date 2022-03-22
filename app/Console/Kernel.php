<?php

namespace App\Console;

use App\Console\Commands\AddNewArticlesByThemes;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AddNewArticlesByThemes::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$this->addArticleSchedule($schedule);
    }

    protected function addArticleSchedule(Schedule $schedule)
    {
        $delay = (int) config('articles.delay_per_article');

        if ($delay > 0) {
            $schedule->command(AddNewArticlesByThemes::class)
                ->withoutOverlapping()
                ->cron("*/$delay * * * *");
        }
    }
}
