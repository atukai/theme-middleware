<?php

namespace At\Theme\Middleware;

use Interop\Container\ContainerInterface;

/**
 * Class AssetMiddlewareFactory
 * @package At\Theme
 */
class AssetMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Manager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['themes']['assets'];
        return new AssetMiddleware($config['paths'], $config['doc_root']);
    }
}