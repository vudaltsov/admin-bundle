<?php

namespace Ruvents\AdminBundle\Twig;

use Ruvents\AdminBundle\Config\Model\Config;
use Ruvents\AdminBundle\Menu\MenuResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdminExtension extends AbstractExtension
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var MenuResolver
     */
    private $menuItemResolver;

    public function __construct(Config $config, MenuResolver $menuItemResolver)
    {
        $this->config = $config;
        $this->menuItemResolver = $menuItemResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('ruvents_admin_config', function () {
                return $this->config;
            }),
            new TwigFunction('ruvents_admin_resolve_menu', function (array $items) {
                return $this->menuItemResolver->resolve($items);
            }),
        ];
    }
}
