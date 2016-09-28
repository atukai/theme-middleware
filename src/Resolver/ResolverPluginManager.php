<?php

namespace At\Theme\Resolver;

use Zend\ServiceManager\AbstractPluginManager;

class ResolverPluginManager extends AbstractPluginManager
{
    protected $instanceOf = ResolverInterface::class;
}