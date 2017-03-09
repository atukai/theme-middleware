<?php

namespace At\Theme\Resolver;

use At\Theme\Helper\ServerRequestHelper;

class HttpRequestResolver implements ResolverInterface
{
    const THEME_URI_PARAM_NAME = 'tmpl';

    /**
     * @var ServerRequestHelper
     */
    protected $requestHelper;

    /**
     * @param ServerRequestHelper $helper
     */
    public function __construct(ServerRequestHelper $helper)
    {
        $this->requestHelper = $helper;
    }

    /**
     * @return string
     */
    public function resolve()
    {
        parse_str($this->requestHelper->getUri()->getQuery());

        if (isset(${self::THEME_URI_PARAM_NAME})) {
            return ${self::THEME_URI_PARAM_NAME};
        }
    }
}
