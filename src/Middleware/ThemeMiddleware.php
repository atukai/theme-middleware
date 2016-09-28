<?php
namespace At\Theme\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use At\Theme\Manager;

/**
 * Class ThemeMiddleware
 * @package Theme\Middleware
 */
class ThemeMiddleware
{
    /**
     * @var Manager
     */
    protected $themeManager;

    /**
     * Theme constructor.
     * @param Manager $themeManager
     */
    public function __construct(Manager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $request = $request->withAttribute('theme', $this->themeManager->getTheme());
        return $next($request, $response);
    }
}