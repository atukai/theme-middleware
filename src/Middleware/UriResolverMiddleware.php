<?php

namespace At\Theme\Middleware;

use At\Theme\Resolver\UriResolver;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UriResolverMiddleware implements MiddlewareInterface
{
    /**
     * @var UriResolver
     */
    private $resolver;

    /**
     * @param UriResolver $resolver
     */
    public function __construct(UriResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $this->resolver->setUri($request->getUri());
        return $delegate->process($request);
    }
}
