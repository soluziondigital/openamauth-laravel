<?php

namespace Maenbn\OpenAmAuthLaravel\Providers;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Maenbn\OpenAmAuth\Factories\OpenAmFactory;
use Maenbn\OpenAmAuthLaravel\Exceptions\ConfigNotFound;
use Maenbn\OpenAmAuthLaravel\Factories\ConfigFactory;
use Maenbn\OpenAmAuthLaravel\Factories\UserProviderFactory;

class OpenAmServiceProvider extends ServiceProvider
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function boot()
    {
        $this->setupConfig();
        $this->app['auth']->provider('openam', function ($app) {

            if (!$app['config']['openam']) {
                throw new ConfigNotFound();
            }
            $config = ConfigFactory::create($app['config']['openam']);
            $openAm = OpenAmFactory::create($config);
            $app['config']['openam.cookieName'] = $config->getCookieName();

            return UserProviderFactory::create($app['config']['openam'], $openAm);
        });
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../../config/openam.php');
        $this->publishes([$source => config_path('openam.php')]);
        $this->mergeConfigFrom($source, 'openam');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'openam'
        ];
    }
}