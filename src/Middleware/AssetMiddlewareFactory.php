<?php

namespace At\Theme\Middleware;

use Interop\Container\ContainerInterface;

class AssetMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return AssetMiddleware
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['themes']['assets'];
        return new AssetMiddleware($config['paths'], $config['doc_root']);
    }
}