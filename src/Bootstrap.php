<?php
declare(strict_types=1);

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
        require APP_DIR . 'config/dependencies.php';
        require APP_DIR . 'config/resources.php';
        require APP_DIR . 'config/route_api.php';

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
