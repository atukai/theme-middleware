<?php

namespace At\Theme;

use Interop\Container\ContainerInterface;
use At\Theme\Resolver\ResolverPluginManager;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class ManagerFactory
 * @package Theme
 */
class ManagerFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Manager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $themeConfig = $container->get('config')['themes'];
        $manager = new Manager($container->get(TemplateRendererInterface::class), $themeConfig);

        if (isset($themeConfig['resolvers'])){
            $resolversPluginManager = $container->get(ResolverPluginManager::class);
            foreach($themeConfig['resolvers'] as $serviceName => $priority) {
                $resolver = $resolversPluginManager->get($serviceName);
                $manager->addResolver($resolver, $priority);
            }
        }

        return $manager;
    }
}