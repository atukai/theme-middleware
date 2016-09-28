<?php

namespace At\Theme;

use Interop\Container\ContainerInterface;
use At\Theme\Helper\ServerRequestHelper;
use At\Theme\Middleware\ServerRequestHelperMiddleware;
use At\Theme\Middleware\ThemeMiddleware;
use At\Theme\Resolver\ConfigurationResolver;
use At\Theme\Resolver\Factory\ConfigurationResolverFactory;
use At\Theme\Resolver\Factory\HttpRequestResolverFactory;
use At\Theme\Resolver\HttpRequestResolver;
use At\Theme\Resolver\ResolverPluginManager;
use At\Theme\Resolver\ResolverPluginManagerFactory;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    Manager::class => ManagerFactory::class,
                    ResolverPluginManager::class => ResolverPluginManagerFactory::class,
                    ThemeMiddleware::class => function(ContainerInterface $c) {
                        return new ThemeMiddleware($c->get(Manager::class));
                    },
                    ServerRequestHelper::class => function(ContainerInterface $c) {
                        return new ServerRequestHelper();
                    },
                    ServerRequestHelperMiddleware::class => function(ContainerInterface $c) {
                        return new ServerRequestHelperMiddleware($c->get(ServerRequestHelper::class));
                    }
                ],
            ],

            'middleware_pipeline' => [
                'always' => [
                    'middleware' => [
                        ServerRequestHelperMiddleware::class,
                        ThemeMiddleware::class,
                    ],
                    'priority' => 10000,
                ],
            ],

            'theme' => [
                'default_theme' => null,
                'custom_theme_path' => false,
                'theme_paths' => [],
                'resolvers' => [
                    ConfigurationResolver::class => 10,
                ],

                'resolver_plugin_manager' => [
                    'factories' => [
                        ConfigurationResolver::class => ConfigurationResolverFactory::class,
                        HttpRequestResolver::class => HttpRequestResolverFactory::class,
                    ]
                ]
            ],
        ];
    }
}