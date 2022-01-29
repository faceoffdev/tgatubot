<?php

namespace App\Framework\Providers;

use App\Common\Actions\SayTelegramAction;
use App\Common\Helper\QuestionHelper;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(QuestionHelper::class, fn () => new QuestionHelper(config('services.tgatu.question_url')));

        $this->app->bind(SayTelegramAction::class, fn () => new SayTelegramAction(app('botman')));
    }
}
