<?php
namespace At\Theme\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use At\Theme\Manager;

class ThemeMiddleware implements MiddlewareInterface
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
     * @param DelegateInterface $delegate
     * @return mixed
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $request = $request->withAttribute($this->attributeName, $this->themeManager->getTheme());
        return $delegate->process($request);
    }
}
