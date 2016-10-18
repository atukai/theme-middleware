<?php

namespace At\Theme;

use At\Theme\Resolver\ResolverInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stdlib\PriorityQueue;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;

/**
 * Class Manager
 * @package Theme
 */
class Manager
{
    /**
     * @var null|string
     */
    protected $currentTheme;

    /**
     * @var null|\Zend\Stdlib\PriorityQueue
     */
    protected $resolvers;

    /**
     * @var null|\Zend\Stdlib\PriorityQueue
     */
    protected $themePaths;

    /**
     * @var null
     */
    protected $lastMatchedResolver;

    /**
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * @var array
     */
    protected $options;

    /**
     * Manager constructor.
     * @param TemplateRendererInterface $templateRenderer
     * @param array $options
     */
    public function __construct(TemplateRendererInterface $templateRenderer, array $options = [])
    {
        $this->templateRenderer = $templateRenderer;
        $this->options = $options;

        // Set the default theme paths (LIFO order)
        $this->themePaths = new PriorityQueue();
        if (isset($this->options['paths'])){
            $priority = 1;
            foreach ($this->options['paths'] as $path) {
                $this->themePaths->insert($path, $priority++);
            }
        }

        // Set up theme resolvers (LIFO order)
        $this->resolvers = new PriorityQueue();
    }

    /**
     * @param ResolverInterface $resolver
     * @param int $priority
     */
    public function addResolver(ResolverInterface $resolver, $priority = 1)
    {
        $this->resolvers->insert($resolver, $priority);
    }

    /**
     * Get the current used theme. If the manager was not initialized or no theme found it will return null.
     * @return string | null
     */
    public function getTheme()
    {
        if (!$this->currentTheme) {
            $this->loadTheme();
        }

        return $this->currentTheme;
    }

    /**
     * Initialize the theme by selecting a theme using the theme resolvers and updating the view resolver
     */
    protected function loadTheme()
    {
        // If already initialized then return
        if ($this->currentTheme){
            return true;
        }

        // Find the current theme that should be used
        $this->currentTheme = $this->resolveTheme();

        if (!$this->currentTheme){
            return false;
        }

        // Get theme configuration
        $config = $this->getThemeConfig($this->currentTheme);
        if (!$config){
            throw new \RuntimeException('Config file not found for theme `'. $this->currentTheme .'`.');
        }

        $allPaths = isset($config['paths']) && is_array($config['paths']) ? $config['paths'] : [];
        foreach ($allPaths as $namespace => $paths) {
            $namespace = is_numeric($namespace) ? null : $namespace;
            foreach ((array) $paths as $path) {
                $this->templateRenderer->addPath($path, $namespace);
            }
        }

        if ($this->templateRenderer instanceof PlatesRenderer) {
        } elseif ($this->templateRenderer instanceof TwigRenderer) {
        } elseif ($this->templateRenderer instanceof ZendViewRenderer) {
        }

        $this->templateRenderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'theme', $this->currentTheme);
    }

    /**
     * @param $theme
     * @return mixed|null
     */
    public function getThemeConfig($theme)
    {
        $theme = $this->cleanThemeName($theme);
        $pathIterator = $this->themePaths->getIterator();
        $config = null;
        $n = $pathIterator->count();
        while (!$config && $n-- >0) {
            $path = $pathIterator->extract();
            $configFile = $path . $theme . '/config.php';

            if (file_exists($configFile)){
                $config = include $configFile;
            }
        }

        return $config;
    }

    /**
     * @return null
     */
    public function getLastMatchedResolver()
    {
        return $this->lastMatchedResolver;
    }

    /**
     * Remove any unwanted characters from a theme name before loading it's config file
     * @param string $theme
     * @return string
     */
    protected function cleanThemeName($theme)
    {
        return str_replace(['.', '/'], '', $theme);
    }

    /**
     * Call each adapter to select a theme until one of theme returns a valid name
     * @return string | null
     */
    protected function resolveTheme()
    {
        $theme = null;
        $resolver = null;

        $iterator = $this->resolvers;
        $i = $iterator->count();

        while (!$theme && $i-- >0) {
            $resolver = $iterator->extract();
            $theme = $resolver->resolve();
        }

        if (!$theme) {
            return null;
        }

        $this->lastMatchedResolver = $resolver;
        $this->currentTheme = $theme;

        return $theme;
    }
}