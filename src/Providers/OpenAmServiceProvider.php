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
        $this->app['auth']->provider('openamauth', function ($app) {

            if (!$app['config']['openamauth']) {
                throw new ConfigNotFound();
            }
            $config = ConfigFactory::create($app['config']['openamauth']);
            $openAm = OpenAmFactory::create($config);
            $app['config']['openamauth.cookieName'] = $config->getCookieName();

            return UserProviderFactory::create($app['config']['openamauth'], $openAm);
        });
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../../config/openamauth.php');
        $this->publishes([$source => config_path('openamauth.php')]);
        $this->mergeConfigFrom($source, 'openamauth');
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
            'openamauth'
        ];
    }
}