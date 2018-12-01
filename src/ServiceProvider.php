<?php namespace Newway\TurboSms;


/**
 * Class ServiceProvider
 * @package Newway\TurboSms
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes(
                [
                        __DIR__ . '/../config/config.php' => config_path('turbo_sms.php'),
                ],
                'config'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        // merge & publihs config
        $configPath = __DIR__ . '/../config/config.php';
        $this->mergeConfigFrom($configPath, 'turbo_sms');
        $this->publishes([$configPath => config_path('turbo_sms.php')]);


        // init theme with default finder
        $this->app['turbo_sms'] = $this->app->singleton(
            TurboSms::class,
            function ($app) {

                $login = $app['config']->get('turbo_sms.auth.login');
                $password = $app['config']->get('turbo_sms.auth.password');
                $sender = $app['config']->get('turbo_sms.sender');
                $url = $this->app['config']->get('turbo_sms.url', 'http://turbosms.in.ua/api/wsdl.html');

                return new TurboSms($login, $password, $sender, $url);
            }
        );


    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {

        return [];
    }

}
