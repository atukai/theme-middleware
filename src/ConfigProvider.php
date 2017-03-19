<?php

namespace At\Theme;

use At\Theme\Resolver\Factory\UriResolverFactory;
use At\Theme\Resolver\UriResolver;
use Interop\Container\ContainerInterface;
use At\Theme\Middleware\UriResolverMiddleware;
use At\Theme\Middleware\ThemeMiddleware;
use At\Theme\Middleware\AssetMiddleware;
use At\Theme\Middleware\AssetMiddlewareFactory;
use At\Theme\Resolver\ConfigurationResolver;
use At\Theme\Resolver\Factory\ConfigurationResolverFactory;
use At\Theme\Resolver\ResolverPluginManager;
use At\Theme\Resolver\ResolverPluginManagerFactory;

class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
            'themes' => $this->getThemes(),
        ];
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                Manager::class => ManagerFactory::class,
                ResolverPluginManager::class => ResolverPluginManagerFactory::class,
                ThemeMiddleware::class => function (ContainerInterface $c) {
                    return new ThemeMiddleware($c->get(Manager::class));
                },
                UriResolverMiddleware::class => function (ContainerInterface $c) {
                    return new UriResolverMiddleware(
                        $c->get(ResolverPluginManager::class)->get(UriResolver::class)
                    );
                },
                AssetMiddleware::class => AssetMiddlewareFactory::class,
            ]
        ];
    }

    /**
     * @return array
     */
    public function getThemes(): array
    {
        return [
            'paths' => [],
            'default_theme' => null,

            'resolver_plugin_manager' => [
                'factories' => [
                    UriResolver::class => UriResolverFactory::class,
                    ConfigurationResolver::class => ConfigurationResolverFactory::class
                ]
            ],

            'resolvers' => [
                ConfigurationResolver::class => -PHP_INT_MAX,
            ],

            'assets' => [
                'paths' => [],
                'doc_root' => '',
                'filters' => []
            ]
        ];
    }
}
