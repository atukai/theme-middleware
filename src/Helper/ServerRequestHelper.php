<?php

namespace At\Theme\Helper;

use Psr\Http\Message\UriInterface;

class ServerRequestHelper
{
    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * @return UriInterface
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }
}