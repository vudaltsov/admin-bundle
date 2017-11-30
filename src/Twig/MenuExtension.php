<?php
declare(strict_types=1);

namespace Ruvents\AdminBundle\Twig;

use Ruvents\AdminBundle\Menu\MenuResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    private $menuItemResolver;

    public function __construct(MenuResolver $menuItemResolver)
    {
        $this->menuItemResolver = $menuItemResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('ruvents_admin_resolve_menu', function (array $items) {
                return $this->menuItemResolver->resolve($items);
            }),
        ];
    }
}
