<?php
namespace Chanshige\WhoisProxy;

use Slim\App;

/**
 * Class Bootstrap
 *
 * @package Chanshige\WhoisProxy
 */
final class Bootstrap
{
    /** @var App */
    private $app;

    /**
     * Bootstrap constructor.
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $container = $app->getContainer();
        $container['debug'] = env('DEBUG_MODE', false);
        $container['cache_enable'] = env('CACHE_ENABLE', false);

        require APP_DIR . 'config/dependencies.php';
        require APP_DIR . 'config/handlers.php';
        require APP_DIR . 'route/api.php';

        $this->app = $app;
    }

    /**
     * Get an instance of the application.
     *
     * @return App
     */
    public function get()
    {
        return $this->app;
    }
}
