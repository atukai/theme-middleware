<?php

namespace At\Theme\Resolver;

use Interop\Container\ContainerInterface;

class ResolverPluginManagerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $manager = new ResolverPluginManager(
            $container,
            $container->get('config')['themes']['resolver_plugin_manager']
        );
        return $manager;
    }
}