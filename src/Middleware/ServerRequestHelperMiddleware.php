<?php

namespace At\Theme\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use At\Theme\Helper\ServerRequestHelper;

/**
 * Class ServerRequestHelperMiddleware
 * @package Zend\Expressive\Helper
 */
class ServerRequestHelperMiddleware
{
    /**
     * @var ServerRequestHelper
     */
    private $helper;

    /**
     * ServerRequestHelperMiddleware constructor.
     * @param ServerRequestHelper $helper
     */
    public function __construct(ServerRequestHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $this->helper->setUri($request->getAttribute('originalUri'));
        return $next($request, $response);
    }
}
