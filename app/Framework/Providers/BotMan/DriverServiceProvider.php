<?php

namespace App\Framework\Providers\BotMan;

use BotMan\BotMan\Drivers\DriverManager;
use function config_path;
use Illuminate\Support\ServiceProvider;
use TheCodingMachine\Discovery\Discovery;

class DriverServiceProvider extends ServiceProvider
{
    /**
     * The drivers that should be loaded to
     * use with BotMan.
     *
     * @var array
     */
    protected $drivers = [];

    public function register()
    {
        $this->discoverDrivers();
    }

    public function boot()
    {
        foreach ($this->drivers as $driver) {
            DriverManager::loadDriver($driver);
        }
    }

    /**
     * Auto-discover BotMan drivers and load them.
     */
    public function discoverDrivers()
    {
        $drivers = Discovery::getInstance()->get('botman/driver');

        foreach ($drivers as $driver) {
            DriverManager::loadDriver($driver);
        }
    }

    /**
     * Auto-publish BotMan driver configuration files.
     */
    public static function publishDriverConfigurations()
    {
        $stubs = Discovery::getInstance()->getAssetType('botman/driver-config');

        foreach ($stubs->getAssets() as $stub) {
            $configFile = config_path('botman/' . basename($stub->getValue()));

            if (!file_exists($configFile)) {
                copy($stub->getPackageDir() . $stub->getValue(), $configFile);
            }
        }
    }
}
