<?php

namespace At\Theme\Helper;

use Zend\Diactoros\Uri;

class ServerRequestHelper
{
    /**
     * @var Uri
     */
    private $uri;

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }
}