<?php

namespace At\Theme\Resolver;

/**
 * Class ConfigurationResolver
 * @package Theme\Resolver
 */
class ConfigurationResolver implements ResolverInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * ConfigurationResolver constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return string|null
     */
    public function resolve()
    {
        if (! isset($this->config['default_theme'])) {
            return null;
        }
        return $this->config['default_theme'];
    }
}
