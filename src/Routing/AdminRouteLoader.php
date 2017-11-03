<?php

namespace Ruvents\AdminBundle\Routing;

use Ruvents\AdminBundle\Config\Model\Config;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class AdminRouteLoader extends Loader
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        /** @var RouteCollection $collection */
        $collection = $this->import($resource);

        $entityRequirement = implode('|', array_keys($this->config->entities));

        foreach ($collection as $name => $route) {
            $route->setRequirement('ruvents_admin_entity', $entityRequirement);
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'ruvents_admin' === $type;
    }
}
