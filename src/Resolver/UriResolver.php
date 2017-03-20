<?php

namespace At\Theme\Resolver;

use Psr\Http\Message\UriInterface;

class UriResolver implements ResolverInterface
{
    const THEME_URI_PARAM_NAME = 'tmpl';

    /**
     * @var UriInterface
     */
    protected $uri;

    /**
     * @return mixed
     * @throws \Exception
     */
    public function resolve()
    {
        if (!$this->uri) {
            throw new \Exception('No Uri provided. Register UriResolverMiddleware.');
        }

        parse_str($this->uri->getQuery());

        if (isset(${self::THEME_URI_PARAM_NAME})) {
            return ${self::THEME_URI_PARAM_NAME};
        }
    }

    /**
     * @param UriInterface $uri
     */
    public function setUri(UriInterface $uri)
    {
        $this->uri = $uri;
    }
}
