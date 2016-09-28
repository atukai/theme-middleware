<?php

namespace At\Theme\Resolver;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ResolverPluginManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $manager = new ResolverPluginManager(
            $container,
            $container->get('config')['theme']['resolver_plugin_manager']
        );
        return $manager;
    }
}