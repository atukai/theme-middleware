<?php

namespace At\Theme\Resolver\Factory;

use Interop\Container\ContainerInterface;
use At\Theme\Resolver\ConfigurationResolver;

/**
 * Class ConfigurationResolverFactory
 * @package Theme\Resolver\Factory
 */
class ConfigurationResolverFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ConfigurationResolver
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ConfigurationResolver($container->get('config')['theme']);
    }
}