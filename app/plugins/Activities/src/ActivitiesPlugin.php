<?php

declare(strict_types=1);

namespace Activities;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use App\KMP\KMPPluginInterface;
use Cake\Event\EventManager;
use Activities\Event\CallForCellsHandler;
use Activities\Services\AuthorizationManagerInterface;
use Activities\Services\DefaultAuthorizationManager;
use Activities\Event\CallForNavHandler;
use App\KMP\StaticHelpers;
use Cake\I18n\DateTime;

/**
 * Plugin for Activities
 */
class ActivitiesPlugin extends BasePlugin implements KMPPluginInterface
{
    protected int $_migrationOrder = 0;
    public function getMigrationOrder(): int
    {
        return $this->_migrationOrder;
    }

    public function __construct($config = [])
    {
        if (!isset($config['migrationOrder'])) {
            $config['migrationOrder'] = 0;
        }
        $this->_migrationOrder = $config['migrationOrder'];
    }
    /**
     * Load all the plugin configuration and bootstrap logic.
     *
     * The host application is provided as an argument. This allows you to load
     * additional plugin dependencies, or attach events.
     *
     * @param \Cake\Core\PluginApplicationInterface $app The host application
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        // From your controller, attach the UserStatistic object to the Order's event manager
        $handler = new CallForCellsHandler();
        EventManager::instance()->on($handler);

        $handler = new CallForNavHandler();
        EventManager::instance()->on($handler);

        StaticHelpers::getAppSetting("Email.SiteAdminSignature", "Webminister");
        StaticHelpers::getAppSetting("Email.SystemEmailFromAddress", "donotreply@webminister.ansteorra.org");
        StaticHelpers::getAppSetting("Activities.NextStatusCheck", DateTime::now()->subDays(1)->toDateString());
        StaticHelpers::getAppSetting("Plugin.Activities.Active", "yes");
    }

    /**
     * Add routes for the plugin.
     *
     * If your plugin has many routes and you would like to isolate them into a separate file,
     * you can create `$plugin/config/routes.php` and delete this method.
     *
     * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
     * @return void
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->plugin(
            'Activities',
            ['path' => '/activities'],
            function (RouteBuilder $builder) {
                // Add custom routes here

                $builder->fallbacks();
            }
        );
        parent::routes($routes);
    }

    /**
     * Add middleware for the plugin.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to update.
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        // Add your middlewares here

        return $middlewareQueue;
    }

    /**
     * Add commands for the plugin.
     *
     * @param \Cake\Console\CommandCollection $commands The command collection to update.
     * @return \Cake\Console\CommandCollection
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        // Add your commands here

        $commands = parent::console($commands);

        return $commands;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
        // Add your services here
        $container->add(
            AuthorizationManagerInterface::class,
            DefaultAuthorizationManager::class,
        );
    }
}
