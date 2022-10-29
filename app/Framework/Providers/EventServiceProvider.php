<?php

namespace App\Framework\Providers;

use DateTimeInterface;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->isLocal()) {
            $this->configureDbDebug();
        }
    }

    private function configureDbDebug(): void
    {
        DB::listen(function ($queryExecuted) {
            $sql = $queryExecuted->sql;

            foreach ($queryExecuted->bindings as $binding) {
                if (is_numeric($binding)) {
                    $replace = $binding;
                } elseif (is_bool($binding)) {
                    $replace = $binding ? 'true' : 'false';
                } elseif (is_string($binding)) {
                    $replace = "'" . $binding . "'";
                } elseif ($binding instanceof DateTimeInterface) {
                    $replace = "'" . $binding->format('Y-m-d H:i:s') . "'";
                } else {
                    $replace = $binding;
                }

                $sql = preg_replace("#\?#", $replace, $sql, 1);
            }

            $time = $queryExecuted->time / 1000;
            //Log::channel('sql')->debug("({$time}): {$sql}");
        });
    }
}
