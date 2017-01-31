<?php

namespace At\Theme;

use Interop\Container\ContainerInterface;
use At\Theme\Helper\ServerRequestHelper;
use At\Theme\Middleware\ServerRequestHelperMiddleware;
use At\Theme\Middleware\ThemeMiddleware;
use At\Theme\Middleware\AssetMiddleware;
use At\Theme\Middleware\AssetMiddlewareFactory;
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
                    },
                    AssetMiddleware::class => AssetMiddlewareFactory::class,
                ],
            ],

            'middleware_pipeline' => [
                [
                    'middleware' => ServerRequestHelperMiddleware::class
                ],
                [
                    'middleware' => ThemeMiddleware::class
                ],
                [
                    'middleware' => AssetMiddleware::class,
                    //'error' => true
                    'priority' => -100000
                ],
            ],

            'themes' => [
                'paths' => [],
                'default_theme' => null,
                'resolvers' => [
                    ConfigurationResolver::class => 10,
                ],

                'resolver_plugin_manager' => [
                    'factories' => [
                        ConfigurationResolver::class => ConfigurationResolverFactory::class,
                        HttpRequestResolver::class => HttpRequestResolverFactory::class,
                    ]
                ],

                'assets' => [
                    'paths' => [],
                    'doc_root' => '',
                    'filters' => []
                ]
            ],
        ];
    }
}