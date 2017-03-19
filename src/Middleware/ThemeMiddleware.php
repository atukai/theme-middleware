<?php
namespace At\Theme\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use At\Theme\Manager;

class ThemeMiddleware
{
    /**
     * Name of the attribute added to the ServerRequest object
     *
     * @var string
     */
    protected $attributeName = 'theme';

    /**
     * @var Manager
     */
    protected $themeManager;

    /**
     * ThemeMiddleware constructor.
     * @param Manager $themeManager
     * @param null $attributeName
     */
    public function __construct(Manager $themeManager, $attributeName = null)
    {
        $this->themeManager = $themeManager;

        if ($attributeName) {
            $this->attributeName = $attributeName;
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $request = $request->withAttribute($this->attributeName, $this->themeManager->getTheme());
        return $next($request, $response);
    }
}
