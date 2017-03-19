<?php

namespace At\Theme\Resolver\Factory;

use Interop\Container\ContainerInterface;
use At\Theme\Resolver\UriResolver;

class UriResolverFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UriResolver
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null)
    {
        return new UriResolver();
    }
}
